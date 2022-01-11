<?php
/**
 * Update a Setting
 *
 * @package switchtemplate
 * @subpackage processors
 */

use TreehillStudio\SwitchTemplate\Processors\ObjectUpdateProcessor;

class SwitchTemplateSettingUpdateProcessor extends ObjectUpdateProcessor
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

return 'SwitchTemplateSettingUpdateProcessor';
