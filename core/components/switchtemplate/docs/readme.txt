SwitchTemplate
==============

Switch resource templates on the fly.

Features
--------
SwitchTemplate changes the template of a MODX resource on the fly with a request
parameter. Different settings with different setting keys could be set in a
custom manager page.

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

Installation
------------
MODX Package Management

Usage
-----
Install via package manager and create template switching settings in a custom
manager page.

Documentation
-------------
For more information please read the documentation on http://jako.github.io/SwitchTemplate/

GitHub Repository
-----------------
https://github.com/Jako/SwitchTemplate
