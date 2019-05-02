<?php

namespace Wladweb\ServiceLocator\Definitions;

use Wladweb\ServiceLocator\Definitions\DataInterface;
use Wladweb\ServiceLocator\Definitions\ContainerRequireInterface;
use Psr\Container\ContainerInterface;
use Wladweb\ServiceLocator\Exceptions\NotFoundException;
use Wladweb\ServiceLocator\Exceptions\ContainerException;

/**
 * Description of Data
 * @author wladweb <wladwebwork@gmail.com>
 */
class Data implements DataInterface, ContainerRequireInterface
{

    private $constructor = [];
    private $properties = [];
    private $methods = [];
    private $container;
    
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setConstructor(array $constructor)
    {
        $this->constructor = $constructor;
    }

    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }

    public function setMethods(array $methods)
    {
        $this->methods = $methods;
    }

    public function getConstructor()
    {
        return $this->resolveDependencies($this->constructor);
    }

    public function getProperties()
    {
        return $this->resolveDependencies($this->properties);
    }

    public function getMethods()
    {
        return $this->resolveMethodArgs($this->methods);
    }
    
    public function hasConstructor()
    {
        return !empty($this->constructor);
    }
    
    public function hasProperties()
    {
        return !empty($this->properties);
    }
    
    public function hasMethods()
    {
        return !empty($this->methods);
    }
    
    private function resolveDependencies(array $input)
    {
        foreach ($input as $key => $value){
            
            if (is_string($value) && substr($value, 0, 1) === '@'){
                try{
                    $dependency = $this->container->get(substr($value, 1));
                } catch (NotFoundException $e){
                    throw new ContainerException('Cant resolve dependency ' . $key, 1002, $e);
                }
                
                $input[$key] = $dependency;
            }
        }
        
        return $input;
    }
    
    private function resolveMethodArgs(array $input)
    {
        $output = [];
        
        foreach ($input as $method => $args){
            $output[$method] = $this->resolveDependencies($args);
        }
        
        return $output;
    }
}
