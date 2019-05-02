<?php

namespace Wladweb\ServiceLocator;

use Psr\Container\ContainerInterface as PsrContainer;
use Wladweb\ServiceLocator\Exceptions\NotFoundException;
use Wladweb\ServiceLocator\Exceptions\ContainerException;
use Wladweb\ServiceLocator\Definitions\Data;
use Wladweb\ServiceLocator\Definitions\ObjectDefinition;
use Wladweb\ServiceLocator\Definitions\AliasDefenition;
use Wladweb\ServiceLocator\Definitions\ValueDefinition;
use Wladweb\ServiceLocator\Definitions\ClosureDefinition;
use Wladweb\ServiceLocator\Definitions\LazyDefinition;

/**
 * Container
 * @author wladweb <wladwebwork@gmail.com>
 */
class Container implements PsrContainer
{

    private static $instance;
    private $services = [];
    private $singletons = [];
    private $store = [];
    private $instantiated = [];

    public static function getContainer($path = '')
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self($path);
        }
        return self::$instance;
    }

    public function get($name)
    {
//
    }

    public function has($name)
    {
        return array_key_exists($name, $this->services);
    }

    private function loadConfig($path)
    {
        if ($path && !\file_exists($path)) {
            throw new ContainerException('Config file not found by path ' . $path, 1000);
        }

        if ($path) {
            $config = include_once $path;

            if (!\is_array($config)) {
                throw new ContainerException('Config file must return an array', 1001);
            }

            $this->setServices($config);
        }
    }

    public function set($name, $service)
    {
        $data = $this->setUpData($service);
        $value = $service['value'];

        if (\array_key_exists('singleton', $service) && $service['singleton'] === true) {
            $this->singletons[$name] = true;
        }

        if (\array_key_exists('alias', $service)) {
            $this->setAlias($name, $service['alias']);
        }

        if ($value instanceof \Closure) {

            $this->services[$name] = new ClosureDefinition($data, $value);
        } elseif (\is_object($value)) {

            $this->services[$name] = new ObjectDefinition($data, $value);
        } elseif (\is_string($value) && \class_exists($value)) {

            $reflection_class = new \ReflectionClass($value);

            if (!$reflection_class->isInstantiable()) {
                throw new ContainerException('Cant create instance of ' . $reflection_class->getName());
            }

            $this->services[$name] = new LazyDefinition($data, $reflection_class);
        } else {
            $this->services[$name] = new ValueDefinition($value);
        }
    }

    private function setUpData($service)
    {
        $data = new Data;
        $data->setContainer($this);

        if (\array_key_exists('constructor', $service)) {
            $data->setConstructor($service['constructor']);
        }

        if (\array_key_exists('properties', $service)) {
            $data->setProperties($service['properties']);
        }

        if (\array_key_exists('methods', $service)) {
            $data->setMethods($service['methods']);
        }

        return $data;
    }

    private function setAlias($name, $alias)
    {
        if ($alias && is_string($alias)) {
            $definition = new AliasDefenition($name);
            $definition->setContainer($this);
            $this->services[$alias] = $definition;
        }
    }

    private function setServices($config)
    {
        foreach ($config as $name => $service) {
            $this->set($name, $service);
        }
    }

    private function __construct($path)
    {
        $this->loadConfig($path);
    }

    private function __clone()
    {
        
    }

    private function __wakeup()
    {
        
    }

}
