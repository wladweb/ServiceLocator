<?php

namespace Wladweb\ServiceLocator\Definitions;

use Wladweb\ServiceLocator\Definitions\DefinitionInterface;
use Wladweb\ServiceLocator\Definitions\ContainerRequireInterface;
use Psr\Container\ContainerInterface;

/**
 * Description of AliasDefenition
 * @author wladweb <wladwebwork@gmail.com>
 */
class AliasDefenition implements DefinitionInterface, ContainerRequireInterface
{
    private $value;
    private $container;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
    
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function create($data = null)
    {
        return $this->container->get($this->value);
    }
}
