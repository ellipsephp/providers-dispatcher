<?php declare(strict_types=1);

use Ellipse\Dispatcher\ExtendedDispatcherServiceProvider;

return [
    new ExtendedDispatcherServiceProvider([

        /**
         * Return the array of interface names classes must implements to be
         * autowired. Default to Psr-15 MiddlewareInterface and
         * RequestHandlerInterface.
         */
        'ellipse.dispatcher.autowired' => function ($container, array $interfaces): array {

            return $interfaces;

        },

    ]),
];
