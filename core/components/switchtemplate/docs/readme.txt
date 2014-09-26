SwitchTemplate
================================================================================

Switch resource templates on the fly.

Features:
--------------------------------------------------------------------------------
SwitchTemplate changes the template of a MODX resource on the fly with a request
parameter. Different settings with different setting keys could be set in a
custom manager page.

With each setting a different template could be used to output the resource.
As well the template type could be set to chunk or template. If the
template type is chunk the resource variables and template variables are
prepared as placeholder before the chunk is parsed. The caching of the output
of SwitchTemplate could be enabled for each separate setting. The output is
cached separate for each resource and each setting. Each setting could be
enabled or disabled for selected resources.

SwitchTemplate could be used for different purposes: As Ajax connector, as
language switcher ...

Installation:
--------------------------------------------------------------------------------
MODX Package Management

Usage
--------------------------------------------------------------------------------
SwitchTemplate has its own manager page and uses the following system settings

switchtemplate.mode_key
(string) Request key used to switch the custom template chunks.
Default: mode

switchtemplate.cache_resource_key
(string) The key identifying a cache handler for SwitchTemplate to use.
Default: resource

switchtemplate.cache_resource_handler
(string) The class of cache handler for SwitchTemplate to use.
Default: xPDOFileCache

switchtemplate.cache_esource_expires
(int) The cache expiration time in seconds for custom template output.
0 means indefinitely or until the cache items are purposely cleared.
Default: 0

Example
--------------------------------------------------------------------------------
If you create the following setting

Setting Name: Test
Setting Key: test
Chunk/Template Name: switchTest
Template Type: Chunk
Cache the Output: Yes
Enabled Resources (Default 'All'): -
Disabled Resources (Default 'None'): -

you could call every page in your installation with the url parameter mode=test
(i.e. your.own.domain/?mode=test) and the page is shown with the template chunk
switchTest.