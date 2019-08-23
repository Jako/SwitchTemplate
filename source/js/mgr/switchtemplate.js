var switchtemplate = function (config) {
    config = config || {};
    Ext.applyIf(config, {});
    switchtemplate.superclass.constructor.call(this, config);
    return this;
};
Ext.extend(switchtemplate, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, util: {}
});
Ext.reg('switchtemplate', switchtemplate);

SwitchTemplate = new switchtemplate();
