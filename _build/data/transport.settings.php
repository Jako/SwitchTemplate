<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage build
 *
 * System settings for the SwitchTemplate package.
 */
$settings = array();

$settings['switchtemplate.mode_key'] = $modx->newObject('modSystemSetting');
$settings['switchtemplate.mode_key']->fromArray(array(
    'key' => 'switchtemplate.mode_key',
    'value' => 'mode',
    'namespace' => 'switchtemplate',
    'area' => 'site',
), '', true, true);
$settings['switchtemplate.cache_resource_key'] = $modx->newObject('modSystemSetting');
$settings['switchtemplate.cache_resource_key']->fromArray(array(
    'key' => 'switchtemplate.cache_resource_key',
    'value' => 'resource',
    'namespace' => 'switchtemplate',
    'area' => 'site',
), '', true, true);
$settings['switchtemplate.cache_resource_handler'] = $modx->newObject('modSystemSetting');
$settings['switchtemplate.cache_resource_handler']->fromArray(array(
    'key' => 'switchtemplate.cache_resource_handler',
    'value' => 'xPDOFileCache',
    'namespace' => 'switchtemplate',
    'area' => 'site',
), '', true, true);
$settings['switchtemplate.cache_resource_expires'] = $modx->newObject('modSystemSetting');
$settings['switchtemplate.cache_resource_expires']->fromArray(array(
    'key' => 'switchtemplate.cache_resource_expires',
    'value' => '0',
    'namespace' => 'switchtemplate',
    'area' => 'site',
), '', true, true);

return $settings;