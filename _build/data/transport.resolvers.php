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

/* create the plugin object */
$resolvers[0] = array(
    'source' => $sources['resolvers'] . 'resolve.remove_tables.php',
);
$resolvers[1] = array(
    'source' => $sources['resolvers'] . 'resolve.tables.php',
);

return $resolvers;
