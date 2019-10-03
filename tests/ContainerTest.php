<?php

namespace Wladweb\ServiceLocator\Tests;

use PHPUnit\Framework\TestCase;
use Wladweb\ServiceLocator\Container;
use Wladweb\ServiceLocator\Exceptions\{
    ContainerException,
    NotFoundException
};

/**
 * Description of ContainerTest
 * @author wladweb <wladwebwork@gmail.com>
 */
class ContainerTest extends TestCase
{

    private $container;

    public function setUp(): void
    {
        $config = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
        $this->container = Container::getContainer($config);
    }

    public function testThatNotFoundExceptionWorks(): void
    {
        $this->expectException(NotFoundException::class);
        $this->container->get('non_existing_value');
    }

    public function testIfInputArrayHasntValueField(): void
    {
        $this->expectException(ContainerException::class);
        $this->container->set('simple_value', [
            'foo' => 'bar'
        ]);
    }

    public function testHas(): void
    {
        $this->container->set('simple_value', [
            'value' => 404
        ]);

        $this->assertTrue($this->container->has('simple_value'));
        $this->assertFalse($this->container->has('non_existing_value'));
    }

    public function testAlias(): void
    {
        $name = 'long_string_name';
        $alias = 'str';
        $value = 'actual_value';

        $this->container->set($name, [
            'value' => $value,
            'alias' => $alias
        ]);

        $expected = $this->container->get($alias);

        $this->assertEquals($expected, $value);
    }

    public function testSetGet(): void
    {
        $this->container->set('ObjectInstance', [
            'value' => new \Wladweb\ServiceLocator\Lib\Test('argument')
        ]);
        $object = $this->container->get('ObjectInstance');

        $this->assertInstanceOf('\Wladweb\ServiceLocator\Lib\Test', $object);
    }

    public function testSingletonObject(): void
    {
        $this->container->set('SingletonObject', [
            'value' => new \Wladweb\ServiceLocator\Lib\Test('argument'),
            'singleton' => true
        ]);

        $obj1 = $this->container->get('SingletonObject');
        $obj2 = $this->container->get('SingletonObject');

        $obj1->version = '1.0.1';

        $this->assertEquals($obj1->version, $obj2->version);
    }

    public function testSingletonLazy(): void
    {
        $this->container->set('SingletonLazy', [
            'value' => '\Wladweb\ServiceLocator\Lib\Test',
            'singleton' => true,
            'constructor' => ['argument']
        ]);

        $obj1 = $this->container->get('SingletonLazy');
        $obj2 = $this->container->get('SingletonLazy');

        $obj1->version = '1.0.2';

        $this->assertEquals($obj1->version, $obj2->version);
    }

    public function testScalarValues()
    {
        $string = 'some string';
        $integer = 615;
        $boolean = true;
        $float = 1.23;

        $this->container->set('string', [
            'value' => $string
        ]);
        $this->container->set('integer', [
            'value' => $integer
        ]);
        $this->container->set('boolean', [
            'value' => $boolean
        ]);
        $this->container->set('float', [
            'value' => $float
        ]);

        $this->assertEquals($this->container->get('string'), $string);
        $this->assertEquals($this->container->get('integer'), $integer);
        $this->assertEquals($this->container->get('boolean'), $boolean);
        $this->assertEquals($this->container->get('float'), $float);
    }

    public function testObject()
    {
        $this->container->set('SomeObject', [
            'value' => new \Wladweb\ServiceLocator\Lib\Test('argument')
        ]);

        $obj1 = $this->container->get('SomeObject');
        $obj2 = $this->container->get('SomeObject');

        $this->assertTrue($obj1 === $obj2);
    }

    public function testLazy()
    {
        $fqn = '\Wladweb\ServiceLocator\Lib\Test';

        $this->container->set('SomeLazy', [
            'value' => $fqn,
            'alias' => 'lazy',
            'constructor' => ['argument']
        ]);

        $obj1 = $this->container->get('lazy');
        $obj2 = $this->container->get('lazy');

        $this->assertInstanceOf($fqn, $obj1);
        $this->assertInstanceOf($fqn, $obj2);
    }

