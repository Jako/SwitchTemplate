SwitchTemplate.panel.Settings = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'switchtemplate-panel-settings',
        title: _('switchtemplate.settings'),
        items: [{
            html: '<p>' + _('switchtemplate.settings_desc') + '</p>',
            border: false,
            cls: 'panel-desc'
        }, {
            xtype: 'switchtemplate-grid-system-settings',
            id: 'switchtemplate-grid-system-settings',
            cls: 'main-wrapper',
            preventSaveRefresh: true
        }]
    });
    SwitchTemplate.panel.Settings.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.panel.Settings, MODx.Panel);
Ext.reg('switchtemplate-panel-settings', SwitchTemplate.panel.Settings);

SwitchTemplate.grid.SystemSettings = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'switchtemplate-grid-systemsettings',
        url: SwitchTemplate.config.connectorUrl,
        baseParams: {
            action: 'mgr/settings/getlist',
            area: MODx.request.area || ''
        },
        save_action: 'mgr/settings/updatefromgrid',
        tbar: [],
        queryParam: (SwitchTemplate.config.modxversion >= 3) ? 'query' : 'key'
    });
    SwitchTemplate.grid.SystemSettings.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.grid.SystemSettings, MODx.grid.SettingsGrid, {
    _showMenu: function (g, ri, e) {
        e.stopEvent();
        e.preventDefault();
        this.menu.record = this.getStore().getAt(ri).data;
        if (!this.getSelectionModel().isSelected(ri)) {
            this.getSelectionModel().selectRow(ri);
        }
        this.menu.removeAll();
        var m = [];
        if (this.menu.record.menu) {
            m = this.menu.record.menu;
        } else {
            m.push({
                text: _('setting_update') || _('edit'),
                handler: this.updateSetting
            });
        }
        if (m.length > 0) {
            this.addContextMenuItem(m);
            this.menu.showAt(e.xy);
        }
    },
    updateSetting: function (btn, e) {
        var r = this.menu.record;
        r.fk = Ext.isDefined(this.config.fk) ? this.config.fk : 0;
        var uss = MODx.load({
            xtype: 'modx-window-setting-update',
            url: SwitchTemplate.config.connectorUrl,
            action: 'mgr/settings/update',
            record: r,
            grid: this,
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    },
                    scope: this
                }
            }
        });
        uss.reset();
        uss.setValues(r);
        uss.show(e.target);
    },
    clearFilter: function () {
        var area = MODx.request.area || '';
        this.getStore().baseParams = this.initialConfig.baseParams;
        var acb = Ext.getCmp('modx-filter-area');
        if (acb) {
            acb.store.load();
            acb.reset();
        }
        Ext.getCmp('modx-filter-' + this.config.queryParam).reset();
        this.getStore().baseParams.area = area;
        this.getStore().baseParams[this.config.queryParam] = '';
        this.getBottomToolbar().changePage(1);
    },
    filterByKey: function (tf, newValue) {
        this.getStore().baseParams[this.config.queryParam] = newValue;
        this.getBottomToolbar().changePage(1);
        return true;
    },
    filterByNamespace: function () {
        this.getStore().baseParams.area = '';
        this.getBottomToolbar().changePage(1);
        var acb = Ext.getCmp('modx-filter-area');
        if (acb) {
            var s = acb.store;
            s.removeAll();
            s.load();
            acb.setValue('');
        }
    },
    listeners: {
        afterrender: function () {
            Ext.getCmp('modx-filter-namespace').hide();
        }
    }
});
Ext.reg('switchtemplate-grid-system-settings', SwitchTemplate.grid.SystemSettings);
