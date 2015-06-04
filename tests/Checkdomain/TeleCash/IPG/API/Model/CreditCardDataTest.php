<?php

namespace Checkdomain\TeleCash\IPG\API\Model;

/**
 * Test case for CreditCardData
 *
 * @package Checkdomain\TeleCash\IPG\API\Model
 */
class CreditCardDataTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param string $ccNumber
     * @param string $validMonth
     * @param string $validYear
     *
     * @dataProvider dataProvider
     */
    public function testXMLGeneration($ccNumber, $validMonth, $validYear)
    {
        $ccData   = new CreditCardData($ccNumber, $validMonth, $validYear);
        $document = new \DOMDocument('1.0', 'UTF-8');
        $xml      = $ccData->getXML($document);
        $document->appendChild($xml);

        $elementCCData = $document->getElementsByTagName('ns2:CreditCardData');
        $this->assertEquals(1, $elementCCData->length, 'Expected element CreditCardData not found');

        $children = [];
        /** @var \DOMNode $child */
        foreach ($elementCCData->item(0)->childNodes as $child) {
            $children[$child->nodeName] = $child->nodeValue;
        }

        $this->assertArrayHasKey('ns1:CardNumber', $children, 'Expected element CardNumber not found');
        $this->assertEquals($ccNumber, $children['ns1:CardNumber'], 'Card number did not match');
        $this->assertArrayHasKey('ns1:ExpMonth', $children, 'Expected element ExpMonth not found');
        $this->assertEquals($validMonth, $children['ns1:ExpMonth'], 'Valid month did not match');
        $this->assertArrayHasKey('ns1:ExpYear', $children, 'Expected element ExpYear not found');
        $this->assertEquals($validYear, $children['ns1:ExpYear'], 'Valid year did not match');
    }

    /**
     * Provides some test values
     *
     * @return array
     */
    public function dataProvider()
    {
        return [
            ['12345678901234', '12', '20'],
            ['41111111111111', '10', '20'],
            ['11122233344455', '12', '16']
        ];
    }
}
