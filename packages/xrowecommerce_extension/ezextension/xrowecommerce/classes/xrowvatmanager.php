<?php
/**
 * File containing the eZVATManager class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

/*!
  \class xrowATManager ezvatmanager.php
  \brief The class eZVATManager does

*/

class xrowVATManager extends eZVATManager
{
    /**
     * Determine user's country.
     *
     * \public
     * \static
     */
    static function getUserCountry( $user = false, $considerPreferedCountry = true )
    {
        $requireUserCountry = eZVATManager::isUserCountryRequired();

        // If current user has set his/her preferred country via the toolbar
        if ( $considerPreferedCountry )
        {
            // return it
            $country = eZShopFunctions::getPreferredUserCountry();
            if ( $country )
            {
                eZDebug::writeDebug( "Applying user's preferred country <$country> while charging VAT" );
                return $country;
            }
        }

        // Otherwise fetch country saved in the user object.

        if ( $user === false )
        {
            $user = eZUser::currentUser();
        }

        $userObject = $user->attribute( 'contentobject' );
        $countryAttributeName = xrowVATManager::getUserCountryAttributeName( $requireUserCountry );

        if ( $countryAttributeName === null )
            return null;

        $userDataMap = $userObject->attribute( 'data_map' );

        if (strpos($countryAttributeName, ";") !== false) {
            foreach ( explode(";", $countryAttributeName) as $value )
            {
                if ( !isset( $userDataMap[$value] ) )
                {
                    if ( $requireUserCountry )
                    {
                        eZDebug::writeError( "Cannot find user country: there is no attribute '$value' in object '" .
                        $userObject->attribute( 'name' ) .
                        "' of class '" .
                        $userObject->attribute( 'class_name' ) . "'.",
                        __METHOD__ );
                    }
                    return null;
                }
                if ($userDataMap[$value]->DataText !== "" && isset($userDataMap[$value]->DataText) ) {
                    $countryAttributeName = $value;
                    break;
                }
            }
        } else {
            if ( !isset( $userDataMap[$countryAttributeName] ) )
            {
                if ( $requireUserCountry )
                {
                    eZDebug::writeError( "Cannot find user country: there is no attribute '$countryAttributeName' in object '" .
                    $userObject->attribute( 'name' ) .
                    "' of class '" .
                    $userObject->attribute( 'class_name' ) . "'.",
                    __METHOD__ );
                }
                return null;
            }
        }

        if ( $countryAttributeName === null )
            return null;
        
        $countryAttribute = $userDataMap[$countryAttributeName];
        $countryContent = $countryAttribute->attribute( 'content' );

        if ( $countryContent === null )
        {
            if ( $requireUserCountry )
            {
                eZDebug::writeError( "User country is not specified in object '" .
                                       $userObject->attribute( 'name' ) .
                                       "' of class '" .
                                       $userObject->attribute( 'class_name' ) . "'." ,
                                     __METHOD__ );
            }
            return null;
        }

        if ( is_object( $countryContent ) )
            $country = $countryContent->attribute( 'value' );
        elseif ( is_array( $countryContent ) )
        {
            if ( is_array( $countryContent['value'] ) )
            {
                foreach ( $countryContent['value'] as $item )
                {
                    $country = $item['Alpha2'];
                    break;
                }
            }
            else
            {
                $country = $countryContent['value'];
            }
        }
        else
        {
            if ( $requireUserCountry )
            {
                eZDebug::writeError( "User country is not specified or specified incorrectly in object '" .
                                       $userObject->attribute( 'name' ) .
                                       "' of class '" .
                                       $userObject->attribute( 'class_name' ) . "'." ,
                                     __METHOD__ );
            }
            return null;
        }

        return $country;
    }
    
    /**
     * Determine name of content attribute that contains user's country.
     *
     * \private
     * \static
     */
    static function getUserCountryAttributeName( $requireUserCountry )
    {
        $ini = eZINI::instance( 'xrowecommerce.ini' );
        if ( !$ini->hasVariable( 'VATSettings', 'UserCountryAttribute' ) )
        {
            if ( $requireUserCountry )
            {
                eZDebug::writeError( "Cannot find user country: please specify its attribute identifier " .
                    "in the following setting: shop.ini.[VATSettings].UserCountryAttribute",
                    __METHOD__ );
            }
            return null;
        }
    
        $countryAttributeName = $ini->variable( 'VATSettings', 'UserCountryAttribute' );
        if ( !$countryAttributeName )
        {
            if ( $requireUserCountry )
            {
                eZDebug::writeError( "Cannot find user country: empty attribute name specified " .
                    "in the following setting: shop.ini.[VATSettings].UserCountryAttribute",
                    __METHOD__ );
            }
    
            return null;
        }
    
        return $countryAttributeName;
    }
}