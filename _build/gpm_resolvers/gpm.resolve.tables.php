<?php
/**
 * Resolve creating db tables
 *
 * THIS RESOLVER IS AUTOMATICALY GENERATED, NO CHANGES WILL APPLY
 *
 * @package switchtemplate
 * @subpackage build
 */

if ($object->xpdo) {
    $modx =& $object->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modelPath = $modx->getOption('switchtemplate.core_path', null, $modx->getOption('core_path') . 'components/switchtemplate/') . 'model/';
            $modx->addPackage('switchtemplate', $modelPath);

            $manager = $modx->getManager();

            $manager->createObjectContainer('SwitchtemplateSettings');

            break;
    }
}

return true;