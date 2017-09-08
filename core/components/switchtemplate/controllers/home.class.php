<?php
/**
 * Home controller class for SwitchTemplate CMP.
 *
 * @package switchtemplate
 * @subpackage controller
 */
require_once dirname(dirname(__FILE__)) . '/model/switchtemplate/switchtemplate.class.php';

class SwitchtemplateHomeManagerController extends modExtraManagerController
{
    /** @var SwitchTemplate $switchtemplate */
    public $switchtemplate;

    public function initialize()
    {
        $this->switchtemplate = new SwitchTemplate($this->modx);
        parent::initialize();
    }

    public function loadCustomCssJs()
    {
        $assetsUrl = $this->switchtemplate->getOption('assetsUrl');
        $jsUrl = $this->switchtemplate->getOption('jsUrl') . 'mgr/';
        $jsSourceUrl = $assetsUrl . '../../../source/js/mgr/';
        $cssUrl = $this->switchtemplate->getOption('cssUrl') . 'mgr/';
        $cssSourceUrl = $assetsUrl . '../../../source/css/mgr/';

        $this->modx->controller->addLexiconTopic('switchtemplate:default');

        if ($this->switchtemplate->getOption('debug') && ($this->switchtemplate->getOption('assetsUrl') != MODX_ASSETS_URL . 'components/switchtemplate/')) {
            $this->addCss($cssSourceUrl . 'switchtemplate.css');
            $this->addJavascript($jsSourceUrl . 'switchtemplate.js');
            $this->addJavascript($jsSourceUrl . 'helper/switchtemplate.combo.js');
            $this->addJavascript($jsSourceUrl . 'widgets/switchtemplate.grid.js');
            $this->addJavascript($jsSourceUrl . 'widgets/home.panel.js');
            $this->addLastJavascript($jsSourceUrl . 'sections/home.js');
        } else {
            $this->addCss($cssUrl . 'switchtemplate.min.css?v=v' . $this->switchtemplate->version);
            $this->addJavascript($jsUrl . 'switchtemplate.min.js?v=v' . $this->switchtemplate->version);
        }
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            SwitchTemplate.config = ' . json_encode($this->switchtemplate->options, JSON_PRETTY_PRINT) . ';
            MODx.load({ xtype: "switchtemplate-page-home"});
        });
        </script>');
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

    public function getTemplateFile()
    {
        return $this->switchtemplate->getOption('templatesPath') . 'home.tpl';
    }
}
