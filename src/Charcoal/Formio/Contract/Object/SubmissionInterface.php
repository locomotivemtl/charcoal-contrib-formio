<?php

namespace Charcoal\Formio\Contract\Object;

/**
 * Formio Submission Object
 */
interface SubmissionInterface
{
    /**
     * @return string
     */
    public function submissionData();

    /**
     * @return string
     */
    public function form();
}
