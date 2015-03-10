<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage controller
 *
 * Settings controller class for SwitchTemplate CMP.
 */
require_once dirname(dirname(dirname(__FILE__))) . '/model/switchtemplate/switchtemplate.class.php';

class SwitchtemplateSettingsManagerController extends modExtraManagerController
{

    public function initialize()
    {
        $this->switchtemplate = new SwitchTemplate($this->modx);
        $this->addJavascript($this->switchtemplate->getOption('jsUrl') . 'mgr/switchtemplate.js');
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            SwitchTemplate.config = ' . $this->modx->toJSON($this->switchtemplate->options) . ';
        });
        </script>');
        return parent::initialize();
    }

    public function getLanguageTopics()
    {
        return array('switchtemplate:default');
    }

    public function process(array $scriptProperties = array())
    {
    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('switchtemplate');
    }

    public function loadCustomCssJs()
    {
        $this->addJavascript($this->switchtemplate->getOption('jsUrl') . 'mgr/widgets/switchtemplate.grid.js');
        $this->addJavascript($this->switchtemplate->getOption('jsUrl') . 'mgr/widgets/settings.panel.js');
        $this->addLastJavascript($this->switchtemplate->getOption('jsUrl') . 'mgr/sections/settings.js');
    }

    public function getTemplateFile()
    {
        return $this->switchtemplate->getOption('templatesPath') . 'default/settings.tpl';
    }

}