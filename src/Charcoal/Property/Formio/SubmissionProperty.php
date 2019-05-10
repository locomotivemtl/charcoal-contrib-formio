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

    /**
     * Retrieve the property's SQL data type (storage format).
     *
     * For a lack of better array support in mysql, data is stored as encoded JSON in a TEXT.
     *
     * @return string
     * @see StorableProperyTrait::sqlType()
     */
    public function sqlType()
    {
        return 'LONGTEXT';
    }
}
