<?php

namespace Checkdomain\TeleCash\IPG\API\Model;

class TransactionDetails implements ElementInterface
{
    /**
     * @var string $namespace
     */
    private $namespace;

    /**
     * @var string $comments
     */
    private $comments;

    /**
     * @var string $invoiceNumber
     */
    private $invoiceNumber;

    /**
     * TransactionDetails constructor.
     *
     * @param string      $namespace
     * @param string      $comments
     * @param string|null $invoiceNumber
     */
    public function __construct($namespace, $comments, $invoiceNumber = null)
    {
        $this->namespace     = $namespace;
        $this->comments      = $comments;
        $this->invoiceNumber = $invoiceNumber;
    }

    /**
     * @param \DOMDocument $document
     *
     * @return \DOMElement
     */
    public function getXML(\DOMDocument $document)
    {
        $xml = $document->createElement(sprintf('%s:TransactionDetails', $this->namespace));

        $comments = $document->createElement('ns1:Comments');
        $comments->textContent = $this->comments;

        $xml->appendChild($comments);

        if (!empty($this->invoiceNumber)) {
            $invoiceNumber = $document->createElement('ns1:InvoiceNumber');
            $invoiceNumber->textContent = $this->invoiceNumber;

            $xml->appendChild($invoiceNumber);
        }

        return $xml;
    }
}
