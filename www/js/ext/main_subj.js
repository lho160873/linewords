
Ext.onReady(function() {

Ext.QuickTips.init();

var treeLoader = new Ext.tree.TreeLoader({
    dataUrl:'subj/view'
});
var rootNode = new Ext.tree.AsyncTreeNode({
    text: '���������� �������'
    ,expanded: true

});


var actionAdd=new Ext.Action(
{
  text: '��������'
  ,iconCls: 'silk-add'
  ,handler: fn_addNode
})
var actionDel=new Ext.Action(
{
  text: '�������'
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
    ,enableDD: true //����������� ������������� ����
    ,rootVisible:true //����� �������� ����
    ,autoScroll: true
    ,columns:[{
            header: '������������',
            dataIndex: 'text',
            width: 200
        }
        ,
        {
            header: '���',
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




//��������� ������� "����������� ����"
tree.on('beforemovenode', function(tree, node,
                                      oldParent, newParent, index) {
    Ext.Ajax.request({
        url: 'subj/move',
        params: {
           nodeid: node.id,
           newparentid: newParent.id,
           oldparentid: oldParent.id,
           dropindex: index //��������� ������ �����, ���� ��� ��������� ����
        }
        
    });
    //return false; //�������� �������� ����� ��������
});
//���������� ������
new Ext.tree.TreeSorter(tree, {
    folderSort: true, //��������, ��� ������� ������������� ���� �������, ������������� � ������, �.� ��� ������
    dir: "asc" //������� ���������� (asc �� ������������, desc �� ��������)
});
//���� ��� ��������������
var editor = new Ext.tree.TreeEditor(tree);


//���������� ������� "��������� ��������������"
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

//���������� ������� "������ ����"
tree.selModel.on('selectionchange', function(selModel, node) {
    //var price = node.attributes.price;
});

//���������� ����
var contextMenu = new Ext.menu.Menu({
   items: [
      actionAdd,
      actionDel,      
      //{ text: '�����������', handler: sortHandler }
    ]
});


//�������� � ������ ����������� ����
//������� contextmenu  �����������, ����� ������������ ������� ������ ������� ����� �� ����
tree.on('contextmenu', fn_treeContextHandler);


//������� ������ �� ����� ������������ ���� ���  ������� ������ ������� ����� �� ����
function fn_treeContextHandler(node) {
   node.select();
   contextMenu.show(node.ui.getAnchor());
}
//�������� ���������� ���� ������
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
        name: '����� ������'
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
                  
//���������� �������� �����
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
            header: '������������',
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
            header: '������������',
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




















