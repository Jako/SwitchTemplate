SwitchTemplate = function (config, getStore) {
    config = config || {};
    Ext.applyIf(config, {});
    SwitchTemplate.superclass.constructor.call(this, config);
    return this;
};
Ext.extend(SwitchTemplate, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}
});
Ext.reg('switchtemplate', SwitchTemplate);
SwitchTemplate = new SwitchTemplate();