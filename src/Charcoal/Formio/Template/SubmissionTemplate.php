<?php

namespace Charcoal\Formio\Template;

use Charcoal\Admin\AdminTemplate;
use Charcoal\Admin\Property\Input\Formio\FormInput;
use Charcoal\Admin\Ui\ObjectContainerInterface;
use Charcoal\Admin\Ui\ObjectContainerTrait;
use Charcoal\Config\ConfigInterface;
use Charcoal\Formio\FormioConfig;
use Charcoal\Formio\Widget\SubmissionWidget;
use Charcoal\Model\ModelInterface;
use InvalidArgumentException;
use Pimple\Container;

/**
 * Class SubmissionTemplate
 */
class SubmissionTemplate extends AdminTemplate implements
    ObjectContainerInterface
{
    use ObjectContainerTrait;

    /**
     * Retrieve the list of parameters to extract from the HTTP request.
     *
     * @return string[]
     */
    protected function validDataFromRequest()
    {
        return array_merge([
            'obj_type', 'obj_id'
        ], parent::validDataFromRequest());
    }

    public function title()
    {
        return $this->translator()->translation('Submission #').$this->objId();
    }

    /**
     * @throws \Exception
     */
    public function submissionWidget()
    {
        return $this->widgetFactory()
                    ->create(SubmissionWidget::class)
                    ->setTemplateIdent('charcoal/formio/widget/submission')
                    ->setType('charcoal/formio/widget/submission')
                    ->setData([
                        'obj_type' => $this->objType() ?: '',
                        'obj_id'   => $this->objId()
                    ]);
    }
}
