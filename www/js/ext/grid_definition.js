

Ext.ns('App', 'App.definition');


App.definition.Grid = Ext.extend(
    Ext.grid.EditorGridPanel, 
    {
    //renderTo: 'developer-grid',
    iconCls: 'silk-grid',
    frame: true,
    title: 'Понятия',
    //height: 250,
    //width: 500,
    //autoHeight:true,
    autoWidth:true,
    //autoDestroy:true,
    style: 'margin-top: 10px',
    buttonAlign: 'left',
    header: false,
    num_row: -1,
    
    selModel: new Ext.grid.CellSelectionModel()
    ,initComponent : function() {

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
        App.definition.Grid.superclass.initComponent.call(this);
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
        }, '-', {
            text: 'Тест',
            //iconCls: 'icon-save',
            handler: this.onTest,
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
            idn : '',
            name: '',
            descritp : ''
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
    },
    
   fnFind: function(response)
        {              
          var json = response.responseText;
          var res = eval('(' + json + ')');
          var num_page = res.num_page;
          this.num_row = res.num_row;
          var v_start = (num_page-1)*def_limit_defs;
          //var curr_index = this.getSelectionModel().getSelectedCell();
          //if (this.num_row==-1 && curr_index!=0)
          //num_row = curr_index;
          if ( this.num_row != -1 )
          //num_row = 0;
          this.store.load({params: {start: v_start, limit: def_limit_defs}}); 
          //changePage
        },
 
    onTest : function(btn, ev) {
        var textFind = m_definitionFind.getValue();
        //alert('1');
        //var strt = this.selModel.getSelectedCell();
        //alert(strt);
        var offset = 0;
        var index = 0;
        if ( this.getBottomToolbar() )
        {
          offset = this.getBottomToolbar().cursor;
        }
        if( this.getSelectionModel().getSelectedCell()!= null )
        {
          index = this.getSelectionModel().getSelectedCell()[0];
        }
        offset = offset+index;
        //alert(offset);
        //alert(this.getBottomToolbar().cursor);
         //var index = this.getSelectionModel().getSelectedCell();
         
         
         //alert(strt);
        Ext.Ajax.request({   
          waitMsg: 'Идет поиск...',
          url: 'definition/find',
          params: {
            strfind: textFind,
            limit: def_limit_defs,
            offset: offset
          }, 
          success: this.fnFind.bind(this),
          failure: function(response){
            var result=response.responseText;
            Ext.MessageBox.alert('error','could not connect to the database. retry later');         
          }                      
        });
      
      
  
    //App.setAlert(App.STATUS_NOTICE, "Before " + action); 
//addMessage("notice", "Before " + action);
//alert("Before " + action);
//}

//); 
    
    //this.store.reload({params: {start: 70, limit: def_limit_defs}}); 
    //alert("d");
    //this.store.refresh();
    //alert("d");
     //this.store.reload({params: {start: 105, limit: def_limit_defs}});   
      //for ( var i = 0; i<countPage; i++)
      //{
      
      // this.store.reload({params: {start: i*def_limit_defs, limit: def_limit_defs}});  
       //this.store.reload({params: {start: 35, limit: def_limit_defs}}); 
       //this.store.update(); 
      //this.store.save();
       //this.store.destroy();
     // var ind = this.store.find('name','Авиационный полк');
     //  alert(ind);

      // if (ind != -1)
      // {
       //this.selModel.selectRange(ind,ind);
       //break;
       //}
     // }
     /*  this.store.load({params: {start: 100, limit: def_limit_defs}});  
       
        //this.store.save();
        var ind = this.store.find('name','Абонент');
        //alert(this.store.find('name','Абонент'));
      
       //onRowclick(this,ind);
       //this.focus(true);
       //this.grid.on('rowmousedown', this.rowclick(this,ind), this);
       //this.rowclick(this,ind);
       this.selModel.selectRange(ind,ind);
       }*/
       
      

       
        //this. (ind);
      //this.
       //this.store.load({params: {start: 100, limit: def_limit_defs}});  
       
    }
    
});
