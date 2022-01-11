<?php
/**
 * Abstract remove processor
 *
 * @package switchtemplate
 * @subpackage processors
 */

namespace TreehillStudio\SwitchTemplate\Processors;

use modObjectRemoveProcessor;
use modX;
use TreehillStudio\SwitchTemplate\SwitchTemplate;

/**
 * Class ObjectRemoveProcessor
 */
class ObjectRemoveProcessor extends modObjectRemoveProcessor
{
    public $languageTopics = ['switchtemplate:default'];

    /** @var SwitchTemplate $switchtemplate */
    public $switchtemplate;

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
}
