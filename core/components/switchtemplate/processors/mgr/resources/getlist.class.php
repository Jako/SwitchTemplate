<?php
/**
 * Get list Resources
 *
 * @package switchtemplate
 * @subpackage processors
 */

class SwitchTemplateResourcesGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $languageTopics = array('switchtemplate:default');
    public $defaultSortField = 'pagetitle';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'switchtemplate.resources';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $valuesQuery = $this->getProperty('valuesqry');
        $query = (!$valuesQuery) ? $this->getProperty('query') : '';
        if (!empty($query)) {
            $c->where(array(
                'pagetitle:LIKE' => '%' . $query . '%',
                'OR:alias:LIKE' => '%' . $query . '%'
            ));
        }
        $c->where(array(
            'deleted' => false,
            'published' => true
        ));
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $valuesQuery = $this->getProperty('valuesqry');
        $id = (!$valuesQuery) ? $this->getProperty('id') : $this->getProperty('query');
        if (!empty($id)) {
            $c->where(array(
                'id:IN' => array_map('intval', explode('|', $id))
            ));
        }
        return $c;
    }
}

return 'SwitchTemplateResourcesGetListProcessor';
