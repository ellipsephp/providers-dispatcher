<?php

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Container;
use Ellipse\DispatcherFactoryInterface;
use Ellipse\Container\ReflectionContainer;
use Ellipse\Providers\ExtendedServiceProvider;
use Ellipse\Dispatcher\ExtendedDispatcherServiceProvider;

describe('ExtendedDispatcherServiceProvider', function () {

    beforeEach(function () {

        $this->provider = new ExtendedDispatcherServiceProvider;

    });

    it('should implement ExtendedServiceProvider', function () {

        expect($this->provider)->toBeAnInstanceOf(ExtendedServiceProvider::class);

    });

    context('when consumed by a container', function () {

        beforeEach(function () {

            $this->container = new Container([$this->provider]);

        });

        it('should provide an array containing MiddlewareInterface::class and RequestHandlerInterface::class for the ellipse.dispatcher.autowired alias', function () {

            $test = $this->container->get('ellipse.dispatcher.autowired');

            expect($test)->toContain(MiddlewareInterface::class);
            expect($test)->toContain(RequestHandlerInterface::class);

        });

        it('should provide an instance of ReflectionContainer for the ellipse.dispatcher.container alias', function () {

            $test = $this->container->get('ellipse.dispatcher.container');

            expect($test)->toBeAnInstanceOf(ReflectionContainer::class);

        });

        it('should provide an implementation of DispatcherFactoryInterface for the DispatcherFactoryInterface::class alias', function () {

            $test = $this->container->get(DispatcherFactoryInterface::class);

            expect($test)->toBeAnInstanceOf(DispatcherFactoryInterface::class);

        });

        context('when an extension is given for ellipse.dispatcher.autowired alias', function () {

            it('should return the value returned by the extension', function () {

                $provider = new ExtendedDispatcherServiceProvider([
                    'ellipse.dispatcher.autowired' => function ($container, array $interfaces) {

                        return array_merge($interfaces, ['App\SomeInterface']);

                    },
                ]);

                $container = new Container([$provider]);

                $test = $container->get('ellipse.dispatcher.autowired');

                expect($test)->toContain(MiddlewareInterface::class);
                expect($test)->toContain(RequestHandlerInterface::class);
                expect($test)->toContain('App\SomeInterface');

            });

        });

    });

});
