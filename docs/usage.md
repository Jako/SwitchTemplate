## How it works

SwitchTemplate template switching plugin for MODx Revolution. It changes the
template of a MODX resource on the fly with a request parameter. Different
settings with different setting keys could be set in a custom manager page.

With each setting a different template could be used to output the resource. As
well the template type could be set to chunk or template. If the template type
is chunk the resource variables and template variables are prepared as
placeholder before the chunk is parsed. The caching of the output of
SwitchTemplate could be enabled for each separate setting. The output is cached
separate for each resource and each setting. Each setting could be enabled or
disabled for selected resources. The output could be filtered to valid AMP
markup.

SwitchTemplate could be used for different purposes: As Ajax connector, as
language switcher, AMP output ...

## System Settings

SwitchTemplate uses the following system settings in the namespace `switchtemplate`:

Setting | Description | Default
--------|-------------|--------
allow_debug_info | Enable this setting to show switchtemplate debug information for a page if it is called with the GET parameter &switchtemplate-debug=1. CAUTION: Disable the setting after you have debugged your installation. The debug information exposes a lot information of your site! | No
cache_resource_expires | The cache expiration time in seconds for custom template output. 0 means indefinitely or until the cache items are purposely cleared. | 0
cache_resource_handler | The class of cache handler for SwitchTemplate to use. | xPDOFileCache
cache_resource_key | The key identifying a cache handler for SwitchTemplate to use. | resource
debug | Log debug information in the MODX error log. | No
mode_key | Request key used to switch the custom template chunks. | mode
mode_tv | Name of a template variable that contains a comma separated list of allowed switch modes. | -

## Custom Manager Page

SwitchTemplate could be configured by settings created in a custom manager page.
Each setting could use the following properties:

Property | Description
---------|------
Name | A name to identify this configuration.
Key | The request key value that selects this setting.
Extension | A file extension (including the dot), that selects this setting.
Chunk/Template Name | The name of a chunk or a template that is parsed and displayed if this setting is used. The name of the chunk is variable by using MODX placeholders. The placeholders are filled with the values of the current resource (no template variables). If this chunk is not found, a chunkname with stripped placeholders is used. If this field is empty, the name of the original template is used with the setting key as suffix (separated with a space). In this case the field value in the grid is green.
Template Type | The template type that is parsed and displayed (`Chunk` or `Template`).
Cache the Output | Cache the output of the switched template.
Output Type | The output for filtering the template output (`HTML` or `AMP`)[^1]. 
Enabled Resources (Default 'All') | A list of MODX resources that could have a switched template.
Disabled Resources (Default 'None') | A list of MODX resources that could not have a switched template.

### Example

If you create the following setting in the custom manager page, you could call
every page in your installation with the url parameter mode=amp (i.e.
`https://your.own.domain/?mode=amp`) or with the file extension .amp.html (i.e.
`https://your.own.domain/index.amp.html`) and the page is shown with the template
chunk `switchTest` and is filtered to valid AMP markup.

Property | Value
---------|------
Name | Test
Key | amp
Extension | .amp.html
Chunk/Template Name | switchTest
Template Type | Chunk
Cache the Output | Yes
Output Type | AMP
Enabled Resources (Default 'All') | -
Disabled Resources (Default 'None') | -

[^1]: The `AMP` output type will filter the resource output to valid [AMP](https://www.ampproject.org) markup using the [AMP PHP Library](https://github.com/Lullabot/amp-library). &lt;img&gt; tags with locally referenced images receive image width and height attributes before to avoid [openssl issues](https://github.com/Lullabot/amp-library#caveats-and-known-issues) with PHP 5.6+.
