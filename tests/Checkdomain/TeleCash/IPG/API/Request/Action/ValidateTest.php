<?php

namespace Checkdomain\TeleCash\IPG\API\Request\Action;
use Checkdomain\TeleCash\IPG\API\Model\CreditCardData;
use Prophecy\Prophet;

/**
 * Test case for Request/Action/Validate
 */
class ValidateTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param CreditCardData $ccData
     *
     * @dataProvider dataProvider
     */
    public function testXMLGeneration($ccData)
    {
        $prophet = new Prophet();
        $orderService  = $prophet->prophesize('Checkdomain\TeleCash\IPG\API\Service\OrderService');

        $validate = new Validate($orderService->reveal(), $ccData);
        $document = $validate->getDocument();
        $document->appendChild($validate->getElement());

        $elementValidate = $document->getElementsByTagName('ns2:Validate');
        $this->assertEquals(1, $elementValidate->length, 'Expected element Validate not found');

        $children = [];
        /** @var \DOMNode $child */
        foreach ($elementValidate->item(0)->childNodes as $child) {
            $children[$child->nodeName] = $child->nodeValue;
        }

        $this->assertArrayHasKey('ns2:CreditCardData', $children, 'Expected element CreditCardData not found');
        //no need to further test CreditCardData, as this is already covered in CreditCardDataTest
    }

    /**
     * Provides some test values
     *
     * @return array
     */
    public function dataProvider()
    {
        return [
            [new CreditCardData('12345678901234', '01', '00')]
        ];
    }
}
