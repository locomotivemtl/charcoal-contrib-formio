<?php

namespace Charcoal\Admin\Property\Input\Formio;

// from charcoal-property
use Charcoal\Admin\Property\AbstractPropertyInput;

// from charcoal-core
use Charcoal\Admin\Ui\FeedbackContainerTrait;
use Charcoal\Config\ConfigInterface;
use Charcoal\Config\ConfigurableTrait;
use Charcoal\Formio\FormioConfig;
use Charcoal\Model\Model;
use Charcoal\Model\ModelFactoryTrait;

// from Psr
use Charcoal\Model\ModelInterface;
use InvalidArgumentException;

// from pimple
use Pimple\Container;

/**
 * Form Builder Input
 *
 * Input to build form.io {@link http://formio.github.io/formio.js/#} forms.
 *
 * SDK documentation for FormBuilder module.
 *  {@link http://formio.github.io/formio.js/docs/class/src/FormBuilder.js~FormBuilder.html}
 *
 * Example of FormBuilder module.
 *  {@link http://formio.github.io/formio.js/app/examples/custombuilder.html}
 */
class FormInput extends AbstractPropertyInput
{
    use ModelFactoryTrait;
    use ConfigurableTrait;
    use FeedbackContainerTrait;

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
     * Inject dependencies from a DI Container.
     *
     * @param  Container $container A dependencies container instance.
     * @return void
     */
    protected function setDependencies(Container $container)
    {
        parent::setDependencies($container);

        $this->setModelFactory($container['model/factory']);
        $this->setConfig($container['config']);
        $this->formioConfig = $container['formio/config'];
    }

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * @param array|\ArrayAccess $data Constructor data.
     * @return void
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);

        $this->createObjTable($this->formProto());
        $this->createObjTable($this->submissionProto());
    }

    /**
     * Set the form builder options.
     *
     * This method always merges default settings.
     *
     * @param  array $settings The from builder options.
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
     * Merge (replacing or adding) builder options.
     *
     * @param  array $settings The builder options.
     * @return self
     */
    public function mergeBuilderOptions(array $settings)
    {
        $this->builderOptions = array_merge($this->builderOptions, $settings);

        return $this;
    }

    /**
     * Add (or replace) a builder option.
     *
     * @param  string $key The setting to add/replace.
     * @param  mixed  $val The setting's value to apply.
     * @throws InvalidArgumentException If the identifier is not a string.
     * @return self
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
     * Retrieve the default builder options.
     *
     * @return array
     */
    public function defaultBuilderOptions()
    {
        return [
            'basic'         => [
                'default' => false,
            ],
            'advanced'      => [
                'components' => [
                    'recaptcha' => false,
                    'form'      => false,
                    'location'  => false,
                    'modaledit' => false,
                    'card' => [
                        'title' => 'Credit card',
                        'key' => 'card',
                        'icon' => 'fa fa-usd',
                        'schema' => [
                            'label' => 'Carte de crédit',
                            'type' => 'stripe',
                            'key' => 'card',
                            'stripe' => [
                                'apiKey' => '',
                                'cardData' => [
                                    'name' => ' '
                                ],
                                'payButton' => [
                                    'enable' => true,
                                    'paymentRequest' => [
                                        'country' => 'CA',
                                        'currency' => 'cad',
                                        'total' => [
                                            'label' => 'Licence',
                                            'amount' => 3000,
                                        ]
                                    ]
                                ],
                                'successLabel' => 'Paiement réussi',
                                'stripeElementOptions' => [
                                    'hidePostalCode' => true
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @uses   AbstractProperty::inputVal() Must handle string sanitization of value.
     * @throws \UnexpectedValueException If the value is invalid.
     * @return string
     */
    public function inputVal()
    {
        $val = parent::inputVal();

        if (isset($val) && $val !== null) {
            $formModel = $this->formProto()->load($val);

            if ($formModel->id() !== null) {
                return $formModel['schema'];
            }
        }

        return $val;
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

        $this->submissionProto = $this->modelFactory()->create($this->formioConfig->submissionObject());

        return $this->submissionProto;
    }

    /**
     * @param ModelInterface $proto Prototype to ensure table creation for.
     * @return void
     */
    private function createObjTable(ModelInterface $proto)
    {
        $obj = $proto;
        if (!$obj) {
            return;
        }

        if ($obj->source()->tableExists() === false) {
            $obj->source()->createTable();
            $msg = $this->translator()->translate('Database table created for "{{ objType }}".', [
                '{{ objType }}' => $obj->objType()
            ]);
            $this->addFeedback(
                'notice',
                '<span class="fa fa-asterisk" aria-hidden="true"></span><span>&nbsp; ' . $msg . '</span>'
            );
        }
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
     * Retrieve the control's data options for JavaScript components.
     *
     * @return array
     */
    public function controlDataForJs()
    {
        $data = [
            'input_type'      => $this->inputType(),
            'builder_options' => $this->builderOptions()
        ];

        return $data;
    }
}
