<?php

namespace Charcoal\Property\Formio;

use Charcoal\Property\StructureProperty;

/**
 * Formio Property
 *
 * Allows storage of a formio compatible data array.
 * Data storage is similar as StructureProperty.
 * The property's value is free to be anything serializable.
 */
class SchemaProperty extends StructureProperty
{
    /**
     * Retrieve the property's type identifier.
     *
     * @return string
     */
    public function type()
    {
        return 'formio/schema';
    }
}
