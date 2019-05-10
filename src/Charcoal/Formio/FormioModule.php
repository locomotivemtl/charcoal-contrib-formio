<?php

namespace Charcoal\Formio;

// from charcoal-app
use Charcoal\App\Module\AbstractModule;
use Charcoal\Translator\TranslatorAwareTrait;

/**
 * Formio Module
 */
class FormioModule extends AbstractModule
{
    const ADMIN_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-formio/config/admin.json';
    const APP_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-formio/config/config.json';

    /**
     * Setup the module's dependencies.
     *
     * @return AbstractModule
     */
    public function setup()
    {
        $container = $this->app()->getContainer();

        $formioServiceProvider = new FormioServiceProvider();
        $container->register($formioServiceProvider);

        $formioConfig = $container['formio/config'];
        $this->setConfig($formioConfig);

        $container['translator/config']->addPaths(['vendor/locomotivemtl/charcoal-contrib-formio/translations']);

        return $this;
    }
}
