<?php
/**
 * File containing the ezcFeedGeoModule class.
 *
 * @package Feed
 * @version 1.2.1
 * @copyright Copyright (C) 2005-2008 eZ systems as. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @filesource
 */

/**
 * Support for the Google module: data container, generator, parser.
 *
 * Specifications: {@link http://code.google.com/apis/base/}.
 *
 * Create example:
 *
 * <code>
 * <?php
 * // $feed is an ezcFeed object
 * $item = $feed->add( 'item' );
 * $module = $item->addModule( 'GoogleProduct' );
 * $module->image_link = http://www.google.com/images/google_sm.gif;
 * $module->expiration_date = '2005-11-15';
 * $module->job_function = 'Analyst';
 * $module->location = '1600 Amphitheatre Parkway, Mountain View, CA, 94043, USA';
 * ?>
 * </code>
 *
 * Parse example:
 *
 * <code>
 * <?php
 * // $item is an ezcFeedEntryElement object
 * $location = isset( $item->Google->location ) ? $item->Geo->location->__toString() : null;
 * $job_function = isset( $item->Google->job_function ) ? $item->Geo->job_function->__toString() : null;
 * ?>
 * </code>
 *
 * @property ezcFeedTextElement $condition
 * @property ezcFeedTextElement $id
 * @property ezcFeedTextElement $price
 * @property ezcFeedTextElement $brand
 * @property ezcFeedTextElement $image_link
 * @property ezcFeedTextElement $isbn
 * @property ezcFeedTextElement $mpn
 * @property ezcFeedTextElement $product_type
 * @property ezcFeedTextElement $upc
 * @property ezcFeedTextElement $weight
 * @property ezcFeedTextElement $expiration_date
 * @property ezcFeedTextElement $color
 * @property ezcFeedTextElement $compatible_with
 * @property ezcFeedTextElement $expiration_date
 * @property ezcFeedTextElement $height
 * @property ezcFeedTextElement $length
 * @property ezcFeedTextElement $model_number
 * @property ezcFeedTextElement $online_only
 * @property ezcFeedTextElement $price_type
 * @property ezcFeedTextElement $quantity
 * @property ezcFeedTextElement $pickup
 * @property ezcFeedTextElement $payment_accepted
 * @property ezcFeedTextElement $payment_notes
 *
 * @package Feed
 * @version 1.2.1
 */
class ezcFeedGoogleProductModule extends ezcFeedModule
{
    /**
     * List of all known Google Product Attributes.
     * @var Array
     */
    static $GoogleAttributes = array(
                // Products required
                'condition', 'id', 'price',
                // Products recommended
                'brand', 'image_link', 'isbn', 'mpn', 'product_type', 'upc', 'weight',
                // Products optional
                'expiration_date', 'color', 'compatible_with', 'expiration_date', 'height', 'length', 'model_number',
                'online_only', 'price_type', 'quantity', 'pickup', 'payment_accepted', 'payment_notes',
                /*/ Events and Activities
                'event_date_range', 'event_type', 'performer', 'venue_description', 'venue_name', 'venue_type', 'venue_website',
                // Housing
                'agent', 'area', 'bathrooms', 'bedrooms', 'broker', 'feature', 'hoa_dues', 'latitude', 'longitude', 'listing_status', 'listing_type', 'lot_size', 'mls_listing_id', 'mls_name', 'model', 'open_house_date_range', 'parking', 'property_taxes', 'property_type', 'provider_class', 'school', 'school_district', 'style', 'video_link', 'year', 'zoning',
                // Jobs
                'education', 'employer', 'immigration_status', 'job_function', 'job_industry', 'job_type', 'publish_date', 'salary', 'salary_type',
                // Vehicles
                'color', 'condition', 'make', 'mileage', 'model', 'vehicle_type', 'vin', 'year'*/

               // german attributes
               // http://www.google.com/support/merchants/bin/answer.py?hl=de&answer=160085
                'autor', 'beschreibung', 'bild_url', 'breite', 'ean', 'einband', 'farbe', 'genre', 'größe', 'herstellungsjahr', 'höhe',
                'kompatible_mit', 'länge', 'link', 'marke', 'menge', 'merkmal', 'modellnummer', 'preis', 'preisart', 'produktart',
                'verfallsdatum', 'verlag', 'versand', 'versandgewicht', 'youtube', 'zahlungshinweise', 'zahlungsmethode', 'zustand'
     );

