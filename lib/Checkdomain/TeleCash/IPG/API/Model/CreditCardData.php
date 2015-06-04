<?php

namespace Checkdomain\TeleCash\IPG\API\Model;


/**
 * Class CreditCardData
 */
class CreditCardData implements ElementInterface
{

    /** @var string $CardNumber */
    private $cardNumber;

    /** @var string $ExpMonth */
    private $expMonth;

    /** @var string $ExpYear */
    private $expYear;

    /**
     * @param string $cardNumber
     * @param string $expMonth
     * @param string $expYear
     */
    public function __construct($cardNumber, $expMonth, $expYear)
    {
        $this->cardNumber = $cardNumber;
        $this->expMonth   = $expMonth;
        $this->expYear    = $expYear;
    }

    /**
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function getXML(\DOMDocument $document)
    {
        $xml = $document->createElement('ns2:CreditCardData');
        $cardNumber = $document->createElement('ns1:CardNumber');
        $cardNumber->textContent = $this->cardNumber;
        $expMonth = $document->createElement('ns1:ExpMonth');
        $expMonth->textContent = $this->expMonth;
        $expYear = $document->createElement('ns1:ExpYear');
        $expYear->textContent = $this->expYear;

        $xml->appendChild($cardNumber);
        $xml->appendChild($expMonth);
        $xml->appendChild($expYear);

        return $xml;
    }
}
