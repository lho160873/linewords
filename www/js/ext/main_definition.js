
Ext.onReady(function() {

Ext.QuickTips.init();
// Typical Store collecting the Proxy, Reader and Writer together.

  
       
 // display or bring forth the form
  function displayFormSubj(){
     //alert('onBtnFiltrClick');
    // SubjWindow.show();
    // Subj_Window.show();
     if(!SubjWindow.isVisible()){
       //resetPresidentForm();
       SubjWindow.show();
     } else {
       SubjWindow.toFront();
     }
  };
     
             
var fnSelectRange = function()
{
  if (m_definitionGrid.num_row != -1 )
    m_definitionGrid.getSelectionModel().select( m_definitionGrid.num_row, 0 );

};

definitionStore = new Ext.data.Store({
    id: 'definition_my',
    proxy: proxyDefinition,
    reader: readerDefinition,
    writer: writerDefinition,  // <-- plug a DataWriter into the store just as you would a Reader
    autoSave: false // <-- false would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
    //,sortInfo:{field: 'name', direction: "ASC"}
    ,listeners:{              
            load:  fnSelectRange
            }
});
 
    var m_definitionGrid = new App.definition.Grid({
      //renderTo: 'content-grid',
      store: definitionStore,
      //region:'west',
      region: 'center',
      layout: 'fit',
      width: 450,
      minSize: 250,  
      //split: true,         // enable resizing
      //collapsible: true,   // make collapsible
      columns : definitionColumns,
      margins: '0 5 10 5',
      bbar: new Ext.PagingToolbar({
                pageSize: def_limit_defs,
                store: definitionStore,
                displayInfo: true
                ,displayMsg:'Показаны {0} - {1} из {2}'
                ,emptyMsg:'Данных нет'
                ,firstText:'Первая страница'
                ,nextText:'Следующая страница'
                ,prevText:'Предыдущая страница'
                ,lastText:'Последняя страница'
                ,refreshText:'Обновить данные'
                ,afterPageText:'из {0}'
                ,beforePageText:'Страница'
            }),
      listeners: {
            rowclick: function(g, index, ev) {
                var rec = g.store.getAt(index);
                m_editDescript.loadRecord(rec);
            },
           
            destroy : function() {
                //definitionForm.getForm().reset();
            }
        }
    });
    
    m_definitionGrid.getStore().load({params: {start: 0, limit: def_limit_defs}});  
    

var m_definitionFind = new Ext.form.TextField({
 title: 'Поиск',
        header: false,
        width: 200,
        value: 'поиск...'        
});

 
 var  onBtnFindClick  = function(btn, ev) 
 {
       
        var textFind = m_definitionFind.getValue();
        var offset = 0;
        var index = 0;
        if ( m_definitionGrid.getBottomToolbar() )
        {
          offset = m_definitionGrid.getBottomToolbar().cursor;
        }
        if( m_definitionGrid.getSelectionModel().getSelectedCell()!= null )
        {
          index = m_definitionGrid.getSelectionModel().getSelectedCell()[0];
        }
        offset = offset+index;

  
        //var ind = m_definitionGrid.store.find('name',textFind,index)+1;
        //if (ind != -1 )
        //  m_definitionGrid.getSelectionModel().select(ind, 0 );

        Ext.Ajax.request({   
          waitMsg: 'Идет поиск...',
          url: 'definition/find',
          params: {
            strfind: textFind,
            limit: def_limit_defs,
            offset: offset
          }, 
          success: function(response)
          {                       
            var json = response.responseText;
            var res = eval('(' + json + ')');
            var num_page = res.num_page;
            m_definitionGrid.num_row = res.num_row;
            var v_start = (num_page-1)*def_limit_defs;
            if ( m_definitionGrid.num_row != -1 )
            m_definitionGrid.store.load({params: {start: v_start, limit: def_limit_defs}}); 
          },
          failure: function(response){
            var result=response.responseText;
            Ext.MessageBox.alert('error','could not connect to the database. retry later');         
          }                      
        });
};        
var  onBtnFiltrClick  = function(btn, ev) 
 {

 //SubjWindow.show();
 displayFormSubj();
 } 
var m_btnFind = new Ext.Button({
  listeners:{ click : onBtnFindClick }
  ,width: 80
  ,text: ' найти '
  ,margins: '0 0 0 10'
  , iconCls: 'silk-search'
});

var m_definitionFindPanel = new Ext.Panel({
      width: 350,
      layout: 'hbox',
      margins: '5 5 0 5',
      header: false,
      border:false,
     items: [m_definitionFind,m_btnFind]
});


var m_definitionFiltr = new Ext.form.TextField({
 title: 'Поиск',
        header: false,
       // margins: '5 5 0 5',
        width: 200,
        //region:'north',
        value: 'предметная область...'        
});

var m_btnFiltr = new Ext.Button({
  listeners:{ click : onBtnFiltrClick }
  ,width: 80
  ,text: ' выбрать'
  ,margins: '0 0 0 10'
  , iconCls: 'silk-user'
});

var m_definitionFiltrPanel = new Ext.Panel({
      width: 350,
      layout: 'hbox',
      margins: '5 5 0 5',
      header: false,
      border:false,
     items: [m_definitionFiltr,m_btnFiltr]
});


var m_headerPanel = new Ext.Panel({
      //width: 250,
      //minSize: 250,  
      height: 60,
      title: 'Список',
      region:'north',
      layout: 'vbox',
      //split: true,         // enable resizing
      //collapsible: true,   // make collapsible
      header: false,
    items: [m_definitionFiltrPanel,m_definitionFindPanel]
});

var m_definitionPanel = new Ext.Panel({
      width: 450,
      minSize: 250,  
      title: 'Список',
      //region:'west',
      layout: 'border',
      //split: true,         // enable resizing
      //collapsible: true,   // make collapsible
      header: false,
    items: [m_headerPanel,m_definitionGrid]
});

var m_definitionTabs = new Ext.TabPanel({
        width: 450,
        minSize: 250,  
        tabPosition: 'bottom',
        region:'west',
        //margins:'3 3 3 0', 
        activeTab: 0,
        split: true,         // enable resizing
        collapsible: true,   // make collapsible
        //defaults:{autoScroll:true},
        items:[
         m_definitionPanel,
         {
             title: 'Сеть',
             closable:true
         },
         {
             title: 'Поиск',
             closable:true
         },
         {
             title: 'Избранное'
         }]
    });


 var m_editDescript = new App.definition.Form({
      title: 'Определение',
      margins: '5 5 0 0',
      region:'north',
      split: true,
      listeners: {
            create : function(fpanel, data) {   // <-- custom "create" event defined in App.user.Form class
                var rec = new m_definitionGrid.store.recordType(data);
                m_definitionGrid.store.insert(0, rec);
            }
        }
    });



    var m_illustrationDescript = new Ext.form.HtmlEditor({
       title: 'Иллюстрированный материал',
      margins: '5 5 0 0',
      header: true,
     frame: true

    });
    
  var m_editPanelIllustrationDescript = new Ext.Panel({
    title: 'Иллюстрированный материал',
    header: true,
    layout: 'fit',
    region:'center',
    //layout: 'border',
    items: [m_illustrationDescript]
   });
    
    
  var m_editPanel = new Ext.Panel({
    title: 'Определение',
    header: false,
    layout: 'border',
    items: [m_editDescript, m_editPanelIllustrationDescript]
});  

    var m_tabsDescript = new Ext.TabPanel({
        region: 'center',
        margins:'3 3 3 0', 
        activeTab: 0,
        //collapsible: true,   // make collapsible
        //defaults:{autoScroll:true},
        items:[
         m_editPanel,
         {
             title: 'Сеть',
             closable:true
         },
         {
             title: 'Альтернативные определения'
         },{
             title: 'Первоисточники',
             closable:true
         }
         ,{
             title: 'Синонимы',
             closable:true
         }]
    });
              
        
    var myBorderPanel = new Ext.Panel({
    renderTo: 'content-grid',
    //width: 700,
    height: def_height,
    autoWidth:true,
    title: 'Border Layout',
    layout: 'border',
    header: false,
    items: [
      m_definitionTabs,
      m_tabsDescript
    ]
    });
    
   
});
