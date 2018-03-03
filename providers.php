<?php declare(strict_types=1);

use Ellipse\Dispatcher\DispatcherServiceProvider;

return [
    new DispatcherServiceProvider([

        /**
         * Return wether the dispatcher factory can autoload classes. Default to
         * true.
         */
        'ellipse.dispatcher.autowiring.status' => function ($container, bool $status): bool {

            return $status;

        },

        /**
         * Return an array of interface names allowing autowiring for classes
         * implementing one of them. Default to Psr-15 MiddlewareInterface and
         * RequestHandlerInterface.
         */
        'ellipse.dispatcher.autowiring.interfaces' => function ($container, array $interfaces): array {

            return $interfaces;

        },

    ]),
];
