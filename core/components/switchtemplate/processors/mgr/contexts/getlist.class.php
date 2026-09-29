<?php
/**
 * Get contexts
 *
 * @package switchtemplate
 * @subpackage processors
 */

use TreehillStudio\SwitchTemplate\Processors\ObjectGetListProcessor;

class SwitchTemplateContextsGetListProcessor extends ObjectGetListProcessor
{
    public $classKey = 'modContext';
    public $defaultSortField = '`rank`';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'switchtemplate.contexts';

    protected $search = ['key', 'name', 'description'];

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);

        $c->where(['key:!=' => 'mgr']);

        return $c;
    }

    /**
     * {@inheritDoc}
     * @param xPDOQuery $c
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $valuesQuery = $this->getProperty('valuesqry');
        $key = (!$valuesQuery) ? $this->getProperty('key') : $this->getProperty('query');
        if (!empty($key)) {
            $c->where([
                $this->classKey . '.key:IN' => explode('|',  $key)
            ]);
        }

        return $c;
    }

    public function prepareRow($object)
    {
        $ta = $object->toArray();

        $ta['name'] = $ta['name'] ?: $ta['key'];

        return $ta;
    }
}

return 'SwitchTemplateContextsGetListProcessor';
