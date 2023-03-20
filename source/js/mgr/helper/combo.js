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
        fields: [{
            name: 'id',
            type: 'int'
        }, {
            name: 'pagetitle',
            type: 'string'
        }],
        pageSize: 10,
        url: SwitchTemplate.config.connectorUrl,
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
        displayField: 'pagetitle',
        displayFieldTpl: '{pagetitle} ({id})',
        valueField: 'id',
        lazyRender: true,
        editable: true,
        typeAhead: true,
        minChars: 1,
        forceSelection: true,
        extraItemCls: 'x-tag',
        expandBtnCls: 'x-form-trigger',
        clearBtnCls: 'x-form-trigger'
    });
    SwitchTemplate.combo.Resources.superclass.constructor.call(this, config);
};
Ext.extend(SwitchTemplate.combo.Resources, Ext.ux.form.SuperBoxSelect);
Ext.reg('switchtemplate-combo-resources', SwitchTemplate.combo.Resources);

