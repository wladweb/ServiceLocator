<?php

namespace Wladweb\ServiceLocator\Definitions;

use Wladweb\ServiceLocator\Definitions\DefinitionInterface;
use Wladweb\ServiceLocator\Definitions\DataInterface;

/**
 * Description of ClosureDefinition
 * @author wladweb <wladwebwork@gmail.com>
 */
class ClosureDefinition implements DefinitionInterface
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
        if ($data->hasConstructor()){
            $constructor = $data->getConstructor();
        } else {
            $constructor = $this->data->getConstructor();
        }
        
        return \call_user_func_array($this->value, $constructor);
    }
}
