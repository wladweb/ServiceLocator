<?php

namespace Wladweb\ServiceLocator\Definitions;

use Psr\Container\ContainerInterface;

/**
 *
 * @author wladweb <wladwebwork@gmail.com>
 */
interface ContainerRequireInterface
{
    public function setContainer(ContainerInterface $container);
}
