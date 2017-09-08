<?php
/**
 * SwitchTemplate plugin
 *
 * @package switchtemplate
 * @subpackage plugin
 *
 * @var modX $modx
 * @var array $scriptProperties
 */
$corePath = $modx->getOption('switchtemplate.core_path', null, $modx->getOption('core_path') . 'components/switchtemplate/');
/** @var SwitchTemplate $switchtemplate */
$switchtemplate = $modx->getService('switchtemplate', 'SwitchTemplate', $corePath . 'model/switchtemplate/', array(
    'core_path' => $corePath
));

$className = 'SwitchTemplate' . $modx->event->name;
$modx->loadClass('SwitchTemplatePlugin', $switchtemplate->getOption('eventsPath'), true, true);
$modx->loadClass($className, $switchtemplate->getOption('eventsPath'), true, true);
if (class_exists($className)) {
    /** @var SwitchTemplatePlugin $handler */
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
}

return;
