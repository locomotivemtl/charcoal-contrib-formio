<?php

namespace Charcoal\Admin\Property\Input\Formio;

// from pimple
use Charcoal\Formio\Object\Submission;
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
class SubmissionInput extends FormInput
{
    /**
     * Inject dependencies from a DI Container.
     *
     * @param  Container $container A dependencies container instance.
     * @return void
     */
    protected function setDependencies(Container $container)
    {
        parent::setDependencies($container);
    }

    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructore is required.
     *
     * @param array|\ArrayAccess $data Constructor data.
     * @return void
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }

    /**
     * Retrieve the default builder options.
     *
     * @return array
     */
    public function defaultBuilderOptions()
    {
        return [
            'readOnly' => true,
            'viewAsHtml' => true,
            'builder'  => [
                'customExample' => [
                    'title'      => 'Example Components',
                    'default'    => true,
                    'weight'     => 0,
                    'components' => [
                        'textfield'   => true,
                        'textarea'    => true,
                        'email'       => true,
                        'phoneNumber' => true
                    ]
                ],
                'basic'         => [
                    'default' => false,
                ],
                'advanced'      => [
                    'components' => [
                        'recaptcha' => false,
                        'form'      => false,
                        'location'  => false,
                        'modaledit' => false,
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
        $prop = $this->p();
        $val  = $prop->inputVal($this->propertyVal(), [
            'lang' => $this->lang()
        ]);

        if ($val === null) {
            return '';
        }

        if (!is_scalar($val)) {
            throw new \UnexpectedValueException(sprintf(
                'Property Input Value must be a string, received %s',
                (is_object($val) ? get_class($val) : gettype($val))
            ));
        }

        if (isset($val) && $val !== null) {
            if ($this->viewController()->objType() === Submission::objType()) {
                $submissionModelId = $this->viewController()->objId();
                $model = $this->submissionModel($submissionModelId);
            } else {
                $model = $this->submissionModel($val);
            }

            if ($model->id()) {
                return $model->submissionData();
            }
        }

        return $val;
    }

    /**
     * @param string|null $val The input val.
     * @return \Charcoal\Source\StorableInterface
     */
    private function submissionModel($val = null)
    {
        if (isset($this->submissionModel)) {
            return $this->submissionModel;
        }

        $submissionModel       = $this->submissionProto()->load($val);
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
     * Retrieve the control's data options for JavaScript components.
     *
     * @return array
     */
    public function controlDataForJs()
    {
        $data = [
            'input_type'      => $this->inputType(),
            'builder_options' => $this->builderOptions(),
            'schema'          => json_decode($this->formSchema(), true),
            'submission'      => json_decode($this->submission(), true),
            'submission_id'   => $this->submissionModel()->id()
        ];

        return $data;
    }
}
