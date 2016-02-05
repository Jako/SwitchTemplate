<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage processor
 *
 * Get list processor for SwitchTemplate CMP
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
        $id = $this->getProperty('id');
        if (!empty($id)) {
            $c->where(array(
                'id' => $id
            ));
        }
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

    public function prepareRow(xPDOObject $object)
    {
        $ta = $object->toArray('', false, true);
        $ta['type'] = $this->modx->lexicon($ta['type']);
        return $ta;
    }

}

return 'SwitchTemplateSettingsGetListProcessor';
