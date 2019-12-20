<?php

namespace Charcoal\Formio\Widget;

use Charcoal\Admin\AdminWidget;
use Charcoal\Admin\Ui\ObjectContainerTrait;
use Charcoal\Config\ConfigInterface;
use Charcoal\Config\ConfigurableTrait;
use Charcoal\Formio\FormioConfig;
use Charcoal\Formio\Template\SubmissionTemplate;
use Charcoal\Model\ModelInterface;
use InvalidArgumentException;
use Pimple\Container;

/**
 * Class SubmissionWidget
 */
class SubmissionWidget extends AdminWidget
{
    use ConfigurableTrait;
    use ObjectContainerTrait;

    /**
     * @var ConfigInterface|FormioConfig $formioConfig
     */
    private $formioConfig;

    /**
     * Settings for the formio form builder.
     *
     * @var array
     */
    private $builderOptions;

    /**
     * @var ModelInterface $formProto
     */
    private $formProto;

    /**
     * @var ModelInterface $submissionProto
     */
    private $submissionProto;

    /**
     * @param Container $container DI container.
     * @return void
     */
    protected function setDependencies(Container $container)
    {
        parent::setDependencies($container);

        // Required ObjectContainerInterface dependencies
        $this->setModelFactory($container['model/factory']);
        $this->setConfig($container['config']);
        $this->formioConfig = $container['formio/config'];
    }

    /**
     * Set the form builder options.
     *
     * This method always merges default settings.
     *
     * @param array $settings The from builder options.
     * @return self
     */
    public function setBuilderOptions(array $settings)
    {
        if ($this->builderOptions) {
            $this->builderOptions = array_replace_recursive($this->builderOptions, $settings);
        } else {
            $this->builderOptions = array_replace_recursive($this->defaultBuilderOptions(), $settings);
        }

        return $this;
    }

    /**
     * Add (or replace) a builder option.
     *
     * @param string $key The setting to add/replace.
     * @param mixed  $val The setting's value to apply.
     * @return self
     * @throws InvalidArgumentException If the identifier is not a string.
     */
    public function addBuilderOption($key, $val)
    {
        if (!is_string($key)) {
            throw new InvalidArgumentException(
                'Setting key must be a string.'
            );
        }

        // Make sure default options are loaded.
        if ($this->builderOptions === null) {
            $this->builderOptions();
        }

        $this->builderOptions[$key] = $val;

        return $this;
    }

    /**
     * Retrieve the builder options.
     *
     * @return array
     */
    public function builderOptions()
    {
        if ($this->builderOptions === null) {
            $this->builderOptions = $this->defaultBuilderOptions();
        }

        return $this->builderOptions;
    }

    /**
     * Merge (replacing or adding) builder options.
     *
     * @param array $settings The builder options.
     * @return self
     */
    public function mergeBuilderOptions(array $settings)
    {
        $this->builderOptions = array_merge($this->builderOptions, $settings);

        return $this;
    }

    /**
     * Retrieve the default builder options.
     *
     * @return array
     */
    public function defaultBuilderOptions()
    {
        return [
            'readOnly'   => true,
            'viewAsHtml' => true
        ];
    }

    /**
     * @return ModelInterface|mixed
     */
    protected function formProto()
    {
        if (isset($this->formProto)) {
            return $this->formProto;
        }

        $this->formProto = $this->modelFactory()->create($this->formioConfig->formObject());

        return $this->formProto;
    }

    /**
     * @return ModelInterface|mixed
     */
    protected function submissionProto()
    {
        if (isset($this->submissionProto)) {
            return $this->submissionProto;
        }

        $this->submissionProto = $this->modelFactory()->create(
            $this->objType() ?: $this->formioConfig->submissionObject()
        );

        return $this->submissionProto;
    }

    /**
     * Retrieve the map widget's options as a JSON string.
     *
     * @return string Returns data serialized with {@see json_encode()}.
     */
    public function builderOptionsAsJson()
    {
        return json_encode($this->builderOptions());
    }

    /**
     * @return \Charcoal\Source\StorableInterface
     */
    private function submissionModel()
    {
        if (isset($this->submissionModel)) {
            return $this->submissionModel;
        }

        $submissionModel       = $this->submissionProto()->load($this->objId());
        $this->submissionModel = $submissionModel;

        return $this->submissionModel;
    }

    /**
     * @return string
     */
    private function formSchema()
    {
        $formId = $this->submissionModel()['form'];
        $form   = $this->formProto()->load($formId);

        if ($form->id()) {
            return $form['schema'];
        }

        return '';
    }

    /**
     * @return string
     */
    private function submission()
    {
        return $this->submissionModel()->submissionData();
    }

    /**
     * Retrieve the widget's data options for JavaScript components.
     *
     * @return array
     */
    public function widgetDataForJs()
    {
        $data = [
            'widget_type'     => $this->type(),
            'builder_options' => $this->builderOptions(),
            'schema'          => json_decode($this->formSchema(), true),
            'submission'      => json_decode($this->submission(), true),
            'submission_id'   => $this->submissionModel()->id()
        ];

        return $data;
    }
}
