<?php

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Interop\Container\ServiceProviderInterface;

use Ellipse\Container;
use Ellipse\Container\ReflectionContainer;

use Ellipse\DispatcherFactory;
use Ellipse\DispatcherFactoryInterface;
use Ellipse\Dispatcher\CallableResolver;
use Ellipse\Dispatcher\ContainerResolver;
use Ellipse\Dispatcher\ControllerResolver;
use Ellipse\Dispatcher\ComposableResolver;
use Ellipse\Dispatcher\DispatcherServiceProvider;

describe('DispatcherServiceProvider', function () {

    beforeEach(function () {

        $this->provider = new DispatcherServiceProvider;

    });

    it('should implement ServiceProviderInterface', function () {

        expect($this->provider)->toBeAnInstanceOf(ServiceProviderInterface::class);

    });

    context('when consumed by a container', function () {

        beforeEach(function () {

            $this->container = new Container([$this->provider]);

        });

        it('should provide true for the ellipse.dispatcher.autowiring.status alias', function () {

            $test = $this->container->get('ellipse.dispatcher.autowiring.status');

            expect($test)->toBeTruthy();

        });

        it('should provide an array containing MiddlewareInterface::class and RequestHandlerInterface::class for the ellipse.dispatcher.autowiring.interfaces alias', function () {

            $test = $this->container->get('ellipse.dispatcher.autowiring.interfaces');

            expect($test)->toEqual([MiddlewareInterface::class, RequestHandlerInterface::class]);

        });

        context('when the autowiring status is set to false', function () {

            it('should provide an instance of ComposableResolver resolving controllers, class names and callables', function () {

                allow($this->container)->toReceive('get')
                    ->with('ellipse.dispatcher.autowiring.status')
                    ->andReturn(false);

                $test = $this->container->get(DispatcherFactoryInterface::class);

                $factory = new ComposableResolver(
                    new ControllerResolver(
                        $this->container,
                        new ContainerResolver(
                            $this->container,
                            new CallableResolver(
                                new DispatcherFactory
                            )
                        )
                    )
                );

                expect($test)->toEqual($factory);

            });

        });

        context('when the autowiring status is set to true', function () {

            it('should provide an instance of ComposableResolver resolving controllers, class names and callables using a reflection container', function () {

                allow($this->container)->toReceive('get')
                    ->with('ellipse.dispatcher.autowiring.status')
                    ->andReturn(true);

                allow($this->container)->toReceive('get')
                    ->with('ellipse.dispatcher.autowiring.interfaces')
                    ->andReturn(['interface']);

                $test = $this->container->get(DispatcherFactoryInterface::class);

                $factory = new ComposableResolver(
                    new ControllerResolver(
                        new ReflectionContainer($this->container, ['interface']),
                        new ContainerResolver(
                            new ReflectionContainer($this->container, ['interface']),
                            new CallableResolver(
                                new DispatcherFactory
                            )
                        )
                    )
                );

                expect($test)->toEqual($factory);

            });

        });

    });

});
