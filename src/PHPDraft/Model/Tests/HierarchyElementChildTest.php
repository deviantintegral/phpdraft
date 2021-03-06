<?php
/**
 * This file contains the HierarchyElementChildTest.php
 *
 * @package PHPDraft\Model
 * @author  Sean Molenaar<sean@seanmolenaar.eu>
 */
namespace PHPDraft\Model\Tests;

use PHPDraft\Core\BaseTest;
use PHPDraft\Model\HierarchyElement;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class HierarchyElementChildTest
 * @package PHPDraft\Model\Tests
 */
class HierarchyElementChildTest extends BaseTest
{
    /**
     * Mock of the parent class
     *
     * @var HierarchyElement|PHPUnit_Framework_MockObject_MockObject
     */
    protected $parent;

    public function setUp()
    {
        $this->parent = $this->getMockBuilder('\PHPDraft\Model\HierarchyElement')
                             ->getMock();
    }

    /**
     * Test if the value the class is initialized with is correct
     */
    public function testTitleSetup()
    {
        $this->assertSame(NULL, $this->class->title);
    }

    /**
     * Test if the value the class is initialized with is correct
     */
    public function testDescriptionSetup()
    {
        $this->assertSame(NULL, $this->class->description);
    }

    /**
     * Test if the value the class is initialized with is correct
     */
    public function testChildrenSetup()
    {
        $this->assertSame([], $this->class->children);
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
}