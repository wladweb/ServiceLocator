<?php

return [
    'SomeDep' => [
        'value' => new Wladweb\ServiceLocator\Lib\Dependency('simple arg'),
        'alias' => 'Dep',
        'properties' => [
            'version' => '1.2.0',
            'prop_a' => 'A',
            'prop_b' => 'B',
        ],
        'methods' => [
            'setColor' => ['heaven', '#69c']
        ],
    ],
    
    'SomeTest' => [
        'value' => function($arg = 'arg') {

            $complex_arg = 'complex ' . (string) $arg;

            return new Wladweb\ServiceLocator\Lib\Test($complex_arg);
        },
        'alias' => 'Closure',
        'constructor' => ['newArg']
    ],
                
    'SomeValue' => [
        'value' => 'some string',
        'alias' => 'Str',
    ],
                
    'IntValue' => [
        'value' => 12,
        'alias' => 'Int',
    ],
                
    'SomeLazy' => [
        'value' => 'Wladweb\ServiceLocator\Lib\Lazy', //FQN
        'alias' => 'Lazy',
        'singleton' => true,
        'constructor' => [
            'dep' => '@Dep' // '@' - alias
        ],
        'properties' => [
            'version' => '2.2.0',
            'prop_a' => 'A',
            'prop_b' => '@Str',
        ],
        'methods' => [
            'setC' => ['C'],
            'setDE' => ['D', 'E'],
        ],
    ],
];

