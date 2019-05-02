<?php

namespace Wladweb\ServiceLocator\Definitions;

use Wladweb\ServiceLocator\Definitions\DefinitionInterface;
use Wladweb\ServiceLocator\Definitions\ContainerRequireInterface;
use Wladweb\ServiceLocator\Definitions\DataInterface;
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
        } elseif ($data->hasConstructor()){
            $args = $data->getConstructor();
        } elseif ($this->data->hasConstructor()){
            $args = $this->data->getConstructor();
        } else {
            foreach ($reflection_constructor as $arg){
                $args[] = $arg->getDefaultValue();
            }
        }
        
        $object = $this->value->newInstanceArgs($args);
        
        if ($data->hasProperties()){
            $properties = $data->getProperties();
        } else {
            $properties = $this->value->getProperties();
        }
        
        foreach ($properties as $key => $val){
            $object->{$key} = $val;
        }
        
        if ($data->hasMethods()){
            $methods = $data->getMethods();
        } else {
            $methods = $this->value->getMethods();
        }
       
        foreach ($methods as $method => $args){
            call_user_func_array([$object, $method], $args);
        }
        
        return $object;
    }
}
