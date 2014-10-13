<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage plugin
 *
 * SwitchTemplate plugin.
 */
$switchtemplateCorePath = realpath($modx->getOption('switchtemplate.core_path', null, $modx->getOption('core_path') . 'components/switchtemplate/')) . '/';
$modx->getService('switchtemplate', 'SwitchTemplate', $switchtemplateCorePath . 'model/switchtemplate/');

switch ($modx->event->name) {
    case 'OnLoadWebPageCache':
        if ($modx->switchtemplate->fromCache) {
            return;
        }
    case 'OnLoadWebDocument':

        $modeKey = $modx->switchtemplate->config['mode_key'];
        $mode = isset($_REQUEST[$modeKey]) ? $_REQUEST[$modeKey] : null;

        // if SwitchTemplate setting with the given key is found
        if ($mode && $templates = $modx->getObject('SwitchtemplateSettings', array('key' => $mode))) {
            // change the template on the fly
            $output = $modx->switchtemplate->switchTemplate($mode, $templates);

            // if the output is not null
            if ($output !== null) {
                // break MODX execution and return the output
                echo $output;
                exit;
            }
        }

        // do nothing.
        break;
}