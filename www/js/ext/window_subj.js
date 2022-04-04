
//Ext.onReady(function() {

//Ext.QuickTips.init();

var treeLoader = new Ext.tree.TreeLoader({
    dataUrl:'subj/view'
});
var rootNode = new Ext.tree.AsyncTreeNode({
    text: 'Предментые области'
    ,expanded: true

});

//var SubjWindow;
var actionAdd=new Ext.Action(
{
  text: 'Добавить'
  ,iconCls: 'silk-add'
  ,handler: fn_addNode
});
var actionDel=new Ext.Action(
{
  text: 'Удалить'
  ,iconCls: 'silk-delete'
  ,handler: fn_deleteHandler
});

var actionSend=new Ext.Action(
{
  text: 'Выбрать'
  //,iconCls: 'silk-delete'
  ,handler: fn_hide
  //,handler: fn_deleteHandler
});




function fn_hide()
{
  SubjWindow.hide();
}


var tree = new Ext.ux.tree.TreeGrid({
//new Ext.tree.TreePanel({
    //renderTo:'content-grid'
    //width: 600    
    bodyStyle: 'padding: 5px'
    //,width: 600
    ,height: def_height
    ,loader: treeLoader
    ,root: rootNode 
    ,enableDD: true //возможность перетаскивать узлы
    ,rootVisible:false //показ верхнего узла
    ,autoScroll: false
    ,columns:[{
            header: 'Наименование',
            dataIndex: 'text',
            width: 300
        }
        ,
        {
            header: 'ФИО',
            dataIndex: 'fio',
            width: 200
        }]
    ,tbar:[
      actionAdd
      ,'-'
      ,actionDel
      ,'-'
      ,actionSend
      ,'-'
    ]
});

var SubjWindow = new Ext.Window({
         title: 'Предментые области',
         closable:true,
         width: 600,
         height: 600,
         plain:true,
         layout: 'fit',
         items: tree,
         autoScroll:false
     }); 


//бработчик события "перемещения узла"
tree.on('beforemovenode', function(tree, node,
                                      oldParent, newParent, index) {
    Ext.Ajax.request({
        url: 'subj/move',
        params: {
           nodeid: node.id,
           newparentid: newParent.id,
           oldparentid: oldParent.id,
           dropindex: index //Численный индекс места, куда был перемещен узел
        }
        
    });
    //return false; //действие переноса будет отменено
});
//сортировка дерева
new Ext.tree.TreeSorter(tree, {
    folderSort: true, //означает, что следует отсортировать узлы листьев, расположенные в папках, т.е все дерево
    dir: "asc" //порядок сортировки (asc по возростанияю, desc по убыванию)
});
//поле для редактирования
var editor = new Ext.tree.TreeEditor(tree);


//обработчик события "произошло редактирования"
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

//обработчик события "выбора узла"
tree.selModel.on('selectionchange', function(selModel, node) {

    //var price = node.attributes.price;
});

//выподающее меню
var contextMenu = new Ext.menu.Menu({
   items: [
      actionAdd,
      actionDel,      
      //{ text: 'Сортировать', handler: sortHandler }
    ]
});


//добавили в дерево контекстное меню
//событие contextmenu  запускается, когда пользователь щелкает правой кнопкой мышки на узле
tree.on('contextmenu', fn_treeContextHandler);


//функция вывода на экран контекстного меню при  щелкает правой кнопкой мышки на узле
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
        name: 'Новый раздел'
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


     
       
       
       


 

//});




















