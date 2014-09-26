<?php
/**
 * SwitchTemplate
 *
 * Copyright 2014 by Thomas Jakobi <thomas.jakobi@partout.info>
 *
 * @package switchtemplate
 * @subpackage lexicon
 *
 * Default English Lexicon Entries for the SwitchTemplate package.
 */
$_lang['switchtemplate'] = 'SwitchTemplate';
$_lang['switchtemplate_desc'] = 'Switch resource templates on the fly.';

$_lang['setting_switchtemplate.mode_key'] = 'Request key';
$_lang['setting_switchtemplate.mode_key_desc'] = 'Request key used to switch the custom templates.';
$_lang['setting_switchtemplate.cache_resource_key'] = 'Key identifying a cache handler';
$_lang['setting_switchtemplate.cache_resource_key_desc'] = 'The key identifying a cache handler for SwitchTemplate to use.';
$_lang['setting_switchtemplate.cache_resource_handler'] = 'Class of cache handler';
$_lang['setting_switchtemplate.cache_resource_handler_desc'] = 'The class of cache handler for SwitchTemplate to use.';
$_lang['setting_switchtemplate.cache_resource_expires'] = 'Cache expiration time in seconds';
$_lang['setting_switchtemplate.cache_resource_expires_desc'] = 'The cache expiration time in seconds for custom template output. 0 means indefinitely or until the cache items are purposely cleared.';

$_lang['switchtemplate.setting_ceate'] = 'Create';
$_lang['switchtemplate.setting_update'] = 'Update';
$_lang['switchtemplate.setting_remove'] = 'Delete';
$_lang['switchtemplate.setting_remove_confirm'] = 'Are you sure you want to delete this SwitchTemplate Setting?';
$_lang['switchtemplate.management'] = 'SwitchTemplate';
$_lang['switchtemplate.management_desc'] = 'On this page you could manage the SwitchTemplate settings that are used to change the the template of a MODX resource on the fly.';
$_lang['switchtemplate.setting_create'] = 'New Setting';
$_lang['switchtemplate.search'] = 'Search';
$_lang['switchtemplate.name'] = 'Setting Name';
$_lang['switchtemplate.key'] = 'Setting Key';
$_lang['switchtemplate.type'] = 'Template Type';
$_lang['switchtemplate.type_short'] = 'Type';
$_lang['switchtemplate.templatename'] = 'Chunk/Template Name';
$_lang['switchtemplate.cache'] = 'Cache the Output';
$_lang['switchtemplate.cache_short'] = 'Cache';
$_lang['switchtemplate.include'] = 'Enabled Resources (Default \'All\')';
$_lang['switchtemplate.exclude'] = 'Disabled Resources (Default \'None\')';
$_lang['switchtemplate.resource_empty'] = 'Select Ressources';
$_lang['switchtemplate.type_empty'] = 'Select Template Type';
$_lang['switchtemplate.type_chunk'] = 'Chunk';
$_lang['switchtemplate.type_template'] = 'Template';
$_lang['switchtemplate.chunk_not_found'] = 'Chunk \'[[+name]]\' is not found.';
$_lang['switchtemplate.template_not_found'] = 'Template \'[[+name]]\' is not found.';

$_lang['switchtemplate.redirect_err_invalid'] = 'Invalid data.';
$_lang['switchtemplate.redirect_err_nf'] = 'API User not found.';
$_lang['switchtemplate.redirect_err_ns'] = 'API User not specified.';
$_lang['switchtemplate.redirect_err_save'] = 'An error occurred while trying to save the API user.';
