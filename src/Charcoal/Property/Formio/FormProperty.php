<?php

namespace Charcoal\Property\Formio;

// from charcoal-model
use Charcoal\Config\ConfigInterface;
use Charcoal\Formio\FormioConfig;
use Charcoal\Model\ModelFactoryTrait;
use Charcoal\Model\ModelInterface;

// from charcoal-property
use Charcoal\Property\AbstractProperty;

// from pimple
use Pimple\Container;

// exceptions
use InvalidArgumentException;
use RuntimeException;

/**
 * Formio Form Property
 *
 * Allows storage of a Charcoal\Formio\Form model which contains a schema property.
 * Acts as relation with externalized From schema.
 */
class FormProperty extends AbstractProperty
{
    use ModelFactoryTrait;

    /**
     * @var string $objType
     */
    private $objType;

    /**
     * @var ConfigInterface|FormioConfig $formioConfig
     */
    private $formioConfig;

    /**
     * Inject dependencies from a DI Container.
     *
     * @param  Container $container A dependencies container instance.
     * @return void
     */
    protected function setDependencies(Container $container)
    {
        parent::setDependencies($container);

        $this->setModelFactory($container['model/factory']);
        $this->formioConfig = $container['formio/config'];
    }

    /**
     * Section constructor.
     * @param array $data Init data.
     */
    public function __construct(array $data = null)
    {
        parent::__construct($data);

        if (!isset($data['obj_type'])) {
            $this->setObjType($this->formioConfig->formObject());
        }
    }

    /**
     * Call this on pre-save and pre-update,
     * _schema should be the submitted property value
     * _fromId should be the actual data saved in the object or null.
     *
     * @param string $schema The schema to save.
     * @param string $formId The form object id.
     * @return string The form id.
     */
    public function createOrUpdateRelation($schema, $formId = null)
    {
        if (!$formId) {
            $model = $this->proto();
            $model->setSchema($schema);
            $model->save();

            return $model->id();
        }

        $model = $this->proto()->load($formId);
        $model->setSchema($schema);
        $model->update();

        return $model->id();
    }

    /**
     * @return string
     */
    public function type()
    {
        return 'object';
    }

    /**
     * Set the object type to build the choices from.
     *
     * @param  string $objType The object type.
     * @throws InvalidArgumentException If the object type is not a string.
     * @return self
     */
    public function setObjType($objType)
    {
        if (!is_string($objType)) {
            throw new InvalidArgumentException(
                'Property object type ("obj_type") must be a string.'
            );
        }

        $this->objType = $objType;

        return $this;
    }

    /**
     * Retrieve the object type to build the choices from.
     *
     * @throws RuntimeException If the object type was not previously set.
     * @return string
     */
    public function objType()
    {
        if ($this->objType === null) {
            throw new RuntimeException(sprintf(
                'Missing object type ("obj_type"). Invalid property "%s".',
                $this->ident()
            ));
        }

        return $this->objType;
    }

    /**
     * @return string
     */
    public function sqlType()
    {
        // Read from proto's key
        $proto = $this->proto();
        $key   = $proto->p($proto->key());

        return $key->sqlType();
    }

    /**
     * @return integer
     */
    public function sqlPdoType()
    {
        // Read from proto's key
        $proto = $this->proto();
        $key   = $proto->p($proto->key());

        return $key->sqlPdoType();
    }

    /**
     * Retrieve a singleton of the {self::$objType} for prototyping.
     *
     * @return ModelInterface
     */
    public function proto()
    {
        return $this->modelFactory()->get($this->objType());
    }
}
