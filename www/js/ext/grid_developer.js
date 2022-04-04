

Ext.ns('App', 'App.developer');

App.developer.Grid = Ext.extend(
    Ext.grid.EditorGridPanel, 
    {
    renderTo: 'developer-grid',
    iconCls: 'silk-grid',
    frame: true,
    title: 'Разработчики',
    height: def_height,
    //width: 500,
    //autoHeight:true,
    autoWidth:true,
    //autoDestroy:true,
    style: 'margin-top: 10px',
    buttonAlign: 'left',
    initComponent : function() {

        // typical viewConfig
        this.viewConfig = {
            forceFit: true
        };

        // relay the Store's CRUD events into this grid so these events can be conveniently listened-to in our application-code.
        this.relayEvents(this.store, ['destroy', 'save', 'update']);

        // build toolbars and buttons.
        this.tbar = this.buildTopToolbar();
        //this.bbar = this.buildBottomToolbar();
        //this.buttons = this.buildUI();

        // super
        App.developer.Grid.superclass.initComponent.call(this);
    },

    /**
     * buildTopToolbar
     */
    buildTopToolbar : function() {
        return [{
            text: 'Добавить',
            iconCls: 'silk-add',
            handler: this.onAdd,
            scope: this
        }, '-', {
            text: 'Удалить',
            iconCls: 'silk-delete',
            handler: this.onDelete,
            scope: this
        }, '-', {
            text: 'Сохранить',
            iconCls: 'icon-save',
            handler: this.onSave,
            scope: this
        }, '-'];
    },

    /**
     * buildBottomToolbar
     */
    buildBottomToolbar : function() {
        return ['<b>@cfg:</b>', '-', {
            text: 'autoSave',
            enableToggle: true,
            pressed: false,
            tooltip: 'When enabled, Store will execute Ajax requests as soon as a Record becomes dirty.',
            toggleHandler: function(btn, pressed) {
                this.store.autoSave = pressed;
            },
            scope: this
        }, '-', {
            text: 'batch',
            enableToggle: true,
            pressed: true,
            tooltip: 'When enabled, Store will batch all records for each type of CRUD verb into a single Ajax request.',
            toggleHandler: function(btn, pressed) {
                this.store.batch = pressed;
            },
            scope: this
        }, '-', {
            text: 'writeAllFields',
            enableToggle: true,
            tooltip: 'When enabled, Writer will write *all* fields to the server -- not just those that changed.',
            toggleHandler: function(btn, pressed) {
                store.writer.writeAllFields = pressed;
            },
            scope: this
        }, '-'];
    },

    /**
     * buildUI
     */
    buildUI : function() {
        return [{
            text: 'Save',
            iconCls: 'icon-save',
            handler: this.onSave,
            scope: this 
        }];
    },

    /**
     * onSave
     */
    onSave : function(btn, ev) {
        this.store.save();
    },

    /**
     * onAdd
     */
    onAdd : function(btn, ev) {
        var u = new this.store.recordType({
            first : '',
            last: '',
            email : ''
        });
        this.stopEditing();
        this.store.insert(0, u);
        this.startEditing(0, 1);
    },

    /**
     * onDelete
     */
    onDelete : function(btn, ev) {
        var index = this.getSelectionModel().getSelectedCell();
        if (!index) {
            return false;
        }
        var rec = this.store.getAt(index[0]);
        this.store.remove(rec);
    }
});
