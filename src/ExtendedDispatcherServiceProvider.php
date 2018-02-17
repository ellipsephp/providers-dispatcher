<?php declare(strict_types=1);

namespace Ellipse\Dispatcher;

use Ellipse\Providers\ExtendedServiceProvider;

class ExtendedDispatcherServiceProvider extends ExtendedServiceProvider
{
    public function __construct(array $extensions = [])
    {
        parent::__construct(new DispatcherServiceProvider, $extensions);
    }
}
