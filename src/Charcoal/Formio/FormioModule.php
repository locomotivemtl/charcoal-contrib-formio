<?php

namespace Charcoal\Formio;

use Charcoal\App\Module\AbstractModule;

/**
 * Formio Module
 */
class FormioModule extends AbstractModule
{
    const APP_CONFIG = 'vendor/locomotivemtl/charcoal-contrib-formio/config/config.json';

    /**
     * Setup the module's dependencies.
     *
     * @return AbstractModule
     */
    public function setup()
    {
        $container = $this->app()->getContainer();

        return $this;
    }
}
