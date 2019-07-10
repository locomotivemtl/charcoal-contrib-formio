<?php

namespace Charcoal\Formio;

// from pimple
use Charcoal\Formio\Service\FormioService;
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

        /**
         * @param Container $container Pimple DI container.
         * @return FormioService
         */
        $container['formio'] = function (Container $container) {
            return new FormioService([
                'model/factory' => $container['model/factory'],
                'config' => $container['formio/config']
            ]);
        };
    }
}
