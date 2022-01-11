<?php
/**
 * @package switchtemplate
 * @subpackage plugin
 */

namespace TreehillStudio\SwitchTemplate\Plugins\Events;

use SwitchtemplateSettings;
use TreehillStudio\SwitchTemplate\Plugins\Plugin;

class OnLoadWebDocument extends Plugin
{
    public function process()
    {
        $modeKey = $this->switchtemplate->getOption('mode_key');
        $mode = $_REQUEST[$modeKey] ?? '';

        /** @var SwitchtemplateSettings $setting */
        $setting = $this->modx->getObject('SwitchtemplateSettings', ['key' => $mode]);

        // If SwitchTemplate setting with the given key is found
        if ($mode && $setting) {
            // Change the template on the fly
            $output = $this->switchtemplate->switchTemplate($setting);

            // If the output is not null
            if ($output !== null) {
                // Break MODX execution and return the output
                exit($output);
            } elseif ($setting->get('extension')) {
                // Redirect to the default resource without a changed extension
                $args = $_GET;
                unset($args[$this->modx->getOption('request_param_alias', null, 'q')]);
                unset($args[$this->switchtemplate->getOption('mode_key')]);
                $this->modx->sendRedirect($this->modx->makeUrl($this->modx->resourceIdentifier, '', $args, 'full'));
            }
        }
    }
}
