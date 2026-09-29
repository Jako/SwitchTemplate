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

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function beforeSave()
    {
        $cache = ($this->getProperty('cache')) == 'true' || $this->getProperty('cache') == '1' ? 1 : 0;
        $this->object->set('cache', $cache);
        $include = $this->getProperty('includeData');
        $this->object->set('include', $include);
        $exclude = $this->getProperty('excludeData');
        $this->object->set('exclude', $exclude);
        $templates = $this->getProperty('templatesData');
        $this->object->set('templates', $templates);
        $contexts = $this->getProperty('contextsData');
        $this->object->set('contexts', $contexts);
        return parent::beforeSave();
    }
}

return 'SwitchTemplateSettingCreateProcessor';
