

Ext.ns('App', 'App.subj');



App.subj.Tree = Ext.extend( 
  Ext.tree.TreePanel,{
      rootVisible:false,
      height: 250,
      //width: 500,
      initComponent : function() 
      {
        
        App.subj.Tree.superclass.initComponent.call(this);
      }
});


/*App.subj.Tree = Ext.extend( 
Ext.ux.tree.TreeGrid,{
      //title: 'Иллюстрированный материал',
      //margins: '5 5 0 0',
      //header: true,
      height: 250,
      width: 500,
      //frame: true
      initComponent : function() 
      {
        this.editor = new Ext.tree.TreeEditor(this, {
            allowBlank: false,
            blankText: 'A name is required',
            selectOnFocus: true
        });
        this.editor.on('complete', this.onEditComplete, this);
        this.on('contextmenu', this.onContextMenu, this);
        
        App.subj.Tree.superclass.initComponent.call(this);
      },
       
      onEditComplete: function(editor, newVal, oldVal) 
      {
          //var n = editor.editNode;
          //alert("safdas");
          //Imgorg.ss.Albums.addOrUpdate({node: n.id, text: newVal, id: n.attributes.id});
      },
      
      onContextMenu: function(node, e) 
      {
        e.stopEvent();
        if(!this.contMenu) 
        {
          this.contMenu = new Ext.menu.Menu({
              items: [{
                  text: 'Remove',
                  handler: function() {
                      var node = this.currentNode;
                      node.unselect();
                       node.remove();
                  },
                  scope: this
              },
           
              {
                  text: 'Add',
                  handler: function() {
                  //alert('add');
                  var root = this.currentNode;//this.root;
                  var node = root.appendChild(new Ext.tree.TreeNode({
                  text:'Раздел'//,
                  }));
        this.getSelectionModel().select(node);
        var ge = this.editor;
        setTimeout(function(){
            ge.editNode = node;
            ge.startEdit(node.ui.textNode);
        }, 10);
                   // alert('add');
                    }
                    ,scope: this
                }
                ]
            });
        }
        this.currentNode = node;
        this.contMenu.showAt(e.getXY());
    },
});*/


