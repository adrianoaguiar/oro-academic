<?php

namespace Oro\Bundle\IssueBundle\Tests\Unit\Entity;

use Oro\Bundle\IssueBundle\Entity\IssuePriority;

/**
 * Class IssuePriorityTest
 * @package Oro\Bundle\IssueBundle\Tests\Unit\Entity
 */
class IssuePriorityTest extends \PHPUnit_Framework_TestCase
{
    protected $object;
    protected $name = 'TEST_NAME';
    protected $label = 'TEST_LABEL';

    protected function setUp()
    {
        $this->object = new IssuePriority($this->name);
    }

    public function testGetSetName()
    {
        $this->object->setName($this->name);
        $this->assertTrue($this->object->getName() == $this->name);
    }

    public function testGetSetLabel()
    {
        $this->object->setLabel($this->label);
        $this->assertTrue($this->object->getLabel() == $this->label);
    }

    public function testGetSetPriority()
    {
        $priority = 10;
        $this->object->setPriority($priority);
        $this->assertTrue($this->object->getPriority() == $priority);
    }
}
