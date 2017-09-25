<?php

namespace Checkdomain\TeleCash\IPG\API\Request\Action;
use Checkdomain\TeleCash\IPG\API\Model\DataStorageItem;
use Prophecy\Prophet;

/**
 * Test case for Request/Action/DeleteHostedData
 */
class DeleteHostedDataTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param DataStorageItem $storageItem
     *
     * @dataProvider dataProvider
     */
    public function testXMLGeneration($storageItem)
    {
        $prophet = new Prophet();
        $orderService  = $prophet->prophesize('Checkdomain\TeleCash\IPG\API\Service\OrderService');

        $delete   = new DeleteHostedData($orderService->reveal(), $storageItem);
        $document = $delete->getDocument();
        $document->appendChild($delete->getElement());

        $elementStore = $document->getElementsByTagName('ns2:StoreHostedData');
        $this->assertEquals(1, $elementStore->length, 'Expected element StoreHostedData not found');

        $children = [];
        /** @var \DOMNode $child */
        foreach ($elementStore->item(0)->childNodes as $child) {
            $children[$child->nodeName] = $child->nodeValue;
        }

        $this->assertArrayHasKey('ns2:DataStorageItem', $children, 'Expected element DataStorageItem not found');
        //no need to further test DataStorageItem, as this is already covered in DataStorageItemTest
    }

    /**
     * Provides some test values
     *
     * @return array
     */
    public function dataProvider()
    {
        return [
            [new DataStorageItem('abc-def')]
        ];
    }
}
