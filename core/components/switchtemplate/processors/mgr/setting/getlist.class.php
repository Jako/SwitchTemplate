<?php
/**
 * Get list Settings
 *
 * @package switchtemplate
 * @subpackage processors
 */

use TreehillStudio\SwitchTemplate\Processors\ObjectGetListProcessor;

class SwitchTemplateSettingsGetListProcessor extends ObjectGetListProcessor
{
    public $classKey = 'SwitchtemplateSettings';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $objectType = 'switchtemplate.settings';

    protected $search = ['name', 'key', 'template', 'include', 'exclude'];

    /**
     * {@inheritDoc}
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $ta = $object->toArray('', false, true);
        $ta['templatename'] = ($ta['template'] != '') ? $ta['template'] : '<span class="green">{original_template_name} '.$ta['name'].'</span>';
        return $ta;
    }
}

return 'SwitchTemplateSettingsGetListProcessor';
