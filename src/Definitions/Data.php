<?php

namespace Wladweb\ServiceLocator\Definitions;

use Wladweb\ServiceLocator\Definitions\DataInterface;

/**
 * Description of Data
 * @author wladweb <wladwebwork@gmail.com>
 */
class Data implements DataInterface
{

    private $constructor = [];
    private $properties = [];
    private $methods = [];

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
        return $this->constructor;
    }

    public function getProperties()
    {
        return $this->properties;
    }

    public function getMethods()
    {
        return $this->methods;
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

}
