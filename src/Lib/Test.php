<?php

namespace Wladweb\ServiceLocator\Lib;

/**
 * Description of Test
 * @author wladweb <wladwebwork@gmail.com>
 */
class Test
{
    public $version;
    public $author;
    private $complex_arg;
    private $prop_b;
    
    public function __construct($complex_arg)
    {
        $this->complex_arg = $complex_arg;
    }
    
    public function setB($b)
    {
        $this->prop_b = $b;
    }
    
    public function getB()
    {
        return $this->prop_b;
    }
    
    public function getComplex()
    {
        return $this->complex_arg;
    }
}
