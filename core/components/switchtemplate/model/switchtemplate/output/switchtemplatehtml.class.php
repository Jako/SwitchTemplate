<?php

/**
 * @package switchtemplate
 * @subpackage plugin
 */
class SwitchTemplateHtml extends SwitchTemplateOutput
{
    public function run(&$resource, &$setting)
    {
        $this->switchtemplate->registerScripts();
    }
}
