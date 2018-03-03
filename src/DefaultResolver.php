<?php declare(strict_types=1);

namespace Ellipse\Dispatcher;

use Psr\Container\ContainerInterface;

use Ellipse\DispatcherFactory;

class DefaultResolver extends AbstractDecoratedResolver
{
    /**
     * Set up a default resolver allowing to resolve controller, class names and
     * callables, using the given container.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct(new ControllerResolver(
            $container,
            new ContainerResolver(
                $container,
                new CallableResolver(
                    new DispatcherFactory
                )
            )
        ));
    }
}