    public function testPropInjection()
    {
        $version1 = '1.0.0';
        $author1 = 'John';

        $version2 = '1.0.1';
        $author2 = 'Steven';

        $this->container->set('test', [
            'value' => new \Wladweb\ServiceLocator\Lib\Test('argument'),
            'properties' => [
                'version' => $version1,
                'author' => $author1
            ]
        ]);

        $test1 = $this->container->get('test');

        $this->assertEquals($test1->version, $version1);
        $this->assertEquals($test1->author, $author1);

        //redefine
        $test2 = $this->container->get('test', [
            'properties' => [
                'version' => $version2,
                'author' => $author2
            ]
        ]);

        $this->assertEquals($test2->version, $version2);
        $this->assertEquals($test2->author, $author2);
    }

    public function testConstructor()
    {
        $argument1 = 'argument1';
        $argument2 = 'argument2';

        $this->container->set('test', [
            'value' => '\Wladweb\ServiceLocator\Lib\Test',
            'constructor' => [$argument1]
        ]);

        $test = $this->container->get('test');

        $this->assertEquals($test->getComplex(), $argument1);

        //redefine
        $test2 = $this->container->get('test', [
            'constructor' => [$argument2]
        ]);

        $this->assertEquals($test2->getComplex(), $argument2);
    }

    public function testMethods()
    {
        $value = 'value B';

        $this->container->set('test', [
            'value' => new \Wladweb\ServiceLocator\Lib\Test('argument'),
            'methods' => [
                'setB' => [$value]
            ]
        ]);

        $test = $this->container->get('test');

        $this->assertEquals($test->getB(), $value);
    }

    public function testClosure()
    {
        $argument = 'argument';

        $this->container->set('closure', [
            'value' => function($arg) {
                return new \Wladweb\ServiceLocator\Lib\Test($arg);
            },
            'constructor' => [$argument]
        ]);

        $test = $this->container->get('closure');

        $this->assertInstanceOf('\Wladweb\ServiceLocator\Lib\Test', $test);
        $this->assertEquals($test->getComplex(), $argument);
    }

    public function testDependencyThroughMask()
    {
        //@mask1
        $this->container->set('SomeString', [
            'value' => 'some string',
            'alias' => 'Str'
        ]);
        
        //@mask2
        $this->container->set('SomeDep', [
            'value' => new \Wladweb\ServiceLocator\Lib\Dependency('simple arg'),
            'alias' => 'Dep',
            'properties' => [
                'version' => '1.2.0',
                'prop_a' => 'A',
                'prop_b' => 'B',
            ],
            'methods' => [
                'setColor' => ['heaven', '#69c']
            ],
        ]);

        $this->container->set('SomeLazy', [
            'value' => 'Wladweb\ServiceLocator\Lib\Lazy', 
            'alias' => 'Lazy',
            'constructor' => [
                'dep' => '@Dep' //@mask1
            ],
            'properties' => [
                'version' => '2.2.0',
                'prop_a' => 'A',
                'prop_b' => '@Str', //@mask2
            ],
            'methods' => [
                'setC' => ['C'],
                'setDE' => ['D', 'E'],
            ],
        ]);
        
        $lazy = $this->container->get('Lazy');
        
        $str = $this->container->get('Str');
        $dep = $this->container->get('Dep');
        
        $this->assertEquals($lazy->prop_b, $str);
        $this->assertEquals($lazy->getDependency(), $dep);
    }

    public function testFixIssue1(){
        
        $fqn = '\Wladweb\ServiceLocator\Lib\DepForIssueOne';

        $this->container->set('fix', [
            'value' => $fqn,
        ]);
        
        $fix = $this->container->get('fix');
        
        $this->assertInstanceOf($fqn, $fix);
    }
}
