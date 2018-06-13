SwitchTemplate.panel.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        cls: 'container home-panel'+ ((SwitchTemplate.config.debug) ? ' debug' : ''),
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [{
            html: '<h2>' + _('switchtemplate') + '</h2>' + ((SwitchTemplate.config.debug) ? '<div class="ribbon top-right"><span>' + _('switchtemplate.debug_mode') + '</span></div>' : ''),
            border: false,
            cls: 'modx-page-header'
        }, {
            defaults: {
                autoHeight: true
            },
            border: true,
            items: [{
                xtype: 'modx-tabs',
                deferredRender: false,
                forceLayout: true,
                defaults: {
                    layout: 'form',
                    autoHeight: true,
                    hideMode: 'offsets'
                },
                items: [{
                    xtype: 'switchtemplate-panel-settings'
                }]
            }]
        }, {
            cls: "treehillstudio_about",
            html: '<img width="133" height="40" src="' + SwitchTemplate.config.assetsUrl + 'img/treehill-studio-small.png"' + ' srcset="' + SwitchTemplate.config.assetsUrl + 'img/treehill-studio-small@2x.png 2x" alt="Treehill Studio">',
            listeners: {
                afterrender: function (component) {
                    component.getEl().select('img').on('click', function () {
                        var msg = '<span style="display: inline-block; text-align: center"><img src="' + SwitchTemplate.config.assetsUrl + 'img/treehill-studio.png" srcset="' + SwitchTemplate.config.assetsUrl + 'img/treehill-studio@2x.png 2x" alt"Treehill Studio"><br>' +
                            '© 2014-2018 by <a href="https://treehillstudio.com" target="_blank">treehillstudio.com</a></span>';
                        Ext.Msg.show({
                            title: _('switchtemplate') + ' ' + SwitchTemplate.config.version,
                            msg: msg,
                            buttons: Ext.Msg.OK,
                            cls: 'treehillstudio_window',
                            width: 330
                        });
                    });
                }
            }
        }]
    });
    SwitchTemplate.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.panel.Home, MODx.Panel);
Ext.reg('switchtemplate-panel-home', SwitchTemplate.panel.Home);

SwitchTemplate.panel.Settings = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'switchtemplate-panel-settings',
        title: _('switchtemplate.settings'),
        items: [{
            html: '<p>' + _('switchtemplate.settings_desc') + '</p>',
            border: false,
            bodyCssClass: 'panel-desc'
        }, {
            cls: 'main-wrapper',
            items: [{
                layout: 'form',
                id: 'switchtemplate-panel-setting-grid',
                defaults: {
                    border: false,
                    autoHeight: true
                },
                border: true,
                items: [{
                    xtype: 'switchtemplate-grid-setting',
                    preventRender: true
                }]
            }]
        }]
    });
    SwitchTemplate.panel.Settings.superclass.constructor.call(this, config);
}
;
Ext.extend(SwitchTemplate.panel.Settings, MODx.Panel);
Ext.reg('switchtemplate-panel-settings', SwitchTemplate.panel.Settings);
