<?php

namespace Charcoal\Formio\Object;

// local dependencies
use Charcoal\Formio\Contract\Object\FormInterface;

// from charcoal-object
use Charcoal\Object\Content;

/**
 * Formio Form Object
 */
class Form extends Content implements
    FormInterface
{
    /**
     * @var string $title
     */
    private $title;

    /**
     * @var string $schema
     */
    private $schema;

    /**
     * @param string $title Title for Form.
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string $schema Schema for Form.
     * @return self
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }
}
