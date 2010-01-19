<?php

class xrowProductAttributeNameList extends eZSerializedObjectNameList
{

    function xrowProductAttributeNameList( $serializedNameList = false )
    {
        eZSerializedObjectNameList::eZSerializedObjectNameList( $serializedNameList );
    }

    function create( $serializedNamesString = false )
    {
        $object = new xrowProductAttributeNameList( $serializedNamesString );
        return $object;
    }

    function store( $productAttribute )
    {
        if ( $this->hasDirtyData() && is_object( $productAttribute ) )
        {
            $attributeID = $productAttribute->attribute( 'id' );
            $languages = $productAttribute->attribute( 'languages' );
            $initialLanguageID = $productAttribute->attribute( 'initial_language_id' );
            
            // update existing
            $productAttributeNames = xrowProductAttributeName::fetchList( $attributeID, array_keys( $languages ) );
            foreach ( $productAttributeNames as $attributeName )
            {
                $languageLocale = $attributeName->attribute( 'language_locale' );
                $attributeName->setAttribute( 'name', $this->nameByLanguageLocale( $languageLocale ) );
                if ( $initialLanguageID == $attributeName->attribute( 'language_id' ) )
                    $attributeName->setAttribute( 'language_id', $initialLanguageID | 1 );
                
                $attributeName->sync(); // avoid unnecessary sql-updates if nothing changed
                

                unset( $languages[$languageLocale] );
            }
            
            // create new
            if ( count( $languages ) > 0 )
            {
                foreach ( $languages as $languageLocale => $language )
                {
                    $languageID = $language->attribute( 'id' );
                    if ( $initialLanguageID == $languageID )
                        $languageID = $initialLanguageID | 1;
                    
                    $attributeName = new xrowProductAttributeName( array( 
                        'attribute_id' => $attributeID , 
                        'language_locale' => $languageLocale , 
                        'language_id' => $languageID , 
                        'name' => $this->nameByLanguageLocale( $languageLocale ) 
                    ) );
                    $attributeName->store();
                }
            }
            
            $this->setHasDirtyData( false );
        }
    }

    static function remove( $productAttribute )
    {
        xrowProductAttributeName::removeAttributeName( $productAttribute->attribute( 'id' ) );
    }

}
;

?>
