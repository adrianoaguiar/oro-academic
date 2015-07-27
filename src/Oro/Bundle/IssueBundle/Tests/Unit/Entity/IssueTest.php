<?php

namespace Oro\Bundle\IssueBundle\Tests\Unit\Entity;

use Oro\Bundle\IssueBundle\Entity\Issue;

/**
 * Class IssueTest
 * @package Oro\Bundle\IssueBundle\Tests\Unit\Entity
 */
class IssueTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @param string $property
     * @param string $value
     * @param string $expected
     * @dataProvider getSetDataProvider
     */
    public function testGetSet($property, $value, $expected)
    {
        $obj = new Issue();
        call_user_func_array(array($obj, 'set' . ucfirst($property)), array($value));
        $this->assertEquals($expected, call_user_func_array(array($obj, 'get' . ucfirst($property)), array()));
    }

    /**
     * @return array
     */
    public function getSetDataProvider()
    {
        $user = $this->getMock('Oro\Bundle\UserBundle\Entity\User');
        $now = new \DateTime('now', new \DateTimeZone('UTC'));

        $issuePriority = $this
            ->getMockBuilder('Oro\Bundle\IssueBundle\Entity\IssuePriority')
            ->disableOriginalConstructor()
            ->getMock();

        return array(
            'code'        => array('code', 'TEST_ISSUE', 'TEST_ISSUE'),
            'summary'     => array('summary', 'Test Issue summary', 'Test Issue summary'),
            'description' => array('description', 'Test Issue description', 'Test Issue description'),
            'createdAt'   => array('createdAt', $now, $now),
            'updatedAt'   => array('updatedAt', $now, $now),
            'type'   => array('type', 'task', 'task'),
            'priority'    => array('priority', $issuePriority, $issuePriority),
            'assignee'    => array('assignee', $user, $user),
            'reporter'       => array('reporter', $user, $user),
        );
    }

}
