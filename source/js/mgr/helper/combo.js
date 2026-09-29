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
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'superboxselect',
        triggerAction: 'all',
        mode: 'remote',
        valueField: 'id',
        displayField: 'pagetitle',
        displayFieldTpl: '{pagetitle} ({id})',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        lazyRender: true,
        editable: true,
        typeAhead: true,
        minChars: 1,
        forceSelection: true,
        store: new Ext.data.JsonStore({
            root: 'results',
            totalProperty: 'total',
            idProperty: 'id',
            fields: ['id', 'pagetitle'],
            url: SwitchTemplate.config.connectorUrl,
            baseParams: {
                action: 'mgr/resources/getlist'
            }
        })
    });
    SwitchTemplate.combo.Resources.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.combo.Resources, Ext.ux.form.SuperBoxSelect);
Ext.reg('switchtemplate-combo-resources', SwitchTemplate.combo.Resources);

SwitchTemplate.combo.Templates = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'superboxselect',
        triggerAction: 'all',
        mode: 'remote',
        valueField: 'id',
        displayField: 'templatename',
        displayFieldTpl: '{templatename} ({id})',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        lazyRender: true,
        editable: true,
        typeAhead: true,
        minChars: 1,
        forceSelection: true,
        store: new Ext.data.JsonStore({
            root: 'results',
            totalProperty: 'total',
            idProperty: 'id',
            fields: ['id', 'templatename'],
            url: SwitchTemplate.config.connectorUrl,
            baseParams: {
                action: 'mgr/templates/getlist'
            }
        })
    });
    SwitchTemplate.combo.Templates.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.combo.Templates, Ext.ux.form.SuperBoxSelect);
Ext.reg('switchtemplate-combo-templates', SwitchTemplate.combo.Templates);

SwitchTemplate.combo.Contexts = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'superboxselect',
        triggerAction: 'all',
        mode: 'remote',
        valueField: 'key',
        displayField: 'name',
        displayFieldTpl: '{name} ({key})',
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger',
        lazyRender: true,
        editable: true,
        typeAhead: true,
        minChars: 1,
        forceSelection: true,
        store: new Ext.data.JsonStore({
            root: 'results',
            totalProperty: 'total',
            idProperty: 'key',
            fields: ['key', 'name'],
            url: SwitchTemplate.config.connectorUrl,
            baseParams: {
                action: 'mgr/contexts/getlist'
            }
        })
    });
    SwitchTemplate.combo.Contexts.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.combo.Contexts, Ext.ux.form.SuperBoxSelect);
Ext.reg('switchtemplate-combo-contexts', SwitchTemplate.combo.Contexts);
