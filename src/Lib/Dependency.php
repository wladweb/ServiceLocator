<?php

namespace Wladweb\ServiceLocator\Lib;

/**
 * Description of Dependency
 * @author wladweb <wladwebwork@gmail.com>
 */
class Dependency
{
    public $version;
    public $prop_a;
    public $prop_b;
    private $colors = [];
    
    public function __construct($arg)
    {
        //
    }
    
    public function setColor($name, $hex)
    {
        $this->colors[$name] = $hex;
    }
}
