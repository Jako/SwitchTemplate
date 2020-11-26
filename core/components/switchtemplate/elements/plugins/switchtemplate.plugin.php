<?php
/**
 * SwitchTemplate Plugin
 *
 * @package switchtemplate
 * @subpackage pluginfile
 *
 * @var modX $modx
 * @var array $scriptProperties
 */

$className = 'SwitchTemplate' . $modx->event->name;

$corePath = $modx->getOption('switchtemplate.core_path', null, $modx->getOption('core_path') . 'components/switchtemplate/');
/** @var SwitchTemplate $switchtemplate */
$switchtemplate = $modx->getService('switchtemplate', 'SwitchTemplate', $corePath . 'model/switchtemplate/', array(
    'core_path' => $corePath
));

if ($switchtemplate) {
    $modx->loadClass('SwitchTemplatePlugin', $switchtemplate->getOption('modelPath') . 'switchtemplate/events/', true, true);
    $modx->loadClass($className, $switchtemplate->getOption('modelPath') . 'switchtemplate/events/', true, true);
    if (class_exists($className)) {
        /** @var SwitchTemplatePlugin $handler */
        $handler = new $className($modx, $scriptProperties);
        $handler->run();
    }
}

return;
