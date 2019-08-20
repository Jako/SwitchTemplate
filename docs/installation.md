## Install from MODX Extras

Search for SwitchTemplate in the Package Manager of a MODX installation and
install it in there.

## Manual installation

If you can't access the MODX Extras Repository in your MODX installation, you
can manually install SwitchTemplate.

* Download the transport package from [MODX Extras](https://modx.com/extras/package/switchtemplate) (or one of the pre built transport packages in [_packages](https://github.com/Jako/SwitchTemplate/tree/master/_packages))
* Upload the zip file to your MODX installation's `core/packages` folder or upload it manually in the MODX Package Manager.
* In the MODX Manager, navigate to the Package Manager page, and select 'Search locally for packages' from the dropdown button.
* SwitchTemplate should now show up in the list of available packages. Click the corresponding 'Install' button and follow instructions to complete the installation.

## Build it from source

To build and install the package from source you could use [Git Package
Management](https://github.com/TheBoxer/Git-Package-Management). The GitHub
repository of SwitchTemplate contains a
[config.json](https://github.com/Jako/SwitchTemplate/blob/master/_build/config.json)
to build that package locally. Use this option, if you want to debug
SwitchTemplate and/or contribute bugfixes and enhancements.

After downloading the github repository, you have to change into the
`core/components/switchtemplate` folder and install mpdf with composer by invoking
`composer install`.
