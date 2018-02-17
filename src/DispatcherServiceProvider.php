<?php declare(strict_types=1);

namespace Ellipse\Dispatcher;

use Psr\Container\ContainerInterface;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Interop\Container\ServiceProviderInterface;

use Ellipse\Container\ReflectionContainer;

use Ellipse\DispatcherFactory;
use Ellipse\DispatcherFactoryInterface;
use Ellipse\Dispatcher\CallableResolver;
use Ellipse\Dispatcher\ContainerResolver;
use Ellipse\Dispatcher\ControllerResolver;
use Ellipse\Dispatcher\ComposableResolver;

class DispatcherServiceProvider implements ServiceProviderInterface
{
    public function getFactories()
    {
        return [
            'ellipse.dispatcher.autowired' => [$this, 'getAutowiredInterfaces'],
            'ellipse.dispatcher.container' => [$this, 'getReflectionContainer'],
            DispatcherFactoryInterface::class => [$this, 'getDispatcherFactory'],
        ];
    }

    public function getExtensions()
    {
        return [];
    }

    /**
     * Return the default array of interface names classes must implements to be
     * autowired.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return array
     */
    public function getAutowiredInterfaces(ContainerInterface $container): array
    {
        return [
            MiddlewareInterface::class,
            RequestHandlerInterface::class,
        ];
    }

    /**
     * Return the reflection container used by the dispatcher factory.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \Ellipse\Container\ReflectionContainer
     */
    public function getReflectionContainer(ContainerInterface $container): ReflectionContainer
    {
        $interfaces = $container->get('ellipse.dispatcher.autowired');

        return new ReflectionContainer($container, $interfaces);
    }

    /**
     * Return an ellipse dispatcher factory.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \Ellipse\DispatcherFactoryInterface
     */
    public function getDispatcherFactory(ContainerInterface $container): DispatcherFactoryInterface
    {
        $reflection = $container->get('ellipse.dispatcher.container');

        return new ComposableResolver(
            new ControllerResolver(
                $reflection,
                new ControllerResolver(
                    $reflection,
                    new CallableResolver(
                        new DispatcherFactory
                    )
                )
            )
        );
    }
}
