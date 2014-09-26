<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage processor
 *
 * Remove processor for SwitchTemplate CMP
 */
class SwitchTemplateSettingsRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'SwitchtemplateSettings';
    public $languageTopics = array('switchtemplate:default');
    public $objectType = 'switchtemplate.settings';
}

return 'SwitchTemplateSettingsRemoveProcessor';
