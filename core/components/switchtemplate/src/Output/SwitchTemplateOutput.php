<?php
/**
 * @package switchtemplate
 * @subpackage output
 */

namespace TreehillStudio\SwitchTemplate\Output;

use modResource;
use modX;
use SwitchtemplateSettings;
use TreehillStudio\SwitchTemplate\SwitchTemplate;

abstract class SwitchTemplateOutput
{
    /** @var modX $modx */
    protected $modx;
    /** @var SwitchTemplate $switchtemplate */
    protected $switchtemplate;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    /**
     * SwitchTemplateOutput constructor.
     * @param modX $modx
     * @param array $scriptProperties
     */
    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties =& $scriptProperties;
        $this->modx =& $modx;
        $corePath = $this->modx->getOption('switchtemplate.core_path', null, $this->modx->getOption('core_path') . 'components/switchtemplate/');
        $this->switchtemplate = $this->modx->getService('switchtemplate', 'SwitchTemplate', $corePath . 'model/switchtemplate/', [
            'core_path' => $corePath
        ]);
    }

    /**
     * @param modResource $resource
     * @param SwitchtemplateSettings $setting
     * @return string
     */
    abstract public function run(&$resource, &$setting);
}
