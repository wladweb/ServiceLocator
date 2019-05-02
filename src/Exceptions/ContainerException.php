<?php

namespace Wladweb\ServiceLocator\Exceptions;

use Psr\Container\ContainerExceptionInterface;

/**
 * Description of ContainerException
 * @author wladweb <wladwebwork@gmail.com>
 */
class ContainerException extends \Exception implements ContainerExceptionInterface
{
    /**
     * 'Config file not found by path',                 1000
     * 'Config file must return an array',              1001
     * 'Cant resolve dependency',                       1002
     */
}
