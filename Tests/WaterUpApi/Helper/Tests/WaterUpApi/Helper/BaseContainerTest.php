<?php
/**
 * Author: Joris Rietveld <jorisrietveld@gmail.com>
 * Created: 25-05-2017 12:43
 * Licence: GNU General Public licence version 3 <https://www.gnu.org/licenses/quick-guide-gplv3.html>
 */
declare(strict_types=1);

namespace Tests\WaterUpApi\Helper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Debug\Debug;

class BaseContainerTest extends TestCase
{
    protected $classPath = '\\StendenINF1I\\WaterUpApi\\Helper\\';
    protected $className = 'BaseContainer';

    protected $baseContainerMock;

    /**
     * The data that will be passed to the BaseContainer for testing its methods.
     *
     * @var array
     */
    protected $testData = [
        'integer' => 1,
        'string'  => "Hello world",
        "float"   => 1.1,
        'boolean' => true,
        'array'   => [1, 2, 3],
        'hex'     => 0xFF,
        'octal'   => 0123,
    ];

    public function setUp()
    {
        /**
         * Add some extra test data like an DateTime object and closure.
         */
        $this->testData = array_merge($this->testData, [
            'object'   => (object)['TestProperty' => 'TestValue'],
            'DateTime' => new \DateTime(),
            'closure'  => function (string $testArgument = 'test')
            {
                return strrev($testArgument);
            },
        ]);

        $this->baseContainerMock = $this->getMockBuilder($this->classPath . $this->className)
            ->setConstructorArgs([$this->testData])
            ->getMockForAbstractClass();
    }

    /**
     * Test the $container->getInt or $container->getInteger methods. They should return an integer or cast it to an
     * integer if possible.
     */
    public function testGetInt()
    {
        $this->assertEquals(
            1,
            $this->baseContainerMock->getInt('integer', -1)
        );

        $this->assertEquals(
            0xFF,
            $this->baseContainerMock->getInt('hex', -1, [FILTER_FLAG_ALLOW_HEX])
        );

        $this->assertEquals(
            0123,
            $this->baseContainerMock->getInt('octal', -1, [FILTER_FLAG_ALLOW_OCTAL])
        );

        $this->assertNull($this->baseContainerMock->getInt('string'));
        $this->assertNull($this->baseContainerMock->getInt('float'));
    }

    /**
     * Test the $container->getString( $index ) method. This method should return an string when an item in the
     * container can be casted to one. It should fail on items that are not string castable like an closure.
     */
    public function testGetString()
    {
        $this->assertEquals(
            'Hello world',
            $this->baseContainerMock->getString('string')
        );
        $this->assertEquals(
            $this->testData['float'],
            $this->baseContainerMock->getString('float')
        );

        $this->assertNull($this->baseContainerMock->getString('closure'));
    }
}
