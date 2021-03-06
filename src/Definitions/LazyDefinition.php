<?php

namespace Wladweb\ServiceLocator\Definitions;

use Wladweb\ServiceLocator\Definitions\DefinitionInterface;
use Wladweb\ServiceLocator\Definitions\ContainerRequireInterface;
use Wladweb\ServiceLocator\Definitions\DataInterface;
use Wladweb\ServiceLocator\Exceptions\ContainerException;
use Psr\Container\ContainerInterface;

/**
 * Description of LazyDefinition
 * @author wladweb <wladwebwork@gmail.com>
 */
class LazyDefinition implements DefinitionInterface, ContainerRequireInterface
{

    private $value;
    private $data;
    private $container;

    public function __construct(DataInterface $data, \ReflectionClass $value)
    {
        $this->value = $value;
        $this->data = $data;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create(DataInterface $data)
    {
        $reflection_constructor = $this->value->getConstructor();

        if (is_null($reflection_constructor)) {
            $args = [];
        } elseif ($data->hasConstructor()) {
            $args = $data->getConstructor();
        } elseif ($this->data->hasConstructor()) {
            $args = $this->data->getConstructor();
        } elseif (!empty($reflection_constructor->getParameters())) {

            foreach ($reflection_constructor->getParameters() as $arg) {
                if ($arg->isDefaultValueAvailable()) {
                    $args[] = $arg->getDefaultValue();
                } else {
                    throw new ContainerException('Cant resolve parameter ' . $arg->getName(), 1003);
                }
            }
        } else {
            $args = [];
        }

        $object = $this->value->newInstanceArgs($args);

        if ($data->hasProperties()) {
            $properties = $data->getProperties();
        } else {
            $properties = $this->data->getProperties();
        }

        foreach ($properties as $key => $val) {
            $object->{$key} = $val;
        }

        if ($data->hasMethods()) {
            $methods = $data->getMethods();
        } else {
            $methods = $this->data->getMethods();
        }

        foreach ($methods as $method => $args) {
            call_user_func_array([$object, $method], $args);
        }

        return $object;
    }

}
