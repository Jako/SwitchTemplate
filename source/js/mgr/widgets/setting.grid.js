SwitchTemplate.grid.Setting = function (config) {
    config = config || {};
    this.ident = 'switchtemplate-setting-' + Ext.id();
    this.buttonColumnTpl = new Ext.XTemplate('<tpl for=".">'
        + '<tpl if="action_buttons !== null">'
        + '<ul class="action-buttons">'
        + '<tpl for="action_buttons">'
        + '<li><i class="icon {className} icon-{icon}" title="{text}"></i></li>'
        + '</tpl>'
        + '</ul>'
        + '</tpl>'
        + '</tpl>', {
        compiled: true
    });
    Ext.applyIf(config, {
        id: this.ident,
        url: SwitchTemplate.config.connectorUrl,
        baseParams: {
            action: 'mgr/setting/getlist',
            pageSize: 10
        },
        fields: ['id', 'name', 'key', 'extension', 'template', 'templatename', 'type', 'cache', 'include', 'exclude'],
        autoHeight: true,
        paging: true,
        remoteSort: true,
        autoExpandColumn: 'name',
        showActionsColumn: false,
        columns: [{
            header: _('switchtemplate.setting_name'),
            dataIndex: 'name',
            sortable: true,
            width: 100
        }, {
            header: _('switchtemplate.setting_key'),
            dataIndex: 'key',
            sortable: true,
            width: 50
        }, {
            header: _('switchtemplate.setting_templatename'),
            dataIndex: 'templatename',
            renderer: SwitchTemplate.util.htmlRenderer,
            sortable: true,
            width: 120
        }, {
            header: _('switchtemplate.setting_extension'),
            dataIndex: 'extension',
            sortable: true,
            width: 50
        }, {
            header: _('switchtemplate.setting_type_short'),
            dataIndex: 'type',
            sortable: true,
            renderer: function (value) {
                return _('switchtemplate.type_' + value);
            },
            width: 40
        }, {
            header: _('switchtemplate.setting_cache_short'),
            dataIndex: 'cache',
            sortable: true,
            editable: false,
            width: 40,
            editor: {
                xtype: 'combo-boolean',
                renderer: 'boolean'
            }
        }, {
            header: _('id'),
            dataIndex: 'id',
            sortable: true,
            hidden: true
        }, {
            renderer: {
                fn: this.buttonColumnRenderer,
                scope: this
            },
            menuDisabled: true,
            width: 49,
            fixed: true
        }],
        tbar: [{
            text: _('switchtemplate.setting_create'),
            cls: 'primary-button',
            handler: this.createSetting
        }, '->', {
            xtype: 'textfield',
            id: this.ident + '-setting-filter-search',
            emptyText: _('search') + '…',
            submitValue: false,
            listeners: {
                change: {
                    fn: this.search,
                    scope: this
                },
                render: {
                    fn: function (cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER,
                            fn: function () {
                                this.fireEvent('change', this);
                                this.blur();
                                return true;
                            },
                            scope: cmp
                        });
                    },
                    scope: this
                }
            }
        }, {
            xtype: 'button',
            id: this.ident + '-setting-filter-clear',
            cls: 'x-form-filter-clear',
            text: _('filter_clear'),
            listeners: {
                click: {
                    fn: this.clearFilter,
                    scope: this
                }
            }
        }]
    });
    SwitchTemplate.grid.Setting.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.grid.Setting, MODx.grid.Grid, {
    windows: {},
    getMenu: function () {
        var m = [];
        m.push({
            text: _('switchtemplate.setting_update'),
            handler: this.updateSetting
        });
        m.push('-');
        m.push({
            text: _('switchtemplate.setting_remove'),
            handler: this.removeSetting
        });
        this.addContextMenuItem(m);
    },
    createSetting: function (btn, e) {
        this.createUpdateSetting(btn, e, false);
    },
    updateSetting: function (btn, e) {
        this.createUpdateSetting(btn, e, true);
    },
    createUpdateSetting: function (btn, e, isUpdate) {
        var r;
        if (isUpdate) {
            if (!this.menu.record || !this.menu.record.id) {
                return false;
            }
            r = this.menu.record;
        } else {
            r = {};
        }
        var createUpdateSetting = MODx.load({
            xtype: 'switchtemplate-window-setting-create-update',
            isUpdate: isUpdate,
            title: (isUpdate) ? _('switchtemplate.setting_update') : _('switchtemplate.setting_ceate'),
            record: r,
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                },
                beforeSubmit: function () {
                    var f = this.fp.getForm();
                    f.items.each(function (item) {
                        if (item.xtype === 'switchtemplate-combo-resources') {
                            var dataField = f.findField(item.name + 'Data');
                            if (dataField) {
                                dataField.setValue(item.getValue());
                            }
                        }
                    });
                }
            }
        });
        createUpdateSetting.fp.getForm().setValues(r);
        createUpdateSetting.show(e.target);
    },
    removeSetting: function () {
        if (!this.menu.record) {
            return false;
        }
        MODx.msg.confirm({
            title: _('switchtemplate.setting_remove'),
            text: _('switchtemplate.setting_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/setting/remove',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: this.refresh,
                    scope: this
                }
            }
        });
    },
    search: function (tf) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    clearFilter: function () {
        var s = this.getStore();
        s.baseParams.query = '';
        Ext.getCmp(this.ident + '-setting-filter-search').reset();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
    buttonColumnRenderer: function (value, metaData) {
    metaData.css = 'x-grid-cell-icons';
        var values = {
            action_buttons: [
                {
                    className: 'update',
                    icon: 'pencil-square-o',
                    text: _('switchtemplate.setting_update')
                },
                {
                    className: 'remove',
                    icon: 'trash-o',
                    text: _('switchtemplate.setting_remove')
                }
            ]
        };
        return this.buttonColumnTpl.apply(values);
    },
    onClick: function (e) {
        var t = e.getTarget();
        var elm = t.className.split(' ')[0];
        if (elm === 'icon') {
            var act = t.className.split(' ')[1];
            var record = this.getSelectionModel().getSelected();
            this.menu.record = record.data;
            switch (act) {
                case 'remove':
                    this.removeSetting(record, e);
                    break;
                case 'update':
                    this.updateSetting(record, e);
                    break;
                default:
                    break;
            }
        }
    }
});
Ext.reg('switchtemplate-grid-setting', SwitchTemplate.grid.Setting);

