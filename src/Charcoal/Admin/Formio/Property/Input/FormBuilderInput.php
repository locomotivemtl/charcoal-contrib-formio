<?php

namespace Charcoal\Admin\Formio\Property\Input;

// from charcoal-property
use Charcoal\Admin\Property\AbstractPropertyInput;
use InvalidArgumentException;

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
class FormBuilderInput extends AbstractPropertyInput
{
    /**
     * Settings for the formio form builder.
     *
     * @var array
     */
    private $builderOptions;

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
     * Merge (replacing or adding) map widget options.
     *
     * @param  array $settings The map widget options.
     * @return self
     */
    public function mergeBuilderOptions(array $settings)
    {
        $this->builderOptions = array_merge($this->builderOptions, $settings);

        return $this;
    }

    /**
     * Add (or replace) an map widget option.
     *
     * @param  string $key The setting to add/replace.
     * @param  mixed  $val The setting's value to apply.
     * @throws InvalidArgumentException If the identifier is not a string.
     * @return self
     */
    public function addMapOption($key, $val)
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
     * Retrieve the map widget's options.
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
     * Retrieve the default map widget options.
     *
     * @return array
     */
    public function defaultBuilderOptions()
    {
        return [
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
            'basic' => [
                'default'    => false,
            ],
            'advanced'    => [
                'components' => [
                    'recaptcha' => false,
                    'form'      => false,
                    'location'  => false,
                    'modaledit' => false,
                ]
            ]
        ];
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
