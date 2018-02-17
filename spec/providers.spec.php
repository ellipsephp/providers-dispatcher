<?php

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Container;
use Ellipse\DispatcherFactoryInterface;
use Ellipse\Container\ReflectionContainer;
use Ellipse\Dispatcher\DispatcherServiceProvider;

describe('providers.php', function () {

    beforeEach(function () {

        $this->providers = require __DIR__ . '/../providers.php';

    });

    context('when consumed by a container', function () {

        beforeEach(function () {

            $this->container = new Container($this->providers);

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
    });

});
