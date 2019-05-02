<?php

namespace Wladweb\ServiceLocator\Definitions;

use Wladweb\ServiceLocator\Definitions\DefinitionInterface;

/**
 * Description of ValueDefinition
 * @author wladweb <wladwebwork@gmail.com>
 */
class ValueDefinition implements DefinitionInterface
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function create($data = null)
    {
        return $this->value;
    }

}
