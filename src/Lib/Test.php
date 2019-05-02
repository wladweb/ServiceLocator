<?php

namespace Wladweb\ServiceLocator\Lib;

/**
 * Description of Test
 * @author wladweb <wladwebwork@gmail.com>
 */
class Test
{
    public $version;
    public $prop_a;
    private $prop_b;
    
    public function __construct($complex_arg)
    {
        //
    }
    
    public function setB($b)
    {
        $this->prop_b = $b;
    }
}
