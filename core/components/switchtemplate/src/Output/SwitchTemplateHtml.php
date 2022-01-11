<?php
/**
 * @package switchtemplate
 * @subpackage plugin
 */

namespace TreehillStudio\SwitchTemplate\Output;

class SwitchTemplateHtml extends SwitchTemplateOutput
{
    public function run(&$resource, &$setting)
    {
        $this->switchtemplate->registerScripts();
    }
}
