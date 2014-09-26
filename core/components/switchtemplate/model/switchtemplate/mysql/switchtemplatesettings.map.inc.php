<?php
$xpdo_meta_map['SwitchtemplateSettings'] = array(
    'package' => 'switchtemplate',
    'version' => NULL,
    'table' => 'switchtemplate_settings',
    'extends' => 'xPDOSimpleObject',
    'fields' =>
        array(
            'name' => NULL,
            'key' => NULL,
            'template' => NULL,
            'type' => NULL,
            'cache' => 1,
            'include' => NULL,
            'exclude' => NULL,
        ),
    'fieldMeta' =>
        array(
            'name' =>
                array(
                    'dbtype' => 'varchar',
                    'precision' => '100',
                    'phptype' => 'string',
                    'null' => false,
                ),
            'key' =>
                array(
                    'dbtype' => 'varchar',
                    'precision' => '50',
                    'phptype' => 'string',
                    'null' => false,
                ),
            'template' =>
                array(
                    'dbtype' => 'varchar',
                    'precision' => '100',
                    'phptype' => 'string',
                    'null' => false,
                ),
            'type' =>
                array(
                    'dbtype' => 'varchar',
                    'precision' => '10',
                    'phptype' => 'string',
                    'null' => false,
                ),
            'cache' =>
                array(
                    'dbtype' => 'tinyint',
                    'precision' => '1',
                    'attributes' => 'unsigned',
                    'phptype' => 'integer',
                    'null' => false,
                    'default' => 1,
                ),
            'include' =>
                array(
                    'dbtype' => 'text',
                    'phptype' => 'string',
                    'null' => true,
                ),
            'exclude' =>
                array(
                    'dbtype' => 'text',
                    'phptype' => 'string',
                    'null' => true,
                ),
        ),
);
