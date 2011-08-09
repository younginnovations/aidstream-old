dojo.require('dojo.NodeList-manipulate');
dojo.require('dojo.NodeList-traverse');
dojo.require('dojo.behavior');
dojo.require('dijit.form.DateTextBox');
/*dojo.require('dijit.form.DateTextBox');
dojo.require('dojox.widget.AutoRotator');
dojo.require('dojox.widget.rotator.Pan');
dojo.require('dojox.widget.rotator.Controller');*/
/*dojo.require('dijit.form.DateTextBox');
dojo.require('dijit.form.ComboBox');
dojo.require('dojo.data.ItemFileReadStore');
dojo.require('dijit.layout.ContentPane');
dojo.require('dojox.widget.AutoRotator');
dojo.require('dojox.widget.rotator.Pan');
dojo.require('dojox.widget.rotator.Controller');
dojo.require('dojo.fx.easing');*/

/* Extending dojo.connect to remove attaching event when null is passed */
/*dojo._extconnect = dojo.connect;

dojo.connect = function (source, event, object, method, once) {
	if (source !== null) {
		dojo._extconnect.apply(dojo, arguments);
	}
}
*/

var count = 0;
var cloneElementForm = function (boneNode, newId) {
//console.log(boneNode);
	if (boneNode === null) {
		return
	}
	var clonedNode = dojo.clone(boneNode);
	
//	console.log(clonedNode);
	
	clonedNode.children().children().forEach(function (element, index){
		if(element.getAttribute("name") == null){
			
		}
		else{
//			var reg = new RegExp(/\d+/);
//			console.log(reg.exec(element.getAttribute("name")));
//			console.log(element.getAttribute("name"));
			dojo.attr(element, 'name', element.getAttribute("name").replace(/\d+/, count));
			
		}
//		console.log(element);
	});
//	console.log(dojo.clone(boneNode));
/*	var nodeId = 'new-div-' + count;
	var dobId = 'dob-' + count;*/
	
	// add and set attributes
	clonedNode.attr('id', newId);
	dojo.query('#form-elements-wrapper').append(clonedNode);
//	dojo.attr(clonedNode, 'id', newId);
	dojo.query(clonedNode).style('display', 'block');
	/*dojo.query('#' + nodeId + ' > .remove').style('display', 'block');
	dojo.query('#' + nodeId + ' #dob').attr('id', dobId);*/
	
	// create datetextbox
//	createDateTextBox(dobId, 'dob[]');
	
	// apply the newly added behavior
	dojo.behavior.apply();
	count++;
}
var createDateTextBox = function (id, name) {
	var dob = new dijit.form.DateTextBox({
		id : id,
		name : name
	}, id);
}

function initialize() {
	/*var a = dojo.byId('activity_date_element');
	alert(a);*/
//	console.log(dojo.query('#new-div-0'));
	var boneElement = (dojo.byId('new-div-0') === null) ? null :
		dojo.query('#new-div-0').clone();
	var boneActivityDate = (dojo.byId('datepicker') === null) ? null :
		dojo.query('.datepicker');
	//alert(boneActivityDate);
	// add behaviors
	dojo.behavior.add({
		
		'.addmore' : {
			'onclick' : function (evt) {
				
				//console.log('apple');
				
				var node = new dojo.NodeList(evt.target.parentNode.parentNode);
				node = node.children('div:last-of-type');
				//console.log(node);
				
				var sp = node.query('input').attr('name')[0];
				//console.log(sp);
				
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
		/*".datepicker" : {
			"found" : function (element) {
				createDateTextBox()
			}
		},*/
		
		".add" : {
		
//		var a = dojo.query('.ajax_save').parents('form');
			"onclick" : function (evt) {
		alert('sfssdsdsd');
			var c_node = new dojo.NodeList(evt.target);
//			console.log(c_node);
//			var a = c_node.parents('form').attr('id');
			var a = c_node.parents('form');
//			console.log(a);
			dojo.xhrPost({
				url: APP_BASEPATH + "/wep/add-activity-elements",
				form: dojo.byId(a[0]),
				load: function (data) {
					console.log(data);
//					alert('hello');
				},
				error: function (data) {
					// do nothing on error
				}
			});
		
				evt.preventDefault();
			}
		},
	".edit" : {
			
//			var a = dojo.query('.ajax_save').parents('form');
				"onclick" : function (evt) {
			
			alert('sfsd');
				var c_node = new dojo.NodeList(evt.target);
//				console.log(c_node);
//				var a = c_node.parents('form').attr('id');
				var a = c_node.parents('form');
//				alert("dddd");
//				console.log(a);
				dojo.xhrPost({
					url: APP_BASEPATH + "/wep/edit-activity-elements",
					form: dojo.byId(a[0]),
					load: function (data) {
//					alert('dd');
						console.log(data);
//						alert('hello');
					},
					error: function (data) {
						// do nothing on error
					}
				});
			
					evt.preventDefault();
				}
			},
			
			"#suffix" : {
				"onmouseover" : function (evt){
					dojo.query('.popup').style('display','block');
				},
				"onmouseout" : function (evt){
					dojo.query('.popup').style('display', 'none');
				}
			},
			
			"#add-more" : {
				"onclick" : function (evt) {
				console.log(evt.target);
					var c = dojo.query('#element-form #form-elements-wrapper').children(':last-child');
//					console.log(c);
					count = parseInt(c.attr('id')[0].split('-')[2]) + 1;
					var split = c.attr('id')[0].split('-');
					split[2] = count;
//					console.log(split);
					
					cloneElementForm(boneElement, split.join('-'));
				}
			},
			".remove" : {
				"onclick" : function (evt) {
					confirm('Are you sure you want to delete?');
					var form_id = dojo.attr(evt.target.parentNode, 'id');
					current_url =  location.href;
					var class_param = current_url.split('&');
					var param_array = class_param[1].split('=');
					var class_name = param_array[1].replace('#', '');
					var title_node = dojo.query('#'+ form_id + ' .title_id')[0];
					
					if(title_node.getAttribute('value') == '0'){
//						console.log(evt.target.parentNode);
						dojo.destroy(evt.target.parentNode);
//						console.log(evt.target.parentNode);
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
									console.log(data);
								}
							},
							error: function (data) {
							}
						});
					}
				}
			}
			/*
			"#add-date" : {
				"onclick" : function (evt) {
//				console.log('asdfasdf');
					cloneActivityDate(boneActivityDate);
				}
			},
			".remove-date" : {
				"onclick" : function (evt) {
					dojo.empty(evt.target.parentNode);
				}
			}
		*/
	});
	dojo.behavior.apply();
}

dojo.addOnLoad(initialize);