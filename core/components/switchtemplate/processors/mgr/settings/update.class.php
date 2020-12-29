<?php
/**
 * Update system setting
 *
 * @param string $key The key of the setting
 * @param string $value The value of the setting
 * @param string $xtype The xtype for the setting, for rendering purposes
 * @param string $area The area for the setting
 * @param string $namespace The namespace for the setting
 * @param string $name The lexicon name for the setting
 * @param string $description The lexicon description for the setting
 *
 * @package switchtemplate
 * @subpackage processors
 */

include_once MODX_CORE_PATH . 'model/modx/processors/system/settings/update.class.php';

class SwitchTemplateSystemSettingsUpdateProcessor extends modSystemSettingsUpdateProcessor
{
    public $checkSavePermission = false;
    public $classKey = 'modSystemSetting';
    public $languageTopics = array('setting', 'namespace', 'switchtemplate:default');
    public $objectType = 'setting';
    public $primaryKeyField = 'key';

    public function beforeSave()
    {
        $this->setProperty('namespace', 'switchtemplate');
        $this->checkForBooleanValue();
        $this->checkCanSave();
        return parent::beforeSave();
    }

    public function afterSave()
    {
        $this->updateTranslations($this->getProperties());
        $this->clearCache();
        return parent::afterSave();
    }

    /**
     * Verify the Namespace passed is a valid Namespace
     */
    protected function checkCanSave()
    {
        $key = $this->getProperty('key');
        if (strpos($key, 'switchtemplate.') !== 0) {
            $this->addFieldError('key', $this->modx->lexicon('switchtemplate.systemsetting_key_err_nv'));
        }
        if (!$this->modx->hasPermission('settings') && !$this->modx->hasPermission('switchtemplate_settings')) {
            $this->addFieldError('usergroup', $this->modx->lexicon('switchtemplate.systemsetting_usergroup_err_nv'));
        }
    }

    /**
     * If this is a Boolean setting, ensure the value of the setting is 1/0
     * @return mixed
     */
    public function checkForBooleanValue()
    {
        $xtype = $this->getProperty('xtype');
        $value = $this->getProperty('value');
        if ($xtype == 'combo-boolean' && !is_numeric($value)) {
            $value = in_array($value, array('yes', 'Yes', $this->modx->lexicon('yes'), 'true', 'True')) ? 1 : 0;
            $this->object->set('value', $value);
        }
        return $value;
    }

    /**
     * Update lexicon name/description
     *
     * @param array $fields
     * @return void
     */
    public function updateTranslations(array $fields)
    {
        if (isset($fields['name'])) {
            $this->object->updateTranslation('setting_' . $this->object->get('key'), $fields['name'], array(
                'namespace' => $this->object->get('namespace'),
            ));
        }

        if (isset($fields['description'])) {
            $this->object->updateTranslation('setting_' . $this->object->get('key') . '_desc', $fields['description'], array(
                'namespace' => $this->object->get('namespace'),
            ));
        }
    }

    /**
     * Clear the settings cache and reload the config
     * @return void
     */
    public function clearCache()
    {
        $this->modx->reloadConfig();
    }
}

return 'SwitchTemplateSystemSettingsUpdateProcessor';
