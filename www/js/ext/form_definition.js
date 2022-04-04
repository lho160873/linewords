/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
Ext.ns('App', 'App.definition');
/**
 * @class App.user.FormPanel
 * A typical FormPanel extension
 */
 
 
 
App.definition.Form = Ext.extend(Ext.form.FormPanel, {
    //renderTo: 'user-form',
    //iconCls: 'silk-user',
    frame: true,
    header: false,
    labelAlign: 'left',
    labelPad: 0,
    margins: 0,
    labelWidth: 1,
    //title: 'Определение',
    frame: true,
    //width: 500,
    defaultType: 'textarea',
    layout: 'fit', 
    defaults: {
        anchor: '100%'
    },

    // private A pointer to the currently loaded record
    record : null,


    initComponent : function() {
        // build the form-fields.  Always a good idea to defer form-building to a method so that this class can
        // be over-ridden to provide different form-fields
        this.items = this.buildForm();

        // build form-buttons
        //this.buttons = this.buildUI();
        this.tbar = this.buildTopToolbar();
        // add a create event for convenience in our application-code.
        this.addEvents({

            create : true
        });

        // super
        App.definition.Form.superclass.initComponent.call(this);
    },

buildTopToolbar : function() {
        return [{
            text: 'Сохранить',
            iconCls: 'icon-save',
            handler: this.onUpdate,
            scope: this
        }, '-'];
    },
    
    buildForm : function() {
        return [         
            {name: 'descript', allowBlank: false}
        ];
    },


    buildUI: function(){
        return [{
            text: 'Save',
            iconCls: 'icon-save',
            handler: this.onUpdate,
            scope: this
        }, {
            text: 'Create',
            iconCls: 'silk-user-add',
            handler: this.onCreate,
            scope: this
        }, {
            text: 'Reset',
            handler: function(btn, ev){
                this.getForm().reset();
            },
            scope: this
        }];
    },


    loadRecord : function(rec) {
        this.record = rec;
        this.getForm().loadRecord(rec);
    },


    onUpdate : function(btn, ev) {
        if (this.record == null) {
            return;
        }
        if (!this.getForm().isValid()) {
            App.setAlert(false, "Form is invalid.");
            return false;
        }
        //alert(definitionStore);
        definitionStore.autoSave=true;
        this.getForm().updateRecord(this.record);
        definitionStore.autoSave=false;
    },


    onCreate : function(btn, ev) {
        if (!this.getForm().isValid()) {
            App.setAlert(false, "Form is invalid");
            return false;
        }
        this.fireEvent('create', this, this.getForm().getValues());
        this.getForm().reset();
    },


    onReset : function(btn, ev) {
        this.fireEvent('update', this, this.getForm().getValues());
        this.getForm().reset();
    }
    
    
    
});
