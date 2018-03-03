<?php declare(strict_types=1);

namespace Ellipse\Dispatcher;

use Ellipse\Dispatcher;
use Ellipse\DispatcherFactoryInterface;

abstract class AbstractDecoratedResolver implements DispatcherFactoryInterface
{
    /**
     * Set up an abstract decorated resolver with the given delegate.
     *
     * @param \Ellipse\DispatcherFactoryInterface $delegate
     */
    public function __construct(DispatcherFactoryInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * @inheritdoc
     */
    public function __invoke($handler, iterable $middleware = []): Dispatcher
    {
        return ($this->delegate)($handler, $middleware);
    }
}
