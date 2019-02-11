<?php

namespace Charcoal\Formio\Object;

// local dependencies
use Charcoal\Formio\Contract\Object\SubmissionInterface;

// from charcoal-object
use Charcoal\Object\Content;

/**
 * Formio Submission Object
 */
class Submission extends Content implements
    SubmissionInterface
{
    /**
     * JSON string form submission.
     *
     * @var string $submission
     */
    private $submission;

    /**
     * @var string $form The form id.
     */
    private $form;

    /**
     * @return string
     */
    public function submissionData()
    {
        return $this->submission;
    }

    /**
     * @param string $submission Submission for Submission.
     * @return self
     */
    public function setSubmissionData($submission)
    {
        $this->submission = $submission;

        return $this;
    }

    /**
     * @return string
     */
    public function form()
    {
        return $this->form;
    }

    /**
     * @param string $form Form for Submission.
     * @return self
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }
}
