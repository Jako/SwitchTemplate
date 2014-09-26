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
        $c->where(array(
            'deleted' => false,
            'published' => true
        ));
        return $c;
    }

}

return 'SwitchTemplateResourcesGetListProcessor';
