<?php
/**
 * @package switchtemplate
 * @subpackage plugin
 */

class SwitchTemplateOnLoadWebDocument extends SwitchTemplatePlugin
{
    public function run()
    {
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
                exit($output);
            } elseif ($setting->get('extension')) {
                // redirect to the default resource without a changed extension
                $args = $_GET;
                unset($args[$this->modx->getOption('request_param_alias', null, 'q')]);
                unset($args[$this->switchtemplate->getOption('mode_key')]);
                $this->modx->sendRedirect($this->modx->makeUrl($this->modx->resourceIdentifier, '', $args, 'full'));
            }
        }
        return;
    }
}
