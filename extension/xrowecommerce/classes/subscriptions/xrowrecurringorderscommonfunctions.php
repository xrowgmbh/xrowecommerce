<?php
/**
 * This will link to the phpDocumentor.pkg tutorial if it is unique, regardless
 * of its location
 * @tutorial bla.txt
 */


class XROWRecurringordersCommonFunctions
{
    
    static function addLeadingZero( $value )
    {
        return sprintf("%02d", $value);
    }
    /*!
     \static
     This can be called like XROWRecurringordersCommonFunctions::createDOMTreefromArray( $name, $array )
    */
    static function createDOMTreefromArray( $name, $array, $root = false )
    {
        $doc = new eZDOMDocument( $name );
        if ( !$root )
            $root = $doc->createElementNode( $name );

        $keys = array_keys( $array );
        foreach ( $keys as $key )
        {
            if ( is_array( $array[$key] ) )
            {
                $node = XROWRecurringordersCommonFunctions::createDOMTreefromArray( $key, $array[$key] );
                $root->appendChild( $node );
            }
            else
            {
                $node = $doc->createElementNode( (string)$key );
                $node->appendChild( $doc->createTextNode( $array[$key] ) );
                $root->appendChild( $node );
            }
            unset( $node );
        }
        return $root;
    }
    /**
     * This can be called like XROWRecurringordersCommonFunctions::createArrayfromXML( $xmlDoc )
     */
    static public function createArrayfromXML( $xmlDoc )
    {
        return self::createArrayfromDOMNODE(simplexml_load_string( $xmlDoc ));
    }
    /**
     * This can be called like XROWRecurringordersCommonFunctions::createArrayfromDOMNODE( $node )
     */
    static function createArrayfromDOMNODE( $xml )
    {
    	
        if ($xml instanceof SimpleXMLElement) {
            $children = $xml->children();
            $return = null;
        }

        foreach ($children as $element => $value) {
            if ($value instanceof SimpleXMLElement) {
                $values = (array)$value->children();

                if (count($values) > 0) {
                    if (is_array($return[$element])) {
                        //hook
                        foreach ($return[$element] as $k=>$v) {
                            if (!is_int($k)) {
                                $return[$element][0][$k] = $v;
                                unset($return[$element][$k]);
                            }
                        }
                        $return[$element][] = self::createArrayfromDOMNODE($value);
                    } else {
                        $return[$element] = self::createArrayfromDOMNODE($value);
                    }
                } else {
                    if (!isset($return[$element])) {
                        $return[$element] = (string)$value;
                    } else {
                        if (!is_array($return[$element])) {
                            $return[$element] = array($return[$element], (string)$value);
                        } else {
                            $return[$element][] = (string)$value;
                        }
                    }
                }
            }
        }

        if (is_array($return)) {
            return $return;
        } else {
            return false;
        }
    }
}
?>