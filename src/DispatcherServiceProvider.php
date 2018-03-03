<?php declare(strict_types=1);

namespace Ellipse\Dispatcher;

use Psr\Container\ContainerInterface;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Interop\Container\ServiceProviderInterface;

use Ellipse\Container\ReflectionContainer;

use Ellipse\DispatcherFactoryInterface;

class DispatcherServiceProvider implements ServiceProviderInterface
{
    /**
     * The user defined service extensions.
     *
     * @var array
     */
    private $extensions;

    /**
     * Set up a dispatcher service provider with the given extensions.
     *
     * @param array $extensions
     */
    public function __construct(array $extensions = [])
    {
        $this->extensions = $extensions;
    }

    /**
     * @inheritdoc
     */
    public function getFactories()
    {
        return [
            DispatcherFactoryInterface::class => [$this, 'getDispatcherFactory'],
            'ellipse.dispatcher.autowiring.status' => [$this, 'getAutowiringStatus'],
            'ellipse.dispatcher.autowiring.interfaces' => [$this, 'getAutowiringInterfaces'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * Return an instance of default resolver as DispatcherFactoryInterface.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return \Ellipse\DispatcherFactoryInterface
     */
    public function getDispatcherFactory(ContainerInterface $container): DispatcherFactoryInterface
    {
        $autowired = $container->get('ellipse.dispatcher.autowiring.status');

        if ($autowired) {

            $interfaces = $container->get('ellipse.dispatcher.autowiring.interfaces');

            $container = new ReflectionContainer($container, $interfaces);

        }

        return new DefaultResolver($container);
    }

    /**
     * Return true as autowiring status.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return bool
     */
    public function getAutowiringStatus(ContainerInterface $container): bool
    {
        return true;
    }

    /**
     * Return an array containing MiddlewareInterface::class and
     * RequestHandlerInterface::class as autowirable interfaces.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @return array
     */
    public function getAutowiringInterfaces(ContainerInterface $container): array
    {
        return [
            MiddlewareInterface::class,
            RequestHandlerInterface::class,
        ];
    }
}