    /**
     * Constructs a new ezcFeedContentModule object.
     *
     * @param string $level The level of the data container ('feed' or 'item')
     */
    public function __construct( $level = 'feed' )
    {
        parent::__construct( $level );
    }

    /**
     * Sets the property $name to $value.
     *
     * @throws ezcBasePropertyNotFoundException
     *         if the property $name is not defined
     *
     * @param string $name The property name
     * @param mixed $value The property value
     * @ignore
     */
    public function __set( $name, $value )
    {
        if ( $this->isElementAllowed( $name ) )
        {
            $node = $this->add( $name );
            $node->text = $value;
        }
        else
        {
            parent::__set( $name, $value );
        }
    }

    /**
     * Returns the value of property $name.
     *
     * @throws ezcBasePropertyNotFoundException
     *         if the property $name is not defined
     *
     * @param string $name The property name
     * @return mixed
     * @ignore
     */
    public function __get( $name )
    {
        if ( $this->isElementAllowed( $name ) )
        {
            return $this->properties[$name];
        }
        else
        {
            return parent::__get( $name );
        }
    }

    /**
     * Returns if the property $name is set.
     *
     * @param string $name The property name
     * @return bool
     * @ignore
     */
    public function __isset( $name )
    {
        if ( $this->isElementAllowed( $name ) )
        {
            return isset( $this->properties[$name] );
        }
        else
        {
            return parent::__isset( $name );
        }
    }

    /**
     * Returns true if the element $name is allowed in the current module at the
     * current level (feed or item), and false otherwise.
     *
     * @param string $name The element name to check if allowed in the current module and level (feed or item)
     * @return bool
     */
    public function isElementAllowed( $name )
    {
        switch ( $this->level )
        {
            case 'feed':
                    return false;
                break;

            case 'item':
                if ( in_array( $name, self::$GoogleAttributes ) )
                {
                    return true;
                }
                break;
        }
        return false;
    }

    /**
     * Adds a new ezcFeedElement element with name $name to this module and
     * returns it.
     *
     * @throws ezcFeedUnsupportedElementException
     *         if trying to add an element which is not supported.
     *
     * @param string $name The element name
     * @return ezcFeedElement
     */
    public function add( $name )
    {
        if ( $this->isElementAllowed( $name ) )
        {
            if ( in_array( $name, self::$GoogleAttributes ) )
            {
                $node = new ezcFeedTextElement();
            }

            $this->properties[$name] = $node;
            return $node;
        }
        else
        {
            throw new ezcFeedUnsupportedElementException( $name );
        }
    }

    /**
     * Adds the module elements to the $xml XML document, in the container $root.
     *
     * @param DOMDocument $xml The XML document in which to add the module elements
     * @param DOMNode $root The parent node which will contain the module elements
     */
    public function generate( DOMDocument $xml, DOMNode $root )
    {
                       
        $elements = self::$GoogleAttributes;
        foreach ( $elements as $element )
        {
            if ( isset( $this->$element ) )
            {
                $elementTag = $xml->createElement( $this->getNamespacePrefix() . ':' . $element );
                $root->appendChild( $elementTag );

                $elementTag->nodeValue = $this->$element->__toString();
            }
        }
    }

    /**
     * Parses the XML element $node and creates a feed element in the current
     * module with name $name.
     *
     * @param string $name The name of the element belonging to the module
     * @param DOMElement $node The XML child from which to take the values for $name
     */
    public function parse( $name, DOMElement $node )
    {
        if ( $this->isElementAllowed( $name ) )
        {
            $element = $this->add( $name );
            $value = $node->textContent;

            if ( in_array( $name, self::$GoogleAttributes ) )
            {
                $element->text = $value;
            }
        }
    }

    /**
     * Returns the module name (Geo').
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'GoogleProduct';
    }

    /**
     * Returns the namespace for this module ('http://www.w3.org/2003/01/geo/wgs84_pos#').
     *
     * @return string
     */
    public static function getNamespace()
    {
        return 'http://base.google.com/ns/1.0';
    }

    /**
     * Returns the namespace prefix for this module ('geo').
     *
     * @return string
     */
    public static function getNamespacePrefix()
    {
        return 'g';
    }
}
?>