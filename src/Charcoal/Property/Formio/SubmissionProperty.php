<?php

namespace Charcoal\Property\Formio;

// from charcoal-property
use Charcoal\Property\StructureProperty;

/**
 * Formio Submission Property
 *
 * Allows storage of a formio compatible submission data array.
 * Data storage is similar as StructureProperty.
 * The property's value is free to be anything serializable.
 */
class SubmissionProperty extends StructureProperty
{
    /**
     * Retrieve the property's type identifier.
     *
     * @return string
     */
    public function type()
    {
        return 'formio/submission';
    }
}
