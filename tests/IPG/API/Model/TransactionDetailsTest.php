<?php

namespace Checkdomain\TeleCash\IPG\API\Model;

/**
 * Test case for Payment
 *
 * @package Checkdomain\TeleCash\IPG\API\Model
 */
class TransactionDetailsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $comments
     * @param string $invoiceNumber
     *
     * @dataProvider dataProvider
     */
    public function testXMLGeneration($comments, $invoiceNumber)
    {
        $ccData   = new TransactionDetails($comments, $invoiceNumber);
        $document = new \DOMDocument('1.0', 'UTF-8');
        $xml      = $ccData->getXML($document);
        $document->appendChild($xml);

        $elementPayment = $document->getElementsByTagName('ns2:TransactionDetails');
        $this->assertEquals(1, $elementPayment->length, 'Expected element TransactionDetails not found');

        $children = [];
        /** @var \DOMNode $child */
        foreach ($elementPayment->item(0)->childNodes as $child) {
            $children[$child->nodeName] = $child->nodeValue;
        }

        $this->assertArrayHasKey('ns1:Comments', $children, 'Expected element Comments not found');
        $this->assertEquals($comments, $children['ns1:Comments'], 'Comments data id did not match');

        if ($invoiceNumber !== null) {
            $this->assertArrayHasKey('ns1:InvoiceNumber', $children, 'Expected element InvoiceNumber not found');
            $this->assertEquals($invoiceNumber, $children['ns1:InvoiceNumber'], 'InvoiceNumber did not match');
        } else {
            $this->assertArrayNotHasKey('ns1:InvoiceNumber', $children, 'Unexpected element InvoiceNumber was found');
        }
    }

    /**
     * Provides some test values
     *
     * @return array
     */
    public function dataProvider()
    {
        return [
            ['Testkommentar', null],
            ['Testkommentar', '1234-TestTestTest']
        ];
    }
}
