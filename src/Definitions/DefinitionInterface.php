<?php

namespace Wladweb\ServiceLocator\Definitions;

/**
 *
 * @author wladweb <wladwebwork@gmail.com>
 */
interface DefinitionInterface
{
    public function create(DataInterface $data);
}
