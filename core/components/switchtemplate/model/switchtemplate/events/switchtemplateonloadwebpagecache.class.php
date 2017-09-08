<?php

/**
 * @package switchtemplate
 * @subpackage plugin
 */
class SwitchTemplateOnLoadWebPageCache extends SwitchTemplatePlugin
{
    public function run()
    {
        if ($this->switchtemplate->fromCache) {
            // stop if the switched template is already loaded from cache
            return;
        }

        $modeKey = $this->switchtemplate->getOption('mode_key');
        $mode = isset($_REQUEST[$modeKey]) ? $_REQUEST[$modeKey] : '';

        /** @var SwitchtemplateSettings $setting */
        $setting = $this->modx->getObject('SwitchtemplateSettings', array('key' => $mode));

        // if SwitchTemplate setting with the given key is found
        if ($mode && $setting) {
            // change the template on the fly
            $output = $this->switchtemplate->switchTemplate($setting);

            // if the output is not null
            if ($output !== null) {
                // break MODX execution and return the output
                echo $output;
                exit;
            }
        }
        return;
    }
}
