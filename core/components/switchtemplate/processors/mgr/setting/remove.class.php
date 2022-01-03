<?php
/**
 * Remove setting
 *
 * @package switchtemplate
 * @subpackage processors
 */

class SwitchTemplateSettingsRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'SwitchtemplateSettings';
    public $languageTopics = array('switchtemplate:default');
    public $objectType = 'switchtemplate.settings';
}

return 'SwitchTemplateSettingsRemoveProcessor';
