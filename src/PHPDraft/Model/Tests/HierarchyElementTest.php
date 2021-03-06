<?php
/**
 * This file contains the HierarchyElementTest.php
 *
 * @package PHPDraft\Model
 * @author  Sean Molenaar<sean@seanmolenaar.eu>
 */

namespace PHPDraft\Model\Tests;

use PHPDraft\Core\BaseTest;
use PHPDraft\Model\HierarchyElement;
use PHPUnit_Framework_MockObject_MockObject;
use ReflectionClass;

/**
 * Class HierarchyElementTest
 * @covers \PHPDraft\Model\HierarchyElement
 */
class HierarchyElementTest extends BaseTest
{
    /**
     * Mock of the parent class
     *
     * @var HierarchyElement|PHPUnit_Framework_MockObject_MockObject
     */
    protected $parent;

    /**
     * Set up
     */
    public function setUp()
    {

        $this->parent     = $this->getMockBuilder('\PHPDraft\Model\Transition')
                                 ->disableOriginalConstructor()
                                 ->getMock();
        $this->class      = $this->getMockForAbstractClass('PHPDraft\Model\HierarchyElement');
        $this->reflection = new ReflectionClass('PHPDraft\Model\HierarchyElement');
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        unset($this->class);
        unset($this->reflection);
    }

    /**
     * Test if the value the class is initialized with is correct
     */
    public function testSetupCorrectly()
    {
        $property = $this->reflection->getProperty('parent');
        $property->setAccessible(TRUE);
        $this->assertNull($property->getValue($this->class));
    }

    /**
     * Test basic parse functions
     */
    public function testParseIsCalled()
    {
        $property = $this->reflection->getProperty('parent');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, $this->parent);

        $obj = '{"meta":{"title":"TEST"}, "content":""}';

        $this->class->parse(json_decode($obj));

        $this->assertSame($this->parent, $property->getValue($this->class));

        $href_property = $this->reflection->getProperty('title');
        $href_property->setAccessible(TRUE);
        $this->assertSame('TEST', $href_property->getValue($this->class));
    }

    /**
     * Test basic parse functions
     */
    public function testParseIsCalledLoop()
    {
        $property = $this->reflection->getProperty('parent');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, $this->parent);

        $obj = '{"meta":{"title":"TEST"}, "content":[{"element":"copy", "content":"hello"}]}';

        $this->class->parse(json_decode($obj));

        $this->assertSame($this->parent, $property->getValue($this->class));

        $href_property = $this->reflection->getProperty('title');
        $href_property->setAccessible(TRUE);
        $this->assertSame('TEST', $href_property->getValue($this->class));

        $href_property = $this->reflection->getProperty('description');
        $href_property->setAccessible(TRUE);
        $this->assertSame('hello'.PHP_EOL, $href_property->getValue($this->class));
    }

    /**
     * Test basic parse functions
     */
    public function testParseIsCalledSlice()
    {
        $property = $this->reflection->getProperty('parent');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, $this->parent);

        $obj = '{"meta":{"title":"TEST"}, "content":[{"element":"copy", "content":"hello"}, {"element":"test", "content":"hello"}]}';

        $this->class->parse(json_decode($obj));

        $this->assertSame($this->parent, $property->getValue($this->class));

        $href_property = $this->reflection->getProperty('title');
        $href_property->setAccessible(TRUE);
        $this->assertSame('TEST', $href_property->getValue($this->class));

        $href_property = $this->reflection->getProperty('description');
        $href_property->setAccessible(TRUE);
        $this->assertSame('hello'.PHP_EOL, $href_property->getValue($this->class));
    }


    /**
     * Test basic get_href
     */
    public function testGetHrefIsCalledWithParent()
    {
        $property = $this->reflection->getProperty('parent');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, $this->parent);

        $this->parent->expects($this->once())
                     ->method('get_href')
                     ->will($this->returnValue('hello'));

        $result = $this->class->get_href();

        $this->assertSame($result, 'hello-');
    }

    /**
     * Test basic get_href
     */
    public function testGetHrefIsCalledWithoutParent()
    {
        $result = $this->class->get_href();

        $this->assertSame($result, '');
    }

    /**
     * Test basic get_href
     */
    public function testGetHrefIsCalledWithTitleWithSpaces()
    {
        $property = $this->reflection->getProperty('title');
        $property->setAccessible(TRUE);
        $property->setValue($this->class, 'some title');

        $parent_property = $this->reflection->getProperty('parent');
        $parent_property->setAccessible(TRUE);
        $parent_property->setValue($this->class, $this->parent);

        $this->parent->expects($this->once())
                     ->method('get_href')
                     ->will($this->returnValue('hello'));

        $result = $this->class->get_href();

        $this->assertSame($result, 'hello-some-title');
    }
}