SwitchTemplate.panel.Settings = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        cls: 'container',
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [{
            html: '<h2>' + _('switchtemplate.management') + '</h2>',
            cls: 'modx-page-header',
            border: false
        }, {
            layout: 'form',
            cls: 'x-form-label-left',
            defaults: {
                border: false,
                autoHeight: true
            },
            border: true,
            items: [{
                html: '<p>' + _('switchtemplate.management_desc') + '</p>',
                border: false,
                bodyCssClass: 'panel-desc'
            }, {
                xtype: 'switchtemplate-grid-setting',
                cls: 'main-wrapper',
                preventRender: true
            }]
        }]
    });
    SwitchTemplate.panel.Settings.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.panel.Settings, MODx.Panel);
Ext.reg('switchtemplate-panel-settings', SwitchTemplate.panel.Settings);
