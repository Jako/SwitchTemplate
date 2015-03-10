SwitchTemplate.combo.Type = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        store: new Ext.data.ArrayStore({
            fields: ['type', 'display'],
            data: [
                ['chunk', _('switchtemplate.type_chunk')],
                ['template', _('switchtemplate.type_template')]
            ]
        }),
        mode: 'local',
        displayField: 'display',
        valueField: 'type',
        editable: false
    });
    SwitchTemplate.combo.Type.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.combo.Type, MODx.combo.ComboBox);
Ext.reg('switchtemplate-combo-type', SwitchTemplate.combo.Type);

SwitchTemplate.combo.Resources = function (config) {
    var resources = new Ext.data.JsonStore({
        id: 'id',
        root: 'results',
        fields: [
            {name: 'id', type: 'int'},
            {name: 'pagetitle', type: 'string'}
        ],
        url: SwitchTemplate.config.connectorUrl,
        pageSize: 10,
        baseParams: {
            action: 'mgr/resources/getlist'
        }
    });

    config = config || {};
    Ext.applyIf(config, {
        pageSize: 10,
        xtype: 'superboxselect',
        store: resources,
        mode: 'remote',
        triggerAction: 'all',
        allowAddNewData: false,
        addNewDataOnBlur: false,
        displayField: 'pagetitle',
        displayFieldTpl: '{pagetitle} ({id})',
        valueField: 'id',
        lazyRender: true,
        editable: true,
        typeAhead: true,
        minChars: 1,
        forceSelection: true,
        classField: 'cls',
        styleField: 'style',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger'
    });
    SwitchTemplate.combo.Resources.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.combo.Resources, Ext.ux.form.SuperBoxSelect);
Ext.reg('switchtemplate-combo-resources', SwitchTemplate.combo.Resources);

SwitchTemplate.grid.Setting = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: 'switchtemplate-grid-setting',
        url: SwitchTemplate.config.connectorUrl,
        baseParams: {
            action: 'mgr/setting/getList'
        },
        save_action: 'mgr/setting/updatefromgrid',
        autosave: true,
        fields: ['id', 'name', 'key', 'template', 'type', 'cache', 'include', 'exclude'],
        autoHeight: true,
        paging: true,
        remoteSort: true,
        autoExpandColumn: 'name',
        columns: [{
            header: _('id'), dataIndex: 'id', sortable: true, editable: true, hidden: true
        }, {
            header: _('switchtemplate.name'),
            dataIndex: 'name',
            sortable: true,
            editable: true,
            editor: {xtype: 'textfield'}
        }, {
            header: _('switchtemplate.key'),
            dataIndex: 'key',
            sortable: true,
            editable: true,
            editor: {xtype: 'textfield'},
            width: 70
        }, {
            header: _('switchtemplate.templatename'),
            dataIndex: 'template',
            sortable: true,
            editable: true,
            editor: {xtype: 'textfield'},
            width: 100
        }, {
            header: _('switchtemplate.type_short'), dataIndex: 'type', sortable: true, width: 30
        }, {
            header: _('switchtemplate.cache_short'),
            dataIndex: 'cache',
            sortable: true,
            width: 30,
            editor: {xtype: 'combo-boolean', renderer: 'boolean'}
        }],
        tbar: [{
            text: _('switchtemplate.setting_create'), cls: 'primary-button', handler: this.createSetting, scope: this
        }, '->', {
            xtype: 'textfield',
            id: 'switchtemplate-search-filter',
            emptyText: _('switchtemplate.search') + '…',
            listeners: {
                'change': {fn: this.search, scope: this}, 'render': {
                    fn: function (cmp) {
                        new Ext.KeyMap(cmp.getEl(), {
                            key: Ext.EventObject.ENTER, fn: function () {
                                this.fireEvent('change', this);
                                this.blur();
                                return true;
                            }, scope: cmp
                        });
                    }, scope: this
                }
            }
        }]
    });
    SwitchTemplate.grid.Setting.superclass.constructor.call(this, config)
};
Ext.extend(SwitchTemplate.grid.Setting, MODx.grid.Grid, {
    windows: {}, getMenu: function () {
        var m = [];
        m.push({
            text: _('switchtemplate.setting_update'), handler: this.updateSetting
        });
        m.push('-');
        m.push({
            text: _('switchtemplate.setting_remove'), handler: this.removeSetting
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
                'success': {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }, 'beforeSubmit': function (o) {
                    var f = this.fp.getForm();
                    f.items.each(function (item) {
                        if (item.xtype == 'switchtemplate-combo-resources') {
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
    removeSetting: function (btn, e) {
        if (!this.menu.record) {
            return false;
        }
        MODx.msg.confirm({
            title: _('switchtemplate.setting_remove'),
            text: _('switchtemplate.setting_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/setting/remove', id: this.menu.record.id
            },
            listeners: {
                'success': {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },
    search: function (tf, nv, ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('switchtemplate-grid-setting', SwitchTemplate.grid.Setting);

SwitchTemplate.window.CreateUpdateSetting = function (config) {
    config = config || {};
    this.ident = config.ident || 'switchtemplate-mecitem' + Ext.id();
    Ext.applyIf(config, {
        id: this.ident,
        url: SwitchTemplate.config.connectorUrl,
        action: (config.isUpdate) ? 'mgr/setting/update' : 'mgr/setting/create',
        fields: [{
            xtype: 'textfield',
            fieldLabel: _('switchtemplate.name'),
            name: 'name',
            id: this.ident + '-name',
            anchor: '100%',
            allowBlank: false
        }, {
            xtype: 'textfield',
            fieldLabel: _('switchtemplate.key'),
            name: 'key',
            id: this.ident + '-key',
            anchor: '100%',
            allowBlank: false
        }, {
            xtype: 'textfield',
            fieldLabel: _('switchtemplate.templatename'),
            name: 'template',
            id: this.ident + '-template',
            anchor: '100%',
            allowBlank: false
        }, {
            xtype: 'switchtemplate-combo-type',
            fieldLabel: _('switchtemplate.type'),
            value: 'chunk',
            name: 'type',
            hiddenName: 'type',
            id: this.ident + '-type',
            anchor: '100%',
            allowBlank: false
        }, {
            xtype: 'modx-combo-boolean',
            fieldLabel: _('switchtemplate.cache'),
            value: '1',
            name: 'cache',
            hiddenName: 'cache',
            id: this.ident + '-cache',
            anchor: '100%'
        }, {
            xtype: 'switchtemplate-combo-resources',
            fieldLabel: _('switchtemplate.include'),
            name: 'include',
            hiddenName: 'include',
            id: this.ident + '-include',
            anchor: '100%'
        }, {
            xtype: 'switchtemplate-combo-resources',
            fieldLabel: _('switchtemplate.exclude'),
            name: 'exclude',
            hiddenName: 'exclude',
            id: this.ident + '-exclude',
            anchor: '100%'
        }, {
            xtype: 'textfield',
            name: 'includeData',
            id: this.ident + '-includeData',
            hidden: true
        }, {
            xtype: 'textfield',
            name: 'excludeData',
            id: this.ident + '-excludeData',
            hidden: true
        }, {
            xtype: 'textfield',
            name: 'id',
            id: this.ident + '-id',
            hidden: true
        }]
    });
    SwitchTemplate.window.CreateUpdateSetting.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.window.CreateUpdateSetting, MODx.Window);
Ext.reg('switchtemplate-window-setting-create-update', SwitchTemplate.window.CreateUpdateSetting);