SwitchTemplate.window.CreateUpdateSetting = function (config) {
    config = config || {};
    this.ident = 'switchtemplate-setting-create-update-' + Ext.id();
    Ext.applyIf(config, {
        id: this.ident,
        url: SwitchTemplate.config.connectorUrl,
        action: (config.isUpdate) ? 'mgr/setting/update' : 'mgr/setting/create',
        autoHeight: true,
        closeAction: 'close',
        cls: 'modx-window customrequest-window modx' + SwitchTemplate.config.modxversion,
        width: 700,
        fields: [{
            layout: 'column',
            items: [{
                columnWidth: .33,
                layout: 'form',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('switchtemplate.setting_name'),
                    name: 'name',
                    id: this.ident + '-name',
                    anchor: '100%',
                    allowBlank: false
                }]
            }, {
                columnWidth: .33,
                layout: 'form',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('switchtemplate.setting_key'),
                    name: 'key',
                    id: this.ident + '-key',
                    anchor: '100%',
                    allowBlank: false
                }]
            }, {
                columnWidth: .34,
                layout: 'form',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('switchtemplate.setting_extension'),
                    name: 'extension',
                    id: this.ident + '-extension',
                    anchor: '100%'
                }]
            }]
        }, {
            layout: 'column',
            items: [{
                columnWidth: .5,
                layout: 'form',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: _('switchtemplate.setting_templatename'),
                    name: 'template',
                    id: this.ident + '-template',
                    anchor: '100%'
                }]
            }, {
                columnWidth: .25,
                layout: 'form',
                items: [{
                    xtype: 'switchtemplate-combo-type',
                    fieldLabel: _('switchtemplate.setting_type'),
                    value: 'chunk',
                    name: 'type',
                    hiddenName: 'type',
                    id: this.ident + '-type',
                    anchor: '100%',
                    allowBlank: false
                }]
            }, {
                columnWidth: .25,
                layout: 'form',
                items: [{
                    xtype: 'modx-combo-boolean',
                    fieldLabel: _('switchtemplate.setting_cache'),
                    value: '1',
                    name: 'cache',
                    hiddenName: 'cache',
                    id: this.ident + '-cache',
                    anchor: '100%'
                }]
            }]
        }, {
            layout: 'column',
            items: [{
                columnWidth: .5,
                layout: 'form',
                items: [{
                    xtype: 'switchtemplate-combo-resources',
                    fieldLabel: _('switchtemplate.setting_include'),
                    description: MODx.expandHelp ? '' : _('switchtemplate.setting_include_desc'),
                    name: 'include',
                    hiddenName: 'include',
                    id: this.ident + '-include',
                    anchor: '100%'
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: this.ident + '-template',
                    html: _('switchtemplate.setting_include_desc'),
                    cls: 'desc-under'
                }, {
                    xtype: 'textfield',
                    name: 'includeData',
                    id: this.ident + '-includeData',
                    hidden: true
                }]
            }, {
                columnWidth: .5,
                layout: 'form',
                items: [{
                    xtype: 'switchtemplate-combo-resources',
                    fieldLabel: _('switchtemplate.setting_exclude'),
                    description: MODx.expandHelp ? '' : _('switchtemplate.setting_exclude_desc'),
                    name: 'exclude',
                    hiddenName: 'exclude',
                    id: this.ident + '-exclude',
                    anchor: '100%'
                }, {
                    xtype: MODx.expandHelp ? 'label' : 'hidden',
                    forId: this.ident + '-template',
                    html: _('switchtemplate.setting_exclude_desc'),
                    cls: 'desc-under'
                }, {
                    xtype: 'textfield',
                    name: 'excludeData',
                    id: this.ident + '-excludeData',
                    hidden: true
                }]
            }]
        }, {
            xtype: 'hidden',
            name: 'id',
            id: this.ident + '-id',
        }]
    });
    SwitchTemplate.window.CreateUpdateSetting.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.window.CreateUpdateSetting, MODx.Window);
Ext.reg('switchtemplate-window-setting-create-update', SwitchTemplate.window.CreateUpdateSetting);
