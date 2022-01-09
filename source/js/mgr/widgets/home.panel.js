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
                xtype: 'switchtemplate-panel-overview'
            }]
        }, {
            cls: "treehillstudio_about",
            html: '<img width="146" height="40" src="' + SwitchTemplate.config.assetsUrl + 'img/mgr/treehill-studio-small.png"' + ' srcset="' + SwitchTemplate.config.assetsUrl + 'img/mgr/treehill-studio-small@2x.png 2x" alt="Treehill Studio">',
            listeners: {
                afterrender: function () {
                    this.getEl().select('img').on('click', function () {
                        var msg = '<span style="display: inline-block; text-align: center"><img src="' + SwitchTemplate.config.assetsUrl + 'img/mgr/treehill-studio.png" srcset="' + SwitchTemplate.config.assetsUrl + 'img/mgr/treehill-studio@2x.png 2x" alt="Treehill Studio"><br>' +
                            '&copy; 2014-2020 by <a href="https://treehillstudio.com" target="_blank">treehillstudio.com</a></span>';
                        Ext.Msg.show({
                            title: _('switchtemplate') + ' ' + SwitchTemplate.config.version,
                            msg: msg,
                            buttons: Ext.Msg.OK,
                            cls: 'treehillstudio_window',
                            width: 358
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

SwitchTemplate.panel.HomeTab = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'switchtemplate-panel-' + config.tabtype,
        title: _('switchtemplate.' + config.tabtype),
        items: [{
            html: '<p>' + _('switchtemplate.' + config.tabtype + '_desc') + '</p>',
            border: false,
            cls: 'panel-desc'
        }, {
            layout: 'form',
            cls: 'x-form-label-left main-wrapper',
            defaults: {
                autoHeight: true
            },
            border: true,
            items: [{
                id: 'switchtemplate-panel-' + config.tabtype + '-grid',
                xtype: 'switchtemplate-grid-' + config.tabtype,
                preventRender: true
            }]
        }]
    });
    SwitchTemplate.panel.HomeTab.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.panel.HomeTab, MODx.Panel);
Ext.reg('switchtemplate-panel-hometab', SwitchTemplate.panel.HomeTab);

SwitchTemplate.panel.Overview = function (config) {
    config = config || {};
    this.ident = 'switchtemplate-panel-overview' + Ext.id();
    this.panelOverviewTabs = [{
        xtype: 'switchtemplate-panel-hometab',
        tabtype: 'setting'
    }];
    if (SwitchTemplate.config.is_admin) {
        this.panelOverviewTabs.push({
            xtype: 'switchtemplate-panel-settings',
            tabtype: 'settings'
        })
    }
    Ext.applyIf(config, {
        id: this.ident,
        items: [{
            xtype: 'modx-tabs',
            stateful: true,
            stateId: 'switchtemplate-panel-overview',
            stateEvents: ['tabchange'],
            getState: function () {
                return {
                    activeTab: this.items.indexOf(this.getActiveTab())
                };
            },
            autoScroll: true,
            deferredRender: false,
            forceLayout: true,
            defaults: {
                layout: 'form',
                autoHeight: true,
                hideMode: 'offsets'
            },
            items: this.panelOverviewTabs,
            listeners: {
                tabchange: function (o, t) {
                    if (t.tabtype === 'settings') {
                        Ext.getCmp('switchtemplate-grid-system-settings').getStore().reload();
                    } else if (t.xtype === 'switchtemplate-panel-hometab') {
                        if (Ext.getCmp('switchtemplate-panel-' + t.tabtype + '-grid')) {
                            Ext.getCmp('switchtemplate-panel-' + t.tabtype + '-grid').getStore().reload();
                        }
                    }
                }
            }
        }]
    });
    SwitchTemplate.panel.Overview.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.panel.Overview, MODx.Panel);
Ext.reg('switchtemplate-panel-overview', SwitchTemplate.panel.Overview);
