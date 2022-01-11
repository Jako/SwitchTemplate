<?php
/**
 * Home Manager Controller class for SwitchTemplate
 *
 * @package switchtemplate
 * @subpackage controller
 */

/**
 * Class SwitchtemplateHomeManagerController
 */
class SwitchtemplateHomeManagerController extends modExtraManagerController
{
    /** @var SwitchTemplate $switchtemplate */
    public $switchtemplate;

    /**
     * {@inheritDoc}
     */
    public function initialize()
    {
        $path = $this->modx->getOption('switchtemplate.core_path', null, $this->modx->getOption('core_path') . 'components/switchtemplate/');
        $this->switchtemplate = $this->modx->getService('switchtemplate', 'SwitchTemplate', $path . 'model/switchtemplate/', [
            'core_path' => $path
        ]);

        parent::initialize();
    }

    /**
     * {@inheritDoc}
     */
    public function loadCustomCssJs()
    {
        $assetsUrl = $this->switchtemplate->getOption('assetsUrl');
        $jsUrl = $this->switchtemplate->getOption('jsUrl') . 'mgr/';
        $jsSourceUrl = $assetsUrl . '../../../source/js/mgr/';
        $cssUrl = $this->switchtemplate->getOption('cssUrl') . 'mgr/';
        $cssSourceUrl = $assetsUrl . '../../../source/css/mgr/';

        if ($this->switchtemplate->getOption('debug') && ($this->switchtemplate->getOption('assetsUrl') != MODX_ASSETS_URL . 'components/switchtemplate/')) {
            $this->addCss($cssSourceUrl . 'switchtemplate.css?v=v' . $this->switchtemplate->version);
            $this->addJavascript($jsSourceUrl . 'switchtemplate.js?v=v' . $this->switchtemplate->version);
            $this->addJavascript($jsSourceUrl . 'helper/combo.js?v=v' . $this->switchtemplate->version);
            $this->addJavascript($jsSourceUrl . 'helper/util.js?v=v' . $this->switchtemplate->version);
            $this->addJavascript($jsSourceUrl . 'widgets/home.panel.js?v=v' . $this->switchtemplate->version);
            $this->addJavascript($jsSourceUrl . 'widgets/setting.grid.js?v=v' . $this->switchtemplate->version);
            $this->addJavascript(MODX_MANAGER_URL . 'assets/modext/widgets/core/modx.grid.settings.js?v=v' . $this->switchtemplate->version);
            $this->addJavascript($jsSourceUrl . 'widgets/settings.panel.js?v=v' . $this->switchtemplate->version);
            $this->addLastJavascript($jsSourceUrl . 'sections/home.js?v=v' . $this->switchtemplate->version);
        } else {
            $this->addCss($cssUrl . 'switchtemplate.min.css?v=v' . $this->switchtemplate->version);
            $this->addJavascript(MODX_MANAGER_URL . 'assets/modext/widgets/core/modx.grid.settings.js');
            $this->addLastJavascript($jsUrl . 'switchtemplate.min.js?v=v' . $this->switchtemplate->version);
        }
        $this->addHtml('<script type="text/javascript">
        Ext.onReady(function() {
            SwitchTemplate.config = ' . json_encode($this->switchtemplate->options, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . ';
            MODx.load({xtype: "switchtemplate-page-home"});
        });
        </script>');
    }

    /**
     * {@inheritDoc}
     * @return string[]
     */
    public function getLanguageTopics()
    {
        return ['core:setting', 'switchtemplate:default'];
    }

    /**
     * {@inheritDoc}
     * @param array $scriptProperties
     */
    public function process(array $scriptProperties = [])
    {
    }

    /**
     * {@inheritDoc}
     * @return string|null
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('switchtemplate');
    }

    /**
     * {@inheritDoc}
     * @return string
     */
    public function getTemplateFile()
    {
        return $this->switchtemplate->getOption('templatesPath') . 'home.tpl';
    }
}
