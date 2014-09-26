<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage build
 *
 * File Resolvers for the SwitchTemplate package.
 */
$files = array();

if (is_dir($sources['source_assets'])) {
    $files['assets'] = array(
        'source' => $sources['source_assets'],
        'target' => "return MODX_ASSETS_PATH . 'components/';",
    );
}
if (is_dir($sources['source_core'])) {
    $files['core'] = array(
        'source' => $sources['source_core'],
        'target' => "return MODX_CORE_PATH . 'components/';",
    );
}

return $files;
