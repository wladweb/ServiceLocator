
## Service Locator

Simple service locator.

### Usage

```php

$file = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';

try {
    $container = Wladweb\ServiceLocator\Container::getContainer($file);
} catch (Wladweb\ServiceLocator\Exceptions\ContainerException $e) {
    echo $e->getCode(), '<br>', $e->getMessage(), '<br>';
}

//var_dump($container);

/**
 * Get target if it was set in config file
 */
$target = $container->get('SomeLazy');
//OR the same by alias
$target = $container->get('Lazy');
//var_dump($target); exit;


/**
 * Also there is ability to set target in runtime
 */
$container->set('AnotherSomeLazy', [
    'value' => 'Wladweb\ServiceLocator\Lib\Lazy',   // value, must have or you will get ContainerException with code 1004
    'alias' => 'AnotherLazy',                       // alias, if you want
    'singleton' => true,                            // singletone, if you need
    'constructor' => [                              // constructor args
        'dep' => '@Dep'                             // '@mask' - dependency registered in container before
    ],
    'properties' => [                               //public props
        'version' => '1.0.0',
        'prop_a' => 'A',
        'prop_b' => '@Str',                         //@mask
    ],
    'methods' => [                                  //public methods with args
        'setC' => ['C'],
        'setDE' => ['D', 'E'],
    ]
]);
//and then get it by name
$target1 = $container->get('AnotherSomeLazy');
//or by alias if you set it
$target1 = $container->get('AnotherLazy');
//var_dump($target1); exit;


/**
 * Or you can redefine existing target when you get it
 */
$target2 = $container->get('SomeLazy', [
    'constructor' => [                              // constructor args
        'dep' => '@Dep'                             
    ],
    'properties' => [                               // public props
        'version' => '1.1.0',
        'prop_a' => 'Another A',
        'prop_b' => 'Another B',
    ],
    'methods' => [                                  // public methods with args
        'setC' => ['Another C'],
        'setDE' => ['Another D', 'Another E'],
    ]
]);
//var_dump($target2); exit;

```