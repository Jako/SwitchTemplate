<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage build
 *
 * Resolvers for the SwitchTemplate package.
 */
$resolvers = array();

/* create the resolvers array */
$resolvers[] = array(
    'type' => 'php',
    'resolver' => array(
        'source' => $sources['resolvers'] . 'resolve.remove_tables.php'
    )
);
if (is_dir($sources['source_assets'])) {
    $resolvers[] = array(
        'type' => 'file',
        'resolver' => array(
            'source' => $sources['source_assets'],
            'target' => "return MODX_ASSETS_PATH . 'components/';")
    );
}
if (is_dir($sources['source_core'])) {
    $resolvers[] = array(
        'type' => 'file',
        'resolver' => array(
            'source' => $sources['source_core'],
            'target' => "return MODX_CORE_PATH . 'components/';")
    );
}
$resolvers[] = array(
    'type' => 'php',
    'resolver' => array(
        'source' => $sources['resolvers'] . 'resolve.tables.php')
);

return $resolvers;
