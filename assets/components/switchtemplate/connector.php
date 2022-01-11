<?php
/**
 * SwitchTemplate connector
 *
 * @package switchtemplate
 * @subpackage connector
 *
 * @var modX $modx
 */

require_once dirname(__FILE__, 4) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('switchtemplate.core_path', null, $modx->getOption('core_path') . 'components/switchtemplate/');
/** @var SwitchTemplate $switchtemplate */
$switchtemplate = $modx->getService('switchtemplate', 'SwitchTemplate', $corePath . 'model/switchtemplate/', [
    'core_path' => $corePath
]);

// Handle request
$modx->request->handleRequest([
    'processors_path' => $switchtemplate->getOption('processorsPath'),
    'location' => ''
]);
