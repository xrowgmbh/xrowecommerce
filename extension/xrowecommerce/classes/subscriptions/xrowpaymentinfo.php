<?php
/*!
    \class xrowPaymentInfo xrowpaymentinfo.php
    \brief xrowPaymentInfo handles the installed paymentgateways for the ezcreditcarddatatype

    Interface for different types of payment gateways.

    Allows usage of multiple payment gateways for ezcreditcard datatype.
*/

class xrowPaymentInfo
{
    /*!
     \static
     returns an array of available payment info classes
     includes the payment info classes
     This can be called like xrowPaymentInfo::getGateways()
    */
    static function getGateways()
    {
        if ( isset( $GLOBALS['xrowPaymentInfoArray'] ) )
            return $GLOBALS['xrowPaymentInfoArray'];

        include_once( 'kernel/classes/workflowtypes/event/ezpaymentgateway/ezpaymentgatewaytype.php' );
        $gw = new eZPaymentGatewayType();
        $gatewayArray = $gw->getGateways( array( -1 ) );
        //eZDebug::writeDebug ( $gatewayArray, 'gateways' );

        $result = array();
        foreach ( $gatewayArray as $gateway )
        {
            $gatewayext = strtolower( $gateway['value'] );
            if ( file_exists( eZExtension::baseDirectory() . '/' . $gatewayext . '/classes/' . $gatewayext . 'info.php' ) )
            {
                $result[$gateway['value']] = $gateway;
                include_once( eZExtension::baseDirectory() . '/' . $gatewayext . '/classes/' . $gatewayext . 'info.php' );
                $className = $gateway['value'] . 'Info';
                $GLOBALS['xrowPaymentInfoClasses'][$gateway['value']] = new $className();
            }
        }

        $GLOBALS['xrowPaymentInfoArray'] = $result;
        return $result;
    }

    /*!
     \static
     returns the object of the $gateway Class
     This can be called like xrowPaymentInfo::getInfoClassObj( $gateway )
    */
    static function getInfoClassObj( $gateway )
    {
        if ( !isset( $GLOBALS['xrowPaymentInfoClasses'] ) )
            xrowPaymentInfo::getGateways();

        if ( isset( $GLOBALS['xrowPaymentInfoClasses'][$gateway] ) )
            return $GLOBALS['xrowPaymentInfoClasses'][$gateway];
        else
            return null;
    }

}