var switchtemplate = function (config) {
    config = config || {};
    switchtemplate.superclass.constructor.call(this, config);
};
Ext.extend(switchtemplate, Ext.Component, {
    initComponent: function () {
        this.stores = {};
        this.ajax = new Ext.data.Connection({
            disableCaching: true,
        });
    }, page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, util: {}, form: {}
});
Ext.reg('switchtemplate', switchtemplate);

SwitchTemplate = new switchtemplate();

MODx.config.help_url = 'https://jako.github.io/SwitchTemplate/usage/';
