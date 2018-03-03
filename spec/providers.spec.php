<?php

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\Container;
use Ellipse\DispatcherFactoryInterface;

describe('providers.php', function () {

    beforeEach(function () {

        $this->providers = require __DIR__ . '/../providers.php';

    });

    context('when consumed by a container', function () {

        beforeEach(function () {

            $this->container = new Container($this->providers);

        });

        it('should provide true for the ellipse.dispatcher.autowiring.status alias', function () {

            $test = $this->container->get('ellipse.dispatcher.autowiring.status');

            expect($test)->toBeTruthy();

        });

        it('should provide an array containing MiddlewareInterface::class and RequestHandlerInterface::class for the ellipse.dispatcher.autowiring.interfaces alias', function () {

            $test = $this->container->get('ellipse.dispatcher.autowiring.interfaces');

            expect($test)->toContain(MiddlewareInterface::class);
            expect($test)->toContain(RequestHandlerInterface::class);

        });

        it('should provide an implementation of DispatcherFactoryInterface for the DispatcherFactoryInterface::class alias', function () {

            $test = $this->container->get(DispatcherFactoryInterface::class);

            expect($test)->toBeAnInstanceOf(DispatcherFactoryInterface::class);

        });

    });

});
