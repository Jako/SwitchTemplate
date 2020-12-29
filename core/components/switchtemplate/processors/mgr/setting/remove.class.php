<?php
/**
 * Remove setting
 *
 * @package switchtemplate
 * @subpackage processor
 */

class SwitchTemplateSettingsRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'SwitchtemplateSettings';
    public $languageTopics = array('switchtemplate:default');
    public $objectType = 'switchtemplate.settings';
}

return 'SwitchTemplateSettingsRemoveProcessor';
