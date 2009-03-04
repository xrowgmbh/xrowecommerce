<?php

/**
 * Straight from the WSDL schema
 */
class checkVat
{
    /**
     * @var string
     */
    public $countryCode;
    /**
     * @var string
     */
    public $vatNumber;

    function __construct( $cc, $vat )
    {
        $this->countryCode = $cc;
        $this->vatNumber = $vat;
    }
}
?>