<?php

namespace Wladweb\ServiceLocator\Definitions;

/**
 *
 * @author wladweb <wladwebwork@gmail.com>
 */
interface DataInterface
{
    public function setConstructor(array $constructor);
    public function setProperties(array $properties);
    public function setMethods(array $methods);
    
    public function getConstructor();
    public function getProperties();
    public function getMethods();
    
    public function hasConstructor();
    public function hasProperties();
    public function hasMethods();
}
