<?php

namespace Checkdomain\TeleCash\IPG\API\Request\Action;
use Checkdomain\TeleCash\IPG\API\Model\Payment;
use Checkdomain\TeleCash\IPG\API\Model\TransactionDetails;
use Checkdomain\TeleCash\IPG\API\Request\Transaction\SellHostedData;
use Prophecy\Prophet;

/**
 * Test case for Request/Transaction/SellHostedData
 */
class SellHostedDataTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param Payment            $payment
     * @param TransactionDetails $transactionDetails
     *
     * @dataProvider dataProvider
     */
    public function testXMLGeneration($payment, $transactionDetails)
    {
        $prophet = new Prophet();
        $orderService  = $prophet->prophesize('Checkdomain\TeleCash\IPG\API\Service\OrderService');

        $sellHosted = new SellHostedData($orderService->reveal(), $payment, $transactionDetails);
        $document   = $sellHosted->getDocument();
        $document->appendChild($sellHosted->getElement());

        $elementCCType = $document->getElementsByTagName('ns1:CreditCardTxType');
        $this->assertEquals(1, $elementCCType->length, 'Expected element CreditCardTxType not found');

        $children = [];
        /** @var \DOMNode $child */
        foreach ($elementCCType->item(0)->childNodes as $child) {
            $children[$child->nodeName] = $child->nodeValue;
        }

        $this->assertArrayHasKey('ns1:Type', $children, 'Expected element Type not found');
        $this->assertEquals('sale', $children['ns1:Type'], 'Type did not match');

        $elementPayment = $document->getElementsByTagName('ns1:Payment');
        $this->assertEquals(1, $elementPayment->length, 'Expected element Payment not found');

        if ($transactionDetails !== null) {
            $elementDetails = $document->getElementsByTagName('ns2:TransactionDetails');
            $this->assertEquals(1, $elementDetails->length, 'Expected element TransactionDetails not found');
        } else {
            $elementDetails = $document->getElementsByTagName('ns2:TransactionDetails');
            $this->assertEquals(0, $elementDetails->length, 'Unexpected element TransactionDetails was found');
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
            [new Payment('abc-def'), null],
            [new Payment('abc-def'), new TransactionDetails('Testkommentar', '1234-TestTestTest')],
        ];
    }
}
