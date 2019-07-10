<?php

namespace Charcoal\Formio\Service;

// From 'charcoal-config'
use Charcoal\Config\ConfigInterface;
// From 'charcoal-formio'.
use Charcoal\Formio\FormioConfig;
// From 'charcoal-core'
use Charcoal\Model\ModelFactoryTrait;
use Charcoal\Model\ModelInterface;

use RuntimeException;

/**
 * Provides tools to handle formio/charcoal relations.
 *
 * Formio Service
 */
class FormioService
{
    use ModelFactoryTrait;

    /**
     * @var ConfigInterface|FormioConfig $config
     */
    protected $config;

    /**
     * FormioService constructor.
     *
     * @param array $data Initial dependencies.
     * @return void
     */
    public function __construct(array $data = [])
    {
        $this->setModelFactory($data['model/factory']);
        $this->setConfig($data['config']);
    }

    /**
     * @param array  $data   The submission data array.
     * @param string $formId The related form id.
     * @return boolean|string Return a submission id if successful and `false` if it's not.
     */
    public function saveSubmission(array $data, $formId)
    {
        // validate parameters
        if (!$formId || empty($data)) {
            return false;
        }

        // Validate form.
        /** @var ModelInterface $formObject */
        $formObject = $this->modelFactory()->create($this->config()->formObject())->load($formId);
        if (!$formObject->id() || $formObject->id() !== $formId) {
            return false;
        }

        /** @var ModelInterface $submission */
        $submission = $this->modelFactory()
                           ->create($this->config()->submissionObject())
                           ->setForm($formId)
                           ->setSubmissionData($data);
        $submission->save();

        return $submission->id();
    }

    // Dependencies
    // ==========================================================================

    /**
     * @param string $key Config key identifier.
     * @return ConfigInterface|FormioConfig
     * @throws RuntimeException When config is missing.
     */
    public function config($key = null)
    {
        if (!isset($this->config)) {
            throw new RuntimeException(sprintf(
                'Config is not set for [%s]',
                get_class($this)
            ));
        }

        if ($key && is_string($key)) {
            $this->config->get($key);
        }

        return $this->config;
    }

    /**
     * @param ConfigInterface|FormioConfig $config Config for FormioService.
     * @return self
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }
}
