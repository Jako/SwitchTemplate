<?php
/**
 * Get resources
 *
 * @package switchtemplate
 * @subpackage processors
 */

use TreehillStudio\SwitchTemplate\Processors\ObjectGetListProcessor;

class SwitchTemplateResourcesGetListProcessor extends ObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $defaultSortField = 'pagetitle';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'switchtemplate.resources';

    protected $search = ['pagetitle', 'alias'];

    /**
     * (@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);
        $c->where([
            'deleted' => false,
            'published' => true
        ]);
        return $c;
    }

}

return 'SwitchTemplateResourcesGetListProcessor';
