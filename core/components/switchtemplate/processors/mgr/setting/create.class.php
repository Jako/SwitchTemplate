<?php
/**
 * Create a Setting
 *
 * @package switchtemplate
 * @subpackage processors
 */

use TreehillStudio\SwitchTemplate\Processors\ObjectCreateProcessor;

class SwitchTemplateSettingCreateProcessor extends ObjectCreateProcessor
{
    public $classKey = 'SwitchtemplateSettings';
    public $objectType = 'switchtemplate.settings';

    public function beforeSave()
    {
        $cache = ($this->getProperty('cache')) == 'true' || $this->getProperty('cache') == '1' ? 1 : 0;
        $this->object->set('cache', $cache);
        $include = $this->getProperty('includeData');
        $this->object->set('include', $include);
        $exclude = $this->getProperty('excludeData');
        $this->object->set('exclude', $exclude);
        return parent::beforeSave();
    }
}

return 'SwitchTemplateSettingCreateProcessor';
