<?php

namespace Charcoal\Formio;

// from pimple
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Formio Service Provider
 */
class FormioServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container Pimple DI container.
     * @return void
     */
    public function register(Container $container)
    {
        /**
         * @return FormioConfig
         */
        $container['formio/config'] = function () {
            return new FormioConfig();
        };
    }
}
