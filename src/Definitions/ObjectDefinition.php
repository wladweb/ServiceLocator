<?php

namespace Wladweb\ServiceLocator\Definitions;

use Wladweb\ServiceLocator\Definitions\DefinitionInterface;
use Wladweb\ServiceLocator\Definitions\DataInterface;

/**
 * Description of ObjectDefinition
 * @author wladweb <wladwebwork@gmail.com>
 */
class ObjectDefinition implements DefinitionInterface
{
    private $value;
    private $data;
    
    public function __construct(DataInterface $data, $value)
    {
        $this->value = $value;
        $this->data = $data;
    }
    
    public function create(DataInterface $data)
    {
        if ($data->hasProperties()){
            $properties = $data->getProperties();
        } else {
            $properties = $this->data->getProperties();
        }
        
        foreach ($properties as $key => $val){
            $this->value->{$key} = $val;
        }
        
        if ($data->hasMethods()){
            $methods = $data->getMethods();
        } else {
            $methods = $this->data->getMethods();
        }
       
        foreach ($methods as $method => $args){
            call_user_func_array([$this->value, $method], $args);
        }
        
        return $this->value;
    }
}
