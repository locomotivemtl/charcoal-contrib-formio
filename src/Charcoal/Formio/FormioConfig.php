<?php

namespace Charcoal\Formio;

// from 'charcoal-config'
use Charcoal\Config\AbstractConfig;

/**
 * Formio Contrib Module Config
 */
class FormioConfig extends AbstractConfig
{

    /**
     * @var string $formModel
     */
    private $formObject;

    /**
     * @var string $submissionModel
     */
    private $submissionObject;

    /**
     * The default data is defined in a JSON file.
     *
     * @return array
     */
    public function defaults()
    {
        $baseDir = rtrim(realpath(__DIR__.'/../../../'), '/');
        $confDir = $baseDir.'/config';

        $formioConfig = $this->loadFile($confDir.'/formio.json');

        return $formioConfig;
    }

    /**
     * @return string|null
     */
    public function formObject()
    {
        return $this->formObject;
    }

    /**
     * @param mixed $formObject FormObject for FormioConfig.
     * @return self
     */
    public function setFormObject($formObject)
    {
        $this->formObject = $formObject;

        return $this;
    }

    /**
     * @return string|null
     */
    public function submissionObject()
    {
        return $this->submissionObject;
    }

    /**
     * @param mixed $submissionObject SubmissionObject for FormioConfig.
     * @return self
     */
    public function setSubmissionObject($submissionObject)
    {
        $this->submissionObject = $submissionObject;

        return $this;
    }
}
