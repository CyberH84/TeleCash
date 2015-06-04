<?php

namespace Checkdomain\TeleCash\IPG\API\Model;

/**
 * Test case for Payment
 *
 * @package Checkdomain\TeleCash\IPG\API\Model
 */
class PaymentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $hostedDataId
     * @param float  $amount
     *
     * @dataProvider dataProvider
     */
    public function testXMLGeneration($hostedDataId, $amount)
    {
        $ccData   = new Payment($hostedDataId, $amount);
        $document = new \DOMDocument('1.0', 'UTF-8');
        $xml      = $ccData->getXML($document);
        $document->appendChild($xml);

        $elementPayment = $document->getElementsByTagName('ns1:Payment');
        $this->assertEquals(1, $elementPayment->length, 'Expected element Payment not found');

        $children = [];
        /** @var \DOMNode $child */
        foreach ($elementPayment->item(0)->childNodes as $child) {
            $children[$child->nodeName] = $child->nodeValue;
        }

        $this->assertArrayHasKey('ns1:HostedDataID', $children, 'Expected element HostedDataID not found');
        $this->assertEquals($hostedDataId, $children['ns1:HostedDataID'], 'Hosted data id did not match');

        if ($amount !== null) {
            $this->assertArrayHasKey('ns1:ChargeTotal', $children, 'Expected element ChargeTotal not found');
            $this->assertEquals($amount, $children['ns1:ChargeTotal'], 'Charge total did not match');
            $this->assertArrayHasKey('ns1:Currency', $children, 'Expected element Currency not found');
            $this->assertEquals('978', $children['ns1:Currency'], 'Currency did not match');
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
            ['abc-def', null],
            ['abc-def', 1.23]
        ];
    }
}
