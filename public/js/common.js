dojo.require('dojo.NodeList-manipulate');
dojo.require('dojo.NodeList-traverse');
dojo.require('dojo.behavior');
dojo.require('dijit.Dialog');
dojo.require('dijit.form.Button');
//dojo.require('dijit.form.DateTextBox');


/* Extending dojo.connect to remove attaching event when null is passed */
/*
dojo._extconnect = dojo.connect;

dojo.connect = function (source, event, object, method, once) {
    if (source !== null) {
        dojo._extconnect.apply(dojo, arguments);
    }
}
*/
/*
var createDateTextBox = function (id, name) {
    var dob = new dijit.form.DateTextBox({
        id : id,
        name : name
    }, id);
}
*/

var messageDialog = function (title, msg) {
    
    var msgDlg = new dijit.Dialog({
                    title: title,
                    style: "width: 240px",
                    parseOnLoad: true,
                    content: '<p>' + msg + '</p><p style="margin-left:90px"><button dojoType="dijit.form.Button" type="button" id="msg-ok">OK</button></p>'
    });
    
    dojo.connect(dojo.byId('msg-ok'), 'onclick', function (e) {
        msgDlg.destroyRecursive();
    });
    
    msgDlg.show();
}

function initialize() {
    
    // start of dojo.behavior.add
    dojo.behavior.add({
        '.addmore' : {
            'onclick' : function (evt) {
                
                var node = new dojo.NodeList(evt.target.parentNode.parentNode);
                node = node.children('fieldset:last-of-type');
                //console.log(node);
                
                var sp = node.query('input').attr('name')[0];
                
                sp = sp.split(/\[(\d+)\]/);
                
                var values = new Array();
    
                for (var i = 1; i < sp.length; i++) {
                    if (sp[i] !== '') {
                        values.push(sp[i]);
                    }
                }
                ajx = new Object();
    
                //ajx['classname'] = sp[0].split(/_/)[0];
    
                for (var i = 0; i < values.length; i++) {
                    ajx['item' + i] = values[i];
                }
                
                
                dojo.xhrGet({
                    url : dojo.attr(evt.target, 'href'),
                    handleAs : 'text',
                    content : ajx,
                    load : function (data) {
                        dojo.place(data, evt.target.parentNode, "before");
                        dojo.behavior.apply();
                    },
                    error : function (err) {
                        
                    }
                });
                dojo.behavior.apply();
                
                evt.preventDefault();
            }
        },
        /*
        ".datepicker" : {
            "found" : function (element) {
                createDateTextBox()
            }
        },
        */
        "#suffix" : {
            "onmouseover" : function (evt){
                dojo.query('.popup').style('display','block');
            },
            "onmouseout" : function (evt){
                dojo.query('.popup').style('display', 'none');
            }
        },
        ".remove" : {
            "onclick" : function (evt) {
                
                var removeNode = evt.target.parentNode;
                var removeUrl = dojo.attr(evt.target, 'href');
                //var grandParent = new dojo.NodeList(evt.target.parentNode.parentNode);
                //var parentNode = new dojo.NodeList(evt.target.parentNode);
                
                var msg = '<div><p>Are you sure you want to delete this item?</p>';
                msg += '<p>This will remove all the subitems(if any) as well.</p>';
                msg += '<p style="margin-left:100px"><button dojoType="dijit.form.Button" type="button" id="cd-ok">OK</button>';
                msg += '<button dojoType="dijit.form.Button" type="button" id="cd-cancel">Cancel</button></p>';
                
                var confirmDlg = new dijit.Dialog({
                    title: "Are you Sure?",
                    style: "width: 320px",
                    parseOnLoad: true,
                    content: msg
                });
                
                dojo.connect(dojo.byId('cd-cancel'), 'onclick', function (e) {
                    confirmDlg.destroyRecursive();
                });
                
                dojo.connect(dojo.byId('cd-ok'), 'onclick', function (e) {
                    confirmDlg.destroyRecursive();
                    var grandParent = dojo.NodeList(removeNode.parentNode.parentNode);
                    if (grandParent.children('div').length < 2) {
                        
                        messageDialog("Warning!", "Sorry! you cannot remove last item.");
                        
                    }
                    else {
                        var parentNode = dojo.NodeList(removeNode.parentNode);
                        var removeId = dojo.attr(parentNode.query('input[type="hidden"]')[0], 'value');
                        
                        if (parseInt(removeId)) {
                            dojo.xhrGet({
                                url: removeUrl ,
                                handleAs: 'text',
                                content: {
                                    "id": removeId
                                },
                                load: function (data) {
                                    if(data == 'success'){
                                        dojo.destroy(parentNode[0]);
                                    }
                                    else{
                                        messageDialog("Error", data);
                                    }
                                },
                                error: function (data) {
                                    messageDialog("Error", "Something went wrong! Please try again");
                                }
                            });
                        }
                        else {
                            dojo.destroy(parentNode[0]);
                        }
                    }
                });
                
                confirmDlg.show();
                
                evt.preventDefault();
                /*
                confirm('Are you sure you want to delete?');
                var form_id = dojo.attr(evt.target.parentNode, 'id');
                current_url =  location.href;
                var class_param = current_url.split('&');
                var param_array = class_param[1].split('=');
                var class_name = param_array[1].replace('#', '');
                var title_node = dojo.query('#'+ form_id + ' .title_id')[0];
                
                if(title_node.getAttribute('value') == '0'){
                    dojo.destroy(evt.target.parentNode);
                }
                else{
                    dojo.xhrGet({
                        url: APP_BASEPATH + "/wep/remove-elements" ,
                        handleAs: 'text',
                        content: {
                            "id": dojo.attr(title_node, 'value'),
                            "class": class_name
                        },
                        load: function (data) {
                            if(data == 'success'){
                                dojo.destroy(evt.target.parentNode);
                            }
                            else{
                                // do something here
                            }
                        },
                        error: function (data) {
                        }
                    });
                }
                */
            }
        },
        "tr": {
            "onmouseenter" : function (evt) {
//        		console.log(evt);
        		var node = dojo.NodeList(evt.target.parentNode);
//        		console.log(node);
        		node.query('.list-action').style('display', 'block');;
        
        		evt.stopPropagation();
        	},
        	"onmouseleave" : function (evt) {
//        		console.log(evt);
        		var node = dojo.NodeList(evt.target.parentNode);
//        		console.log(node);
        		node.query('.list-action').style('display', 'none');;
        
        		evt.stopPropagation();
        	}
        
        	
        }
    });
    // End of dojo.behavior.add
    dojo.behavior.apply();
}

dojo.addOnLoad(initialize);
