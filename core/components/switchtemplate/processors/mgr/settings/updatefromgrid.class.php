<?php
/**
 * Update a system setting
 *
 * @package switchtemplate
 * @subpackage processors
 */

require_once dirname(__FILE__) . '/update.class.php';

class SwitchTemplateSystemSettingsUpdateFromGridProcessor extends SwitchTemplateSystemSettingsUpdateProcessor
{
    /**
     * {@inheritDoc}
     * @return bool
     */
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $properties = json_decode($data, true);
        $this->setProperties($properties);
        $this->unsetProperty('data');

        return parent::initialize();
    }
}

return 'SwitchTemplateSystemSettingsUpdateFromGridProcessor';
