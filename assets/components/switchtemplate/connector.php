<?php
/**
 * SwitchTemplate Connector
 *
 * @package switchtemplate
 * @subpackage connector
 */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('switchtemplate.core_path', null, $modx->getOption('core_path') . 'components/switchtemplate/');

$switchtemplate = $modx->getService(
    'switchtemplate',
    'SwitchTemplate',
    $corePath . 'model/switchtemplate/',
    array(
        'core_path' => $corePath
    )
);

/* handle request */
$modx->request->handleRequest(array(
    'processors_path' => $switchtemplate->getOption('processorsPath'),
    'location' => '',
));
