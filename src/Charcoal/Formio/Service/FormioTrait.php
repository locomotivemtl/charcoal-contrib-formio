<?php

namespace Charcoal\Formio\Service;

use RuntimeException;

/**
 * Trait FormioTrait
 * @package Charcoal\Formio\Service
 */
trait FormioTrait
{
    /**
     * @var FormioService $formio The Formio Service.
     */
    private $formio;

    /**
     * @return FormioService
     * @throws RuntimeException If the formio is missing.
     */
    public function formio()
    {
        if (!isset($this->formio)) {
            throw new RuntimeException(sprintf(
                'Formio is not defined for [%s]',
                get_class($this)
            ));
        }

        return $this->formio;
    }

    /**
     * @param FormioService $formio Formio for FormioTrait.
     * @return self
     */
    public function setFormio(FormioService $formio)
    {
        $this->formio = $formio;

        return $this;
    }
}
