<?php
/**
 * @package switchtemplate
 * @subpackage plugin
 */

abstract class SwitchTemplatePlugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var SwitchTemplate $switchtemplate */
    protected $switchtemplate;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties =& $scriptProperties;
        $this->modx = &$modx;
        $corePath = $this->modx->getOption('switchtemplate.core_path', null, $this->modx->getOption('core_path') . 'components/switchtemplate/');
        $this->switchtemplate = $this->modx->getService('switchtemplate', 'SwitchTemplate', $corePath . 'model/switchtemplate/', array(
            'core_path' => $corePath
        ));
    }

    abstract public function run();
}
