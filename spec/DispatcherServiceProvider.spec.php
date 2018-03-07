<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Interop\Container\ServiceProviderInterface;

use Ellipse\Container;
use Ellipse\Container\ReflectionContainer;

use Ellipse\DispatcherFactoryInterface;
use Ellipse\Dispatcher\DefaultResolver;
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

        context('when the DispatcherFactoryInterface::class id is retrieved from the container', function () {

            context('when the autowiring status is set to false', function () {

                it('should return an instance of DefaultResolver using the container', function () {

                    allow($this->container)->toReceive('get')
                        ->with('ellipse.dispatcher.autowiring.status')
                        ->andReturn(false);

                    $test = $this->container->get(DispatcherFactoryInterface::class);

                    $resolver = new DefaultResolver($this->container);

                    expect($test)->toEqual($resolver);

                });

            });

            context('when the autowiring status is set to false', function () {

                it('should return an instance of DefaultResolver using a reflection container', function () {

                    allow($this->container)->toReceive('get')
                        ->with('ellipse.dispatcher.autowiring.status')
                        ->andReturn(true);

                    allow($this->container)->toReceive('get')
                        ->with('ellipse.dispatcher.autowiring.interfaces')
                        ->andReturn(['interface']);

                    $test = $this->container->get(DispatcherFactoryInterface::class);

                    $resolver = new DefaultResolver(
                        new ReflectionContainer($this->container, ['interface'])
                    );

                    expect($test)->toEqual($resolver);

                });

            });

        });

        it('should provide true for the ellipse.dispatcher.autowiring.status id', function () {

            $test = $this->container->get('ellipse.dispatcher.autowiring.status');

            expect($test)->toBeTruthy();

        });

        it('should provide an array containing MiddlewareInterface::class and RequestHandlerInterface::class for the ellipse.dispatcher.autowiring.interfaces id', function () {

            $test = $this->container->get('ellipse.dispatcher.autowiring.interfaces');

            expect($test)->toEqual([MiddlewareInterface::class, RequestHandlerInterface::class]);

        });

    });

});
