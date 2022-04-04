
Ext.onReady(function() {

Ext.QuickTips.init();

var treeLoader = new Ext.tree.TreeLoader({
    dataUrl:'subj/view'
});
var rootNode = new Ext.tree.AsyncTreeNode({
    text: 'ѕредментые области'
    ,expanded: true

});


var actionAdd=new Ext.Action(
{
  text: 'ƒобавить'
  ,iconCls: 'silk-add'
  ,handler: fn_addNode
})
var actionDel=new Ext.Action(
{
  text: '”далить'
  ,iconCls: 'silk-delete'
  ,handler: fn_deleteHandler
})


var tree = new Ext.ux.tree.TreeGrid({
//new Ext.tree.TreePanel({
    renderTo:'content-grid'
    //,width: 600    
    ,height: def_height
    ,loader: treeLoader
    ,root: rootNode 
    ,enableDD: true //возможность перетаскивать узлы
    ,rootVisible:true //показ верхнего узла
    ,autoScroll: true
    ,columns:[{
            header: 'Ќаименование',
            dataIndex: 'text',
            width: 200
        }
        ,
        {
            header: '‘»ќ',
            dataIndex: 'fio',
            width: 100
        }]
    ,tbar:[
      actionAdd
      ,'-'
      ,actionDel
      ,'-'
    ]
});




//бработчик событи€ "перемещени€ узла"
tree.on('beforemovenode', function(tree, node,
                                      oldParent, newParent, index) {
    Ext.Ajax.request({
        url: 'subj/move',
        params: {
           nodeid: node.id,
           newparentid: newParent.id,
           oldparentid: oldParent.id,
           dropindex: index //„исленный индекс места, куда был перемещен узел
        }
        
    });
    //return false; //действие переноса будет отменено
});
//сортировка дерева
new Ext.tree.TreeSorter(tree, {
    folderSort: true, //означает, что следует отсортировать узлы листьев, расположенные в папках, т.е все дерево
    dir: "asc" //пор€док сортировки (asc по возростани€ю, desc по убыванию)
});
//поле дл€ редактировани€
var editor = new Ext.tree.TreeEditor(tree);


//обработчик событи€ "произошло редактировани€"
editor.on('beforecomplete', fn_save);

function fn_save(editor, newValue, originalValue) 
{
  Ext.Ajax.request({
        url: 'subj/update',
        params: {
           id: editor.editNode.id,
           newvalue: newValue,
           oldvalue: originalValue
        }        
    });
}

//обработчик событи€ "выбора узла"
tree.selModel.on('selectionchange', function(selModel, node) {
    //var price = node.attributes.price;
});

//выподающее меню
var contextMenu = new Ext.menu.Menu({
   items: [
      actionAdd,
      actionDel,      
      //{ text: '—ортировать', handler: sortHandler }
    ]
});


//добавили в дерево контекстное меню
//событие contextmenu  запускаетс€, когда пользователь щелкает правой кнопкой мышки на узле
tree.on('contextmenu', fn_treeContextHandler);


//функци€ вывода на экран контекстного меню при  щелкает правой кнопкой мышки на узле
function fn_treeContextHandler(node) {
   node.select();
   contextMenu.show(node.ui.getAnchor());
}
//удаление выбранного узла дерева
function fn_deleteHandler() {
  Ext.Ajax.request({
        url: 'subj/delete',
        params: {
        id: tree.getSelectionModel().getSelectedNode().id
        }
        
    });

   tree.getSelectionModel().getSelectedNode().remove();
}

function fn_addNode() 
{
                 var root = tree.getSelectionModel().getSelectedNode();//this.root;
                 
                  var root_id = -1;
                 if ( root != rootNode) { 
                  root_id = tree.getSelectionModel().getSelectedNode().id;//this.root;
                  }
                 
                  
      Ext.Ajax.request({
        url: 'subj/insert',
        params: {
        root_id: root_id,
        name: 'Ќовый раздел'
        }
    , 
        success: function(response)
        {              
          var json = response.responseText;
          var res = eval('(' + json + ')');
          var node = root.appendChild(new Ext.tree.TreeNode({
                  text:res.name,
                  id:res.subj_area_id
                  })) 
         
         
        },
        failure: function(response){
          var result=response.responseText;
          Ext.MessageBox.alert('error','could not create new node');         
        }                      
      });
                  
}
                  
//сортировка дочерних узлов
function sortHandler() {
    tree.getSelectionModel().getSelectedNode().sort(
      function (leftNode, rightNode) {
         return (leftNode.text.toUpperCase() < rightNode.text.
      toUpperCase() ? 1 : -1);
      }
   );
}
/*
var proxy = new Ext.data.HttpProxy({
url:'subj/view'
    //api: {
		//    read : 'subj/view',
    //    create : 'subj/insert',
    //    update: 'subj/update',
    //    destroy: 'subj/delete'
    //}
});

proxy.load(
    null, 
    {
      read: function(xmlDocument) 
      {
        parseXmlAndCreateNodes(xmlDocument);
      }
    }, 
    function(){ xmltree.render(); }
);
*/



 























 /*var tree = new App.subj.Tree({
 root: new Ext.tree.AsyncTreeNode({
                        expanded: true,
                        text: 'Some Tree'
                }),
        renderTo: 'content-grid'
        ,dataUrl:  'subj/view'
           ,columns:[{
            header: 'Ќаименование',
            dataIndex: 'text',
            width: 230
        }]        
        
    });
*/

        
 /*var tree = new Ext.ux.tree.TreeGrid({
         autoScroll: true,
        height: 300,
        renderTo: 'content-grid',

        columns:[{
            header: 'Ќаименование',
            dataIndex: 'text',
            width: 230
        }]
        ,dataUrl:  'subj/view'  
    });*/
    
  /* var ge = new Ext.tree.TreeEditor(tree,{
            allowBlank: false,
            blankText: 'A name is required',
            selectOnFocus: true
        });*/
   






});




















