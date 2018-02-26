SwitchTemplate.panel.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        cls: 'container',
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [{
            html: '<h2>' + _('switchtemplate.management') + '</h2>',
            border: false,
            cls: 'modx-page-header'
        }, {
            layout: 'form',
            cls: 'x-form-label-left',
            defaults: {
                autoHeight: true
            },
            border: true,
            style: 'margin-bottom: 20px',
            items: [{
                html: '<p>' + _('switchtemplate.management_desc') + '</p>',
                border: false,
                bodyCssClass: 'panel-desc'
            }, {
                xtype: 'switchtemplate-grid-setting',
                cls: 'main-wrapper',
                preventRender: true
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
