<?php

namespace Charcoal\Formio\Contract\Object;

/**
 * FormInterface
 * @package Charcoal\Formio\Contract\Object
 */
interface FormInterface
{
    /**
     * @return string
     */
    public function title();

    /**
     * @return string
     */
    public function schema();
}
