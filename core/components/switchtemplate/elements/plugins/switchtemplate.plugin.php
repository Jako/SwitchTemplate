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

$className = 'TreehillStudio\SwitchTemplate\Plugins\Events\\' . $modx->event->name;

$corePath = $modx->getOption('switchtemplate.core_path', null, $modx->getOption('core_path') . 'components/switchtemplate/');
/** @var SwitchTemplate $switchtemplate */
$switchtemplate = $modx->getService('switchtemplate', 'SwitchTemplate', $corePath . 'model/switchtemplate/', [
    'core_path' => $corePath
]);

if ($switchtemplate) {
    if (class_exists($className)) {
        $handler = new $className($modx, $scriptProperties);
        if (get_class($handler) == $className) {
            $handler->run();
        } else {
            $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' could not be initialized!', '', 'SwitchTemplate Plugin');
        }
    } else {
        $modx->log(xPDO::LOG_LEVEL_ERROR, $className. ' was not found!', '', 'SwitchTemplate Plugin');
    }
}

return;
