<?php
/**
 * Remove a Setting
 *
 * @package switchtemplate
 * @subpackage processors
 */

use TreehillStudio\SwitchTemplate\Processors\ObjectRemoveProcessor;

class SwitchTemplateSettingsRemoveProcessor extends ObjectRemoveProcessor
{
    public $classKey = 'SwitchtemplateSettings';
    public $objectType = 'switchtemplate.settings';
}

return 'SwitchTemplateSettingsRemoveProcessor';
