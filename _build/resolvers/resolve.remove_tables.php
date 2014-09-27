<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage build
 *
 * Remove Table Resolver for the SwitchTemplate package.
 */
if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_UNINSTALL:
            /** @var modX $modx */
            $modx =& $object->xpdo;

            $modelPath = $modx->getOption('switchtemplate.core_path', null, $modx->getOption('core_path') . 'components/switchtemplate/') . 'model/';
            $modx->addPackage('switchtemplate', $modelPath);

            $manager = $modx->getManager();

            $manager->removeObjectContainer('SwitchtemplateSettings');

            break;
    }
}
return true;