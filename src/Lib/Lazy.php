<?php

namespace Wladweb\ServiceLocator\Lib;

/**
 * Description of Lazy
 * @author wladweb <wladwebwork@gmail.com>
 */
class Lazy
{
    public $version;
    public $prop_a;
    public $prop_b;
    private $prop_c;
    private $prop_d;
    private $prop_e;
    private $dependency; // Wladweb\ServiceLocator\Lib\Dependency
    
    public function __construct($dep)
    {
        $this->dependency = $dep;
    }
    
    public function setC($c)
    {
        $this->prop_c = $c;
    }
    
    public function setDE($d, $e)
    {
        $this->prop_d = $d;
        $this->prop_e = $e;
    }
}
