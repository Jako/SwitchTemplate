<?php
/**
 * @package switchtemplate
 * @subpackage plugin
 */

namespace TreehillStudio\SwitchTemplate\Plugins\Events;

use SwitchtemplateSettings;
use TreehillStudio\SwitchTemplate\Plugins\Plugin;

class OnLoadWebPageCache extends Plugin
{
    public function process()
    {
        if ($this->switchtemplate->fromCache) {
            // Stop if the switched template is already loaded from cache
            return;
        }

        $modeKey = $this->switchtemplate->getOption('mode_key');
        $mode = isset($_REQUEST[$modeKey]) ? $_REQUEST[$modeKey] : '';

        /** @var SwitchtemplateSettings $setting */
        $setting = $this->modx->getObject('SwitchtemplateSettings', ['key' => $mode]);

        // If SwitchTemplate setting with the given key is found
        if ($mode && $setting) {
            // Change the template on the fly
            $output = $this->switchtemplate->switchTemplate($setting);

            // If the output is not null
            if ($output !== null) {
                // Break MODX execution and return the output
                echo $output;
                @session_write_close();
                exit;
            }
        }
    }
}
