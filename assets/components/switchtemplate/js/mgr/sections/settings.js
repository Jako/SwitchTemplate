Ext.onReady(function () {
    MODx.load({xtype: 'switchtemplate-page-settings'});
});

SwitchTemplate.page.Settings = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'switchtemplate-panel-settings'
        }]
    });
    SwitchTemplate.page.Settings.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.page.Settings, MODx.Component);
Ext.reg('switchtemplate-page-settings', SwitchTemplate.page.Settings);
