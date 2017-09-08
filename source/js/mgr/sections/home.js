SwitchTemplate.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        formpanel: 'switchtemplate-panel-home',
        components: [{
            xtype: 'switchtemplate-panel-home'
        }]
    });
    SwitchTemplate.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.page.Home, MODx.Component);
Ext.reg('switchtemplate-page-home', SwitchTemplate.page.Home);
