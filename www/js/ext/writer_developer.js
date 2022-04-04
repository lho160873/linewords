/*!
 * Ext JS Library 3.3.1
 * Copyright(c) 2006-2010 Sencha Inc.
 * licensing@sencha.com
 * http://www.sencha.com/license
 */
// Application instance for showing user-feedback messages.
var App = new Ext.App({});

// Create HttpProxy instance.  Notice new configuration parameter "api" here instead of load.  However, you can still use
// the "url" paramater -- All CRUD requests will be directed to your single url instead.
var proxyDeveloper = new Ext.data.HttpProxy({
    api: {
        //read : 'app.php/developers/view',
		read : 'developer/view',
        create : 'developer/insert',
        update: 'developer/update',
        destroy: 'developer/delete'
    }
});

// Typical JsonReader.  Notice additional meta-data params for defining the core attributes of your json-response
var readerDeveloper = new Ext.data.JsonReader({
    totalProperty: 'total',
    successProperty: 'success',
    idProperty: 'developer_id',
    root: 'data',
    messageProperty: 'message'  // <-- New "messageProperty" meta-data
}, [
    {name: 'developer_id'},
    {name: 'fio', allowBlank: true},
    {name: 'comment', allowBlank: true}
]);

// The new DataWriter component.
var writerDeveloper = new Ext.data.JsonWriter({
    encode: true,
    writeAllFields: false
});

// Typical Store collecting the Proxy, Reader and Writer together.
var developerStore = new Ext.data.Store({
    id: 'developer',
    proxy: proxyDeveloper,
    reader: readerDeveloper,
    writer: writerDeveloper,  // <-- plug a DataWriter into the store just as you would a Reader
    autoSave: false // <-- false would delay executing create, update, destroy requests until specifically told to do so with some [save] buton.
});

// load the store immeditately
//developerStore.load();

////
// ***New*** centralized listening of DataProxy events "beforewrite", "write" and "writeexception"
// upon Ext.data.DataProxy class.  This is handy for centralizing user-feedback messaging into one place rather than
// attaching listenrs to EACH Store.
//
// Listen to all DataProxy beforewrite events
//

    
Ext.data.DataProxy.addListener('beforewrite', function(proxy, action) {
    App.setAlert(App.STATUS_NOTICE, "Before " + action); 
//addMessage("notice", "Before " + action);
//alert("Before " + action);
});

////
// all write events
//
Ext.data.DataProxy.addListener('write', function(proxy, action, result, res, rs) {
    App.setAlert(true, action + ':' + res.message);
//alert(action + ':' + res.message);
});

////
// all exception events
//
Ext.data.DataProxy.addListener('exception', function(proxy, type, action, options, res) {
    if (type === 'remote') {
        Ext.Msg.show({
            title: 'REMOTE EXCEPTION',
            msg: res.message,
            icon: Ext.MessageBox.ERROR,
            buttons: Ext.Msg.OK
        });
    }
});


// A new generic text field
var textField =  new Ext.form.TextField();

// Let's pretend we rendered our grid-columns with meta-data from our ORM framework.
var developerColumns =  [
    {header: "Код", width: 40, sortable: true, dataIndex: 'developer_id'},
    {header: "ФИО", width: 140, sortable: true, dataIndex: 'fio', editor: textField},
    {header: "Комментарий", width: 200, sortable: true, dataIndex: 'comment', editor: textField}
];


