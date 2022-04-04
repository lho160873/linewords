
Ext.onReady(function() {

Ext.QuickTips.init();

    // create user.Form instance (@see UserForm.js)
    /*var developerForm = new App.Form({
        renderTo: 'developer-form',
        listeners: {
            create : function(fpanel, data) {   // <-- custom "create" event defined in App.user.Form class
                var rec = new developerGrid.store.recordType(data);
                developerGrid.store.insert(0, rec);
            }
        }
    });*/

    // create user.Grid instance (@see UserGrid.js)
    var developerGrid = new App.developer.Grid({
        renderTo: 'content-grid',
        store: developerStore,
        columns : developerColumns,
        /*listeners: {
            rowclick: function(g, index, ev) {
                var rec = g.store.getAt(index);
                developerForm.loadRecord(rec);
            },
            destroy : function() {
                developerForm.getForm().reset();
            }
        }*/
    });
        developerGrid.getStore().load();  
    


    
   
});
