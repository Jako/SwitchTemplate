<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage build
 *
 * Plugins for the SwitchTemplate package.
 */
$plugins = array();

/* create the plugin object */
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id',1);
$plugins[0]->set('name','SwitchTemplate');
$plugins[0]->set('description','Switch resource templates on the fly.');
$plugins[0]->set('plugincode', getSnippetContent($sources['plugins'] . 'switchtemplate.plugin.php'));
$plugins[0]->set('category', 0);

$events = array();

$e = array(
    'OnLoadWebPageCache',
    'OnLoadWebDocument'
);

foreach ($e as $ev) {
    $events[$ev] = $modx->newObject('modPluginEvent');
    $events[$ev]->fromArray(array(
        'event' => $ev,
        'priority' => 0,
        'propertyset' => 0
    ),'',true,true);
}

if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' plugin events for SwitchTemplate.');
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not find plugin events for SwitchTemplate!');
}
unset($events);

return $plugins;