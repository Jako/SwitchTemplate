<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage build
 *
 * Menus for the SwitchTemplate package.
 */
$menus = array();

$menus[0] = $modx->newObject('modMenu');
$menus[0]->fromArray(array(
    'text' => 'switchtemplate',
    'parent' => 'components',
    'action' => 'settings',
    'description' => 'switchtemplate_desc',
    'icon' => '',
    'params' => '',
    'handler' => '',
    'permissions' => '',
    'namespace' => 'switchtemplate'
), '', true, true);

return $menus;