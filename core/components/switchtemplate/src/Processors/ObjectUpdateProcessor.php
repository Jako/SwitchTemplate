<?php
/**
 * Abstract update processor
 *
 * @package switchtemplate
 * @subpackage processors
 */

namespace TreehillStudio\SwitchTemplate\Processors;

use modObjectUpdateProcessor;
use modX;
use TreehillStudio\SwitchTemplate\SwitchTemplate;

/**
 * Class ObjectUpdateProcessor
 */
class ObjectUpdateProcessor extends modObjectUpdateProcessor
{
    public $languageTopics = ['switchtemplate:default'];

    /** @var SwitchTemplate $switchtemplate */
    public $switchtemplate;

    protected $required = [];

    /**
     * {@inheritDoc}
     * @param modX $modx A reference to the modX instance
     * @param array $properties An array of properties
     */
    public function __construct(modX &$modx, array $properties = [])
    {
        parent::__construct($modx, $properties);

        $corePath = $this->modx->getOption('switchtemplate.core_path', null, $this->modx->getOption('core_path') . 'components/switchtemplate/');
        $this->switchtemplate = $this->modx->getService('switchtemplate', 'SwitchTemplate', $corePath . 'model/switchtemplate/');
    }

    /**
     * Get a boolean property.
     * @param string $k
     * @param mixed $default
     * @return bool
     */
    public function getBooleanProperty($k, $default = null)
    {
        return ($this->getProperty($k, $default) === 'true' || $this->getProperty($k, $default) === true || $this->getProperty($k, $default) === '1' || $this->getProperty($k, $default) === 1);
    }

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function beforeSave()
    {
        foreach ($this->required as $required) {
            $value = $this->getProperty($required);
            if (empty($value)) {
                $this->addFieldError($required, $this->modx->lexicon('field_required'));
            }
        }

        return parent::beforeSave();
    }
}
