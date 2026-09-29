<?php
/**
 * Get templates
 *
 * @package switchtemplate
 * @subpackage processors
 */

use TreehillStudio\SwitchTemplate\Processors\ObjectGetListProcessor;

class SwitchTemplateTemplatesGetListProcessor extends ObjectGetListProcessor
{
    public $classKey = 'modTemplate';
    public $defaultSortField = 'templatename';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'switchtemplate.templates';

    protected $search = ['templatename', 'description'];
}

return 'SwitchTemplateTemplatesGetListProcessor';
