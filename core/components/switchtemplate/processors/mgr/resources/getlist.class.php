<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage processor
 *
 * Get list processor for SwitchTemplate CMP.
 */
class SwitchTemplateResourcesGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $languageTopics = array('switchtemplate:default');
    public $defaultSortField = 'pagetitle';
    public $defaultSortDirection = 'DESC';
    public $objectType = 'switchtemplate.resources';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $query = $this->getProperty('query');
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
        $c->sortby('pagetitle', 'ASC');
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
}

return 'SwitchTemplateResourcesGetListProcessor';
