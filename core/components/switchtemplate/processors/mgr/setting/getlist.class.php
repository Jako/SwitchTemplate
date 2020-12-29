<?php
/**
 * Get settings
 *
 * @package switchtemplate
 * @subpackage processor
 */

class SwitchTemplateSettingsGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'SwitchtemplateSettings';
    public $languageTopics = array('switchtemplate:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $objectType = 'switchtemplate.settings';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'name:LIKE' => '%' . $query . '%',
                'OR:key:LIKE' => '%' . $query . '%',
                'OR:template:LIKE' => '%' . $query . '%',
                'OR:include:LIKE' => '%' . $query . '%',
                'OR:exclude:LIKE' => '%' . $query . '%'
            ));
        }
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $id = $this->getProperty('id');
        if (!empty($id)) {
            $c->where(array(
                'id:IN' => array_map('intval', explode('|', $id))
            ));
        }
        return $c;
    }

    public function prepareRow(xPDOObject $object)
    {
        $ta = $object->toArray('', false, true);
        $ta['templatename'] = ($ta['template'] != '') ? $ta['template'] : '<span class="green">{original_template_name} '.$ta['name'].'</span>';
        return $ta;
    }

}

return 'SwitchTemplateSettingsGetListProcessor';
