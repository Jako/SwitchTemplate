<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage connector
 *
 * SwitchTemplate connector.
 */
include_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
if (!defined('MODX_CORE_PATH')) {
    include_once dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/config.core.php';
}
if (!defined('MODX_CORE_PATH')) {
    exit('config.core.php not found');
}
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$switchtemplateCorePath = realpath($modx->getOption('switchtemplate.core_path', null, $modx->getOption('core_path') . 'components/switchtemplate/')) . '/';

$modx->getService('switchtemplate', 'SwitchTemplate', $switchtemplateCorePath . 'model/switchtemplate/');

/* handle request */
$path = $modx->getOption('processorsPath', $modx->switchtemplate->config, $switchtemplateCorePath . 'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));
