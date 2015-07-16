dojo.require('dojo.NodeList-manipulate');
dojo.require('dojo.NodeList-traverse');
dojo.require('dojo.behavior');
dojo.require('dijit.Dialog');
dojo.require('dijit.form.Button');
dojo.require('dijit.form.Select');
//dojo.require('dijit.form.CheckBox');
dojo.require('dijit.form.DateTextBox');
//dojo.require("dojo.date.locale");
dojo.require('dijit.TooltipDialog');
dojo.require('dojo.number');
dojo.require('dojo.io.iframe');

/**
 * @todo some comments on what is required for what
 * @param evt
 * @param checklist
 * @param checkstate
 * @returns
 */
/* Extending dojo.connect to remove attaching event when null is passed */
/*
dojo._extconnect = dojo.connect;

dojo.connect = function (source, event, object, method, once) {
    if (source !== null) {
        dojo._extconnect.apply(dojo, arguments);
    }
}
*/

// Define a basic custom dropdown
dojo.declare("my.dropDown", [dijit._Widget, dijit._Templated], {
    // summary:
    //      Supply label and popup as parameter when instantiating this widget.

    label: null,
    orient: {
        'BL': 'TL',
        'BR': 'TR'
    }, // see http://api.dojotoolkit.org/jsdoc/1.3.2/dijit.popup.__OpenArgs (orient)
    templateString: "<span class='enabled' dojoAttachEvent='onclick: openPopup,onkeyup:keyPress' onClick='return false;' dojoAttachPoint='labelNode'></span>",
    disabled: false,
    attributeMap: {
        label: {
            node: "labelNode",
            type: "innerHTML"
        }
    },
    keyPress: function (key) {
        if (key.keyCode == dojo.keys.ESCAPE) {
            this.closePopup();
        }
    },

    openPopup: function (focus) {
        if (typeof (focus) === 'undefined') focus = true

        if (this.disabled) return;
        var self = this;

        // wrap the pop-up widget and position it offscreen so
        // that it can be measured by the widget’s startup method
        dijit.popup.moveOffScreen(this.popup);

        // if the pop-up has not been started yet, start it now
        if (this.popup.startup && !this.popup._started) {
            this.popup.startup();
        }
        dijit.popup.open({
            popup: this.popup,
            parent: this,
            around: this.domNode.children[0],
            orient: this.orient,
            onCancel: function () {
                dijit.popup.close(self.popup);
                self.open = false;
            },
            onClose: function () {
                dijit.popup.close(self.popup);
                self.open = false;
            }
        });

        this.open = true;
        if (focus) {
            this.popup.focus();
        }
    },

    closePopup: function () {
        if (this.open) {
            dijit.popup.close(this.popup);
            this.open = false;
        }
    },

    toggleDisabled: function () {
        this.disabled = !this.disabled
        dojo.toggleClass(this.domNode, 'buttonDisabled');
        dojo.toggleClass(this.domNode, 'enabled');
        dojo.attr(this.domNode, 'disabled', this.disabled);
    },

    _onBlur: function () {
        // summary:
        //      This is called from focus manager and when we get the signal we
        //      need to close the drop down
        //      (note: I don't fully understand where this comes from
        //      I couldn't find docs. Got the code from this example:
        //      http://archive.dojotoolkit.org/nightly/dojotoolkit/dijit/tests/_base/test_popup.html
        this.closePopup();
    }
});

dojo.behavior._apply = dojo.behavior.apply;
dojo.behavior.apply = function () {
    loadSelect2();
    dojo.behavior._apply();
}

var checkUncheck = function (evt, checklist, checkstate) {
    dojo.forEach(checklist,
        function (el, idx, ary) {
            el.checked = checkstate;
        }
    );
    evt.preventDefault();
}

var getTarget = function (event) {
    // w3 || IE
    return event.target || event.srcElement;
}

var createDateTextBox = function (id, name, value) {
    if (value) {
        date = value
    } else {
        date = 0;
    }
    var dob = new dijit.form.DateTextBox({
        id: id,
        name: name,
        value: date,
        constraints: {
            datePattern: 'yyyy-MM-dd'
        }

    }, id);
}

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

var confirmDialog = function (title, msg, yes_label, no_label, onOk) {
    if (!yes_label) {
        yes_label = 'Ok';
    }
    if (!no_label) {
        no_label = 'Cancel';
    }

    msg += '<p style="margin-left:100px"><button dojoType="dijit.form.Button" type="button" id="cd-ok">' + yes_label + '</button>';
    msg += '<button dojoType="dijit.form.Button" type="button" id="cd-cancel">' + no_label + '</button></p>';

    // Destroy all dialog box before creating new. Used to remove dialog box remaining after clicking close.
    dojo.query('.dijitDialog').forEach(dojo.destroy);

    var confirmDlg = new dijit.Dialog({
        title: title,
        style: "width: 400px",
        parseOnLoad: true,
        content: msg
    });

    dojo.connect(dojo.byId('cd-cancel'), 'onclick', function (e) {
        confirmDlg.destroyRecursive();
    });

    dojo.connect(dojo.byId('cd-ok'), 'onclick', function (e) {
        onOk.call();
    });

    confirmDlg.show();
}

//support form goes down
var supportUp = function (target) {
    dojo.query("#hidden-overlay").style('display', 'block');
    dojo.query(target).addClass('active');
    dojo.behavior.apply();
    dojo.animateProperty({
        node: dojo.byId("support-wrapper"),
        duration: 500,
        properties: {
            top: {
                start: "-330",
                end: "0"
            }
        }
    }).play();
}
//support form goes up
var supportDown = function (target) {
    dojo.query("#hidden-overlay").style('display', 'none');
    dojo.query(target).removeClass('active');
    dojo.behavior.apply();
    dojo.animateProperty({
        node: dojo.byId("support-wrapper"),
        duration: 500,
        properties: {
            top: {
                start: "0",
                end: "-330"
            }
        }
    }).play();
}

//validate support
var validateSupport = function () {
    flag = 1;
    if (dojo.query('#support_name').val() == "") {
        flag = 0;
        dojo.query('#support_name').addClass('error');
        dojo.query('#support_name').val('Name cannot be empty');
    }
    if (dojo.query('#support_email').val() == "") {
        flag = 0;
        dojo.query('#support_email').addClass('error');
        dojo.query('#support_email').val('Email cannot be empty');
    } else {
        var email = dojo.query('#support_email').val();
        var checkEmail = validEmail(email);
        if (!checkEmail) {
            flag = 0;
            dojo.query('#support_email').addClass('error');
            dojo.query('#support_email').val('Please input a valid email address!');
        }
    }
    if (dojo.query('#support_query').val() == "") {
        flag = 0;
        dojo.query('#support_query').addClass('error');
        dojo.query('#support_query').val('Query cannot be empty');

    } else if (dojo.query('#support_query').val().length >= 1000) {

        flag = 0;
        dojo.query('#support_query').addClass('error');
        dojo.query('#support_query').val('Query is too long. Please use maximum of 1000 characters');

    }

    if (flag == 1) {
        if ((dojo.query('#support_name').attr('class') == 'error') || (dojo.query('#support_email').attr('class') == 'error') || (dojo.query('#support_query').attr('class') == 'error')) {
            flag = 0;
        }
    }

    return flag;
}

var validEmail = function (email) {
    var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    if (filter.test(email)) {
        return true;
    } else {
        return false;
    }
}

    function initialize() {
        var targetNode;
        // start of dojo.behavior.add
        dojo.behavior.add({
            '.addmore': {
                'onclick': function (evt) {
                    evt.preventDefault();
                    var node = new dojo.NodeList(getTarget(evt).parentNode.parentNode);
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
                        url: dojo.attr(getTarget(evt), 'href'),
                        handleAs: 'text',
                        content: ajx,
                        load: function (data) {
                            dojo.place(data, getTarget(evt).parentNode, "before");
                            dojo.behavior.apply();
                        },
                        error: function (err) {

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
            "#suffix": {
                "onmouseover": function (evt) {
                    dojo.query('.popup').style('display', 'block');
                },
                "onmouseout": function (evt) {
                    dojo.query('.popup').style('display', 'none');
                }
            },
            */
            ".remove-this": {
                "onmouseover": function (evt) {
                    var node = getTarget(evt).parentNode.parentNode;
                    dojo.addClass(node, 'remove-highlight');
                },

                "onmouseout": function (evt) {
                    var node = getTarget(evt).parentNode.parentNode;
                    dojo.removeClass(node, 'remove-highlight');
                },

                "onclick": function (evt) {
                    evt.preventDefault();
                    var removeNode = getTarget(evt).parentNode;
                    var removeUrl = dojo.attr(getTarget(evt), 'href');
                    //var grandParent = new dojo.NodeList(getTarget(evt).parentNode.parentNode);
                    //var parentNode = new dojo.NodeList(getTarget(evt).parentNode);
                    var msg = '<div><p>Are you sure you want to delete this item?</p>';
                    msg += '<p>This will remove all the subitems(if any) as well.</p>';
                    msg += '<p style="margin-left:100px"><button dojoType="dijit.form.Button" type="button" id="cd-ok">OK</button>';
                    msg += '<button dojoType="dijit.form.Button" type="button" id="cd-cancel">Cancel</button></p>';

                    // Destroy all dialog box before creating new. Used to remove dialog box remaining after clicking close.
                    dojo.query('.dijitDialog').forEach(dojo.destroy);

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

                        var parentNode = dojo.NodeList(removeNode.parentNode);
                        var removeId = dojo.attr(parentNode.query('input[type="hidden"]')[0], 'value');
                        //console.log(grandParent.children('fieldset').length);
                        if (parseInt(removeId)) {
                            dojo.xhrGet({
                                url: removeUrl,
                                handleAs: 'text',
                                content: {
                                    "id": removeId
                                },
                                load: function (data) {
                                    //console.log(data);
                                    if (data == 'success') {
                                        if (grandParent.children('fieldset').length > 1) {
                                            dojo.destroy(parentNode[0]);
                                        } else {
                                            window.location.reload();
                                        }
                                    } else {
                                        messageDialog("Error", data);
                                    }
                                },
                                error: function (data) {
                                    //console.log(data);
                                    messageDialog("Error", "Something went wrong! Please try again");
                                }
                            });
                        } else {
                            if (grandParent.children('fieldset').length > 1) {
                                dojo.destroy(parentNode[0]);
                            }
                        }

                    });

                    confirmDlg.show();
                    evt.preventDefault();
                    /*
                        confirm('Are you sure you want to delete?');
                        var form_id = dojo.attr(getTarget(evt).parentNode, 'id');
                        current_url =  location.href;
                        var class_param = current_url.split('&');
                        var param_array = class_param[1].split('=');
                        var class_name = param_array[1].replace('#', '');
                        var title_node = dojo.query('#'+ form_id + ' .title_id')[0];

                        if(title_node.getAttribute('value') == '0'){
                            dojo.destroy(getTarget(evt).parentNode);
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
                                        dojo.destroy(getTarget(evt).parentNode);
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
                "onmouseenter": function (evt) {
                    //              console.log(evt);
                    var node = dojo.NodeList(getTarget(evt).parentNode);
                    //              console.log(node);
                    dojo.query(this).children(".title").children('.list-action').style('display', 'block');
                    evt.stopPropagation();
                },
                "onmouseleave": function (evt) {
                    //              console.log(evt);
                    var node = dojo.NodeList(getTarget(evt).parentNode);
                    //              console.log(node);
                    dojo.query(this).children(".title").children('.list-action').style('display', 'none');
                    //node.query('.list-action').style('display', 'none');

                    evt.stopPropagation();
                }


            },
            ".hidden-field": {
                "found": function (ele) {
                    dojo.query(ele).parent().style("display", 'none');
                }
            },
            ".datepicker": {
                "found": function (ele) {

                    createDateTextBox(ele.id, ele.name, ele.value);
                }
            },
            "#publish": {
                "onclick": function (evt) {
                    var ids = new Array();
                    dojo.query('.activity-list-table  input[type=checkbox]:checked').forEach(function (tag) {
                        ids.push(tag.value);
                    });
                    dojo.query('#ids').attr('value', ids.join(","));

                    var msg = "All the selected activities will be published and ";
                    msg += "the activities earlier published will be republished. Are you sure you want to publish?";

                    new confirmDialog('', msg, 'Yes, publish these activities', "Don't publish", function () {
                        dojo.byId('iati_activity_status').submit();
                    });
                }
            },

            "#validate": {
                "onclick": function (evt) {
                    var files = new Array();
                    dojo.query('.xml-list-table td input[type=checkbox]:checked').forEach(function (tag) {
                        files.push(tag.value);
                    });
                    dojo.query('#files').attr('value', files.join(","));

                    var msg = "Are you sure you want to validate the selected XML files?";

                    new confirmDialog('', msg, 'Yes', "Don't Validate", function () {
                        dojo.byId('xml_form').submit();
                    });
                }
            },

            "#delete-transactions": {
                "onclick": function (evt) {
                    var transactions = new Array();
                    dojo.query('.activity-list-table td input[type=checkbox]:checked').forEach(function (tag) {
                        transactions.push(tag.value);
                    });
                    dojo.query('#transactions').attr('value', transactions.join(","));

                    if (transactions.length > 0) {
                        var msg = "Are you sure you want to delete the selected transactions?";
                        new confirmDialog('', msg, 'Yes', "Cancel", function () {
                            dojo.byId('transactions_form').submit();
                        });
                    } else {
                        evt.preventDefault();
                    }
                }
            },

            "#push_to_registry": {
                "onclick": function (evt) {
                    var ids = new Array();
                    dojo.query('.published-files-list-table  input[type=checkbox]:checked').forEach(function (tag) {
                        ids.push(tag.value);
                    });
                    dojo.query('#file_ids').attr('value', ids.join(","));

                    if (confirm("Are you sure you want to publish the files in IATI Registry?")) {
                        dojo.byId('publish-to-registry').submit();
                    }
                }
            },

            "#push_to_registry_for_organisation": {
                "onclick": function (evt) {
                    var ids = new Array();
                    dojo.query('.published-files-of-organisation-list-table  input[type=checkbox]:checked').forEach(function (tag) {
                        ids.push(tag.value);
                    });
                    dojo.query('#organisation_file_ids').attr('value', ids.join(","));

                    if (confirm("Are you sure you want to publish the files in IATI Registry?")) {
                        dojo.byId('publish-to-registry-organisation').submit();
                    }
                }
            },

            ".hide-div": {
                "found": function (ele) {
                    var b = ele.parentNode;
                    dojo.query(b).style('display', 'none');
                }
            },
            "select.vocabulary_value": {
                "found": function (ele) {
                    var formWrapper = ele.parentNode.parentNode;
                    if (ele.value == '' || ele.value == 3) {
                        dojo.query('.non_dac_code', formWrapper).attr('value', '');
                        dojo.query('.non_dac_code', formWrapper).parent().style('display', 'none');
                        dojo.query('.sector_value', formWrapper).parent().style('display', 'block');
                    } else {
                        dojo.query('.sector_value', formWrapper).attr('value', '');
                        dojo.query('.sector_value', formWrapper).parent().style('display', 'none');
                        dojo.query('.non_dac_code', formWrapper).parent().style('display', 'block');
                    }
                },

                "onchange": function (evt) {
                    var selected = getTarget(evt);
                    var formWrapper = selected.parentNode.parentNode;
                    if (selected.value == '' || selected.value == 3) {
                        dojo.query('.non_dac_code', formWrapper).attr('value', '');
                        dojo.query('.non_dac_code', formWrapper).parent().style('display', 'none');
                        dojo.query('.sector_value', formWrapper).parent().style('display', 'block');
                    } else {
                        dojo.query('.sector_value', formWrapper).attr('value', '');
                        dojo.query('.sector_value', formWrapper).parent().style('display', 'none');
                        dojo.query('.non_dac_code', formWrapper).parent().style('display', 'block');
                    }
                }

            },
            "#organisation_username": {
                "found": function (ele) {
                    if (ele.value == '') {
                        dojo.query('#admin_username').attr('value', '');
                        dojo.query('#admin_username').attr('readonly', 'false');
                    } else {
                        dojo.query('#admin_username').attr('value', ele.value + '_admin');
                        dojo.query('#admin_username').attr('readonly', 'true');
                    }
                },

                "onchange": function (evt) {
                    var selected = getTarget(evt);
                    if (selected.value == '') {
                        dojo.query('#admin_username').attr('value', '');
                        dojo.query('#admin_username').attr('readonly', 'false');
                    } else {
                        dojo.query('#admin_username').attr('value', selected.value + '_admin');
                        dojo.query('#admin_username').attr('readonly', 'true');
                    }
                },

                "onkeyup": function (evt) {
                    dojo.query('#organisation_username').attr('value', getTarget(evt).value.replace(" ", ""));
                    var selected = getTarget(evt);
                    if (selected.value == '') {
                        dojo.query('#admin_username').attr('value', '');
                        dojo.query('#admin_username').attr('readonly', 'false');
                    } else {
                        dojo.query('#admin_username').attr('value', selected.value + '_admin');
                        dojo.query('#admin_username').attr('readonly', 'true');
                    }
                }
            },

            ".check-uncheck": {
                "onclick": function (evt) {
                    var value = dojo.query(".check-uncheck").html();
                    var checklist = dojo.query('input[type=checkbox]');
                    if (value === "Check All") {
                        var a = dojo.query(".check-uncheck").html('Uncheck All');
                        checkUncheck(evt, checklist, true);

                    }
                    if (value === "Uncheck All") {
                        var a = dojo.query(".check-uncheck").html('Check All');
                        checkUncheck(evt, checklist, false)
                    }
                }
            },

            // Help tooltip display.
            ".help": {
                "found": function (ele) {
                    var node = ele;
                    var classname = dojo.attr(node, 'class');
                    var classes = classname.split(' ');
                    var elementname = classes[1];
                    helpdialog = new dijit.TooltipDialog({
                        href: APP_BASEPATH + "/wep/get-help-message?element=" + elementname,
                        style: "width: 320px",
                        autofocus: false
                    });
                    //get html of the element
                    var element = new dojo.NodeList(node);
                    var temp = element.clone();
                    var tempWrapper = dojo.create("div");
                    tempWrapper.appendChild(temp[0]);
                    var eleHtml = tempWrapper.innerHTML;
                    var helpDrop = new my.dropDown({
                        label: eleHtml,
                        popup: helpdialog
                    }, node);
                    /*
                        //get message for the element
                        dojo.xhrPost({
                            url: APP_BASEPATH + "/wep/get-help-message",
                            handleAs: "json",
                            timeout: 10000,
                            content: {
                                "element": elementname
                            },
                            load: function (data) {
                                helpdialog = new dijit.TooltipDialog({
                                    content: data,
                                    style: "width: 320px",
                                    autofocus: false
                                });
                                dijit.popup.open({
                                    popup: helpdialog,
                                    around: node
                                });
                            },
                            error: function (err) {
                                console.log(err);
                            }
                        });
                    */
                }
            },

            ".login-parent": {
                "onclick": function (evt) {
                    if (dojo.query(this).attr('class') == "login-parent active") {
                        //dojo.query('#login-hidden-overlay').style('display', 'block');
                        loginSlide(this);
                    } else {
                        //dojo.query('#login-hidden-overlay').style('display', 'none');
                        loginSlideDown(this);
                    }
                    evt.preventDefault();
                }
            },

            "#user-login": {
                "onsubmit": function (evt) {
                    //unset error messages
                    dojo.byId('username-error').innerHTML = "";
                    dojo.byId('password-error').innerHTML = "";

                    user = dojo.attr('username', 'value');
                    password = dojo.attr('password', 'value');
                    if (user && password) {
                        return;
                    }

                    evt.preventDefault();
                    if (!user) {
                        dojo.byId('username-error').innerHTML = "Username cannot be empty";
                    }
                    if (!password) {
                        dojo.byId('password-error').innerHTML = "Password cannot be empty";
                    }
                    return;
                }
            },

            //function to animate the support form
            "#support-container .support": {
                "onclick": function (evt) {
                    if (dojo.query(this).attr('class') == "support active") {
                        supportDown(this);
                    } else {
                        supportUp(this);
                    }
                }
            },

            //validate submit form
            "#support-form #support_submit": {
                "onclick": function (evt) {
                    var flag = validateSupport();
                    if (flag == 0) {
                        evt.preventDefault();
                    }
                }
            },

            //focus on support input fields
            "#support-form input": {
                "onclick": function (evt) {
                    if (dojo.query(this).attr('class') == 'error') {
                        dojo.query(this).removeClass('error');
                        dojo.query(this).val('');
                    }
                }
            },

            //focus on support textarea fields
            "#support-form #support_query": {
                "onclick": function (evt) {
                    if (dojo.query(this).attr('class') == 'error') {
                        dojo.query(this).removeClass('error');
                        dojo.query(this).val('');
                    }
                }
            },

            //function to close support form when clicked else were
            "#hidden-overlay": {
                "onclick": function (evt) {
                    if ((dojo.query("#hidden-overlay").style('display')[0]) == 'block') {
                        supportDown('.support');
                    }
                }
            },

            //function to show popup in index page
            "#about-morelink": {
                "onclick": function (evt) {
                    dojo.query(".popup-wrapper").style('display', 'block');
                    evt.preventDefault();
                }
            },

            //function to close popup
            ".close-link": {
                "onclick": function (evt) {
                    dojo.query(".popup-wrapper").style('display', 'none');
                }
            },

            //Javascript validate contact form
            "#contact-form #send": {
                "onclick": function (evt) {
                    var flag = validateContact();
                    if (flag == 0) {
                        evt.preventDefault();
                    }
                }
            },

            //Remove error class from contact input on focus
            "#contact-form input": {
                "onclick": function (evt) {
                    if (dojo.query(this).attr('class') == 'error') {
                        dojo.query(this).removeClass('error');
                        dojo.query(this).val('');
                    }
                }
            },

            //Remove error class from contact message on focus
            "#contact-form #message": {
                "onclick": function (evt) {
                    if (dojo.query(this).attr('class') == 'error') {
                        dojo.query(this).removeClass('error');
                        dojo.query(this).val('');
                    }
                }
            },
            //Add new activity
            //generate iati identifier from reporting org and activity identifier
            "#activity_identifier": {
                "onkeyup": function (evt) {
                    var selected = getTarget(evt);
                    var identifier = selected.value.replace(/ /g, '');
                    var repOrg = dojo.query('#reporting_org').attr('value');
                    dojo.query('#iati_identifier_text').attr('value', repOrg + '-' + identifier);
                }
            },

            //Edit activity
            //generate iati identifier from reporting org and activity identifier
            "#IatiIdentifier-activity_identifier": {
                "onkeyup": function (evt) {
                    var selected = getTarget(evt);
                    var identifier = selected.value.replace(/ /g, '');
                    var repOrg = dojo.query('#IatiIdentifier-reporting_org').attr('value');
                    dojo.query('#IatiIdentifier-text').attr('value', repOrg + '-' + identifier);
                }
            },

            ".delete-files": {
                "onclick": function (evt) {
                    evt.preventDefault();
                    var msg;
                    if (dojo.hasClass(this, 'published')) {
                        msg = "<p>This xml file is linked from IATI Registry. People might be using this file. Are you sure you want to delete it? </p>";
                    } else {
                        msg = "<p>Are you sure you want to delete the file?</p>";
                    }
                    msg += '<p style="margin-left:100px"><button dojoType="dijit.form.Button" type="button" id="cd-ok">OK</button>';
                    msg += '<button dojoType="dijit.form.Button" type="button" id="cd-cancel">Cancel</button></p>';
                    // Destroy all dialog box before creating new. Used to remove dialog box remaining after clicking close.
                    dojo.query('.dijitDialog').forEach(dojo.destroy);

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
                        var url = dojo.query(getTarget(evt)).attr('href');
                        window.location = url;
                    });

                    confirmDlg.show();
                }
            },
            ".inline-edit-form": {
                "found": function (ele) {
                    dojo.query(ele).style('display', 'none');
                },

                "onsubmit": function (evt) {
                    evt.preventDefault();
                    var node = getTarget(evt);
                    var id = dojo.query('[name=id]', node).val();
                    var msg = dojo.query('[name=message]', node).val();

                    dojo.xhrPost({
                        url: APP_BASEPATH + "/admin/edit-help-message",
                        handleAs: "text",
                        timeout: 10000,
                        content: {
                            "id": id,
                            "message": msg
                        },
                        load: function (data) {
                            var info = dojo.query(node.parentNode.parentNode).children('.value-info');
                            info.style('display', 'block');
                            info.innerHTML(msg);
                            dojo.query(node.parentNode).style('display', 'none');
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });

                }
            },

            ".value-info": {
                "onclick": function (evt) {
                    var node = getTarget(evt);
                    dojo.query(node).style('display', 'none');
                    dojo.query('.inline-edit-form', node.parentNode).style('display', 'block');
                }
            },

            ".inline-cancel": {
                "onclick": function (evt) {
                    var node = getTarget(evt).parentNode.parentNode.parentNode;
                    dojo.query('.value-info', node).style('display', 'block');
                    dojo.query('.inline-edit-form', node).style('display', 'none');
                }
            },

            "#account_identifier": {
                "onkeyup": function (evt) {
                    var identifier = getTarget(evt).value.replace(/ /g, '');
                    dojo.query('#user_name').attr('value', identifier + "_admin");
                }
            },

            "#group_identifier": {
                "onkeyup": function (evt) {
                    var identifier = getTarget(evt).value.replace(/ /g, '');
                    dojo.query('#user_name').attr('value', identifier + "_group");
                }
            },

            ".delete-item": {
                'onclick': function (evt) {
                    if (!confirm("Are you sure you want to delete this?")) {
                        evt.preventDefault();
                    }
                }
            },

            ".non-required-element": {
                "found": function (ele) {
                    dojo.query('div:first-child', ele).style('display', 'none');
                }
            },

            ".non-required-element >legend": {
                "onclick": function (evt) {
                    var ele = getTarget(evt);
                    var node = dojo.query("div:first-child", ele.parentNode);
                    if (node.style('display') == 'block' || node.style('display') == '') {
                        node.style('display', 'none');
                    } else {
                        node.style('display', 'block');
                    }
                }
            },

            '.add-element': {
                'onclick': function (evt) {
                    evt.preventDefault();
                    var node = new dojo.NodeList(getTarget(evt));
                    var value = node.attr('value')[0];
                    var url = APP_BASEPATH + "/wep/clone-node";

                    var wrapperNode = dojo.query(getTarget(evt)).parents(".element-wrapper").first();
                    var lastNode = wrapperNode.children('fieldset').last().query(".form-wrapper").last();
                    var input = lastNode.query('input').attr('id');
                    var refEle = input[0];
                    refEle = refEle.replace(/-\w+$/, '');
                    // prepare input object for get parameter
                    var data = new Object();
                    data['class'] = value;
                    data['refEle'] = refEle;

                    dojo.xhrGet({
                        url: url,
                        handleAs: 'text',
                        content: data,
                        load: function (data) {
                            dojo.place(data, getTarget(evt).parentNode, "before");
                            dojo.behavior.apply();
                        },
                        error: function (err) {

                        }
                    });
                    //*/
                }
            },

            ".remove-element": {
                "onmouseover": function (evt) {
                    var node = getTarget(evt).parentNode.parentNode;
                    dojo.addClass(node, 'remove-highlight');
                },

                "onmouseout": function (evt) {
                    var node = getTarget(evt).parentNode.parentNode;
                    dojo.removeClass(node, 'remove-highlight');
                },

                "onclick": function (evt) {
                    evt.preventDefault();
                    var removeNode = getTarget(evt).parentNode;
                    var removeUrl = dojo.attr(getTarget(evt), 'href');
                    //var grandParent = new dojo.NodeList(getTarget(evt).parentNode.parentNode);
                    //var parentNode = new dojo.NodeList(getTarget(evt).parentNode);
                    var msg = '<div><p>Are you sure you want to delete this item?</p>';
                    msg += '<p>This will remove all the subitems(if any) as well.</p>';
                    msg += '<p style="margin-left:100px"><button dojoType="dijit.form.Button" type="button" id="cd-ok">OK</button>';
                    msg += '<button dojoType="dijit.form.Button" type="button" id="cd-cancel">Cancel</button></p>';

                    // Destroy all dialog box before creating new. Used to remove dialog box remaining after clicking close.
                    dojo.query('.dijitDialog').forEach(dojo.destroy);

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
                        var parentNode = dojo.NodeList(removeNode.parentNode);
                        var removeId = dojo.attr(parentNode.query('input[type="hidden"]')[0], 'value');
                        //console.log(grandParent.children('fieldset').length);
                        if (parseInt(removeId)) {
                            dojo.xhrGet({
                                url: removeUrl,
                                handleAs: 'text',
                                content: {
                                    "id": removeId
                                },
                                load: function (data) {
                                    //console.log(data);
                                    if (data == 'success') {
                                        if (grandParent.children('.form-wrapper').length > 1) {
                                            dojo.destroy(parentNode[0]);
                                        } else {
                                            window.location.reload();
                                        }
                                    } else {
                                        messageDialog("Error", data);
                                    }
                                },
                                error: function (data) {
                                    //console.log(data);
                                    messageDialog("Error", "Something went wrong! Please try again");
                                }
                            });
                        } else {
                            if (grandParent.children('.form-wrapper').length > 1) {
                                dojo.destroy(parentNode[0]);
                            }
                        }

                    });

                    confirmDlg.show();
                }
            },

            ".collapsed": {
                "found": function (ele) {
                    dojo.query('dl', ele).style('display', 'none');
                }
            },

            "#update-reporting-org": {
                "onclick": function (evt) {
                    var Id = dojo.attr(dojo.query('input[name=ReportingOrg_id]')[0], 'value');
                    dojo.xhrPost({
                        url: APP_BASEPATH + "/wep/update-reporting-org",
                        handleAs: 'text',
                        content: {
                            "id": Id
                        },
                        load: function (data) {
                            window.location.reload();
                        },
                        error: function (data) {
                            messageDialog("Error", "Something went wrong! Please try again");
                        }
                    });
                }
            },

            ".simplified-add-more": {
                "onclick": function (evt) {
                    evt.preventDefault();
                    var node = new dojo.NodeList(getTarget(evt));

                    var value = node.attr('value')[0];
                    var url = APP_BASEPATH + "/simplified/default/get-form";
                    var wrapperNode = dojo.query(getTarget(evt)).parents(".zend_form").first();
                    var lastNode = wrapperNode.query(".form-wrapper").last();

                    var input = lastNode.query('input').attr('id');
                    var refEle = input[0];
                    refEle = refEle.replace(/-\w+$/, '');

                    // prepare input object for get parameter
                    var data = new Object();
                    data['class'] = value;
                    data['refEle'] = refEle;

                    dojo.xhrGet({
                        url: url,
                        handleAs: 'text',
                        content: data,
                        load: function (data) {
                            dojo.place(data, getTarget(evt).parentNode, "before");
                            dojo.behavior.apply();
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                }
            },

            ".simplified-remove-element": {
                "onmouseover": function (evt) {
                    var node = getTarget(evt).parentNode.parentNode;
                    dojo.addClass(node, 'remove-highlight');
                },

                "onmouseout": function (evt) {
                    var node = getTarget(evt).parentNode.parentNode;
                    dojo.removeClass(node, 'remove-highlight');
                },
                'onclick': function (evt) {

                    var msg = '<div><p>Are you sure you want to delete this element?</p>';
                    msg += '<p style="margin-left:100px"><button dojoType="dijit.form.Button" type="button" id="cd-ok">OK</button>';
                    msg += '<button dojoType="dijit.form.Button" type="button" id="cd-cancel">Cancel</button></p>';

                    // Destroy all dialog box before creating new. Used to remove dialog box remaining after clicking close.
                    dojo.query('.dijitDialog').forEach(dojo.destroy);

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
                        var removeUrl = APP_BASEPATH + "/simplified/default/remove-element";

                        var removeNode = getTarget(evt).parentNode;
                        var wrapperNode = dojo.NodeList(removeNode.parentNode);
                        var grandParent = dojo.NodeList(removeNode.parentNode.parentNode);
                        var removeId = dojo.attr(wrapperNode.query('input[type="hidden"]')[0], 'value');
                        var className = dojo.attr(getTarget(evt), 'value');
                        if (parseInt(removeId)) {
                            dojo.xhrGet({
                                url: removeUrl,
                                handleAs: 'text',
                                content: {
                                    "id": removeId,
                                    "classname": className
                                },
                                load: function (data) {
                                    //console.log(data);
                                    if (data == 'success') {
                                        if (grandParent.children('.form-wrapper').length > 1) {
                                            dojo.destroy(wrapperNode[0]);
                                        } else {
                                            window.location.reload();
                                        }
                                    } else {
                                        messageDialog("Error", data);
                                    }
                                },
                                error: function (data) {
                                    //console.log(data);
                                    messageDialog("Error", "Something went wrong! Please try again");
                                }
                            });
                        } else {
                            if (grandParent.children('.form-wrapper').length > 1) {
                                dojo.destroy(wrapperNode[0]);
                            }
                        }
                    });

                    confirmDlg.show();
                }
            },

            ".change-state-help": {
                'found': function (ele) {
                    var msg;
                    msg = "<div class='state-help-popup-info-wrapper'>"; // main wrapper
                    msg += "<div class='state-help-title'><span class='info-image'></span>Aidstream Activity States</div>"; // title
                    // message body
                    msg += "<div class= 'state-help-body'><p>Aidstream maintains a simple workflow to ensure that the activities are properly verified before it gets registered in IATI Registry for public view. The following states are maintained in the activities.</p>";
                    msg += "<ul class='states-list'><li>Draft</li><li>Completed</li><li>Verified</li><li>Published</li></ul>";
                    msg += "<p>When the activity is in any one of the states, any further changes in the activity will return its state back to Draft. The entire flow has to be followed before the changes get updated in the activty xml files and registered in IATI Registry. It is recommended that different users are given access to each of these roles for the workflow.</p>"
                    msg += "</div></div>"; // closing body and main div
                    var stateHelpDialog = new dijit.TooltipDialog({
                        content: msg,
                        style: 'width:590px',
                        autofocus: false
                    });
                    //get html of the element
                    var element = new dojo.NodeList(ele);
                    var temp = element.clone();
                    var tempWrapper = dojo.create("div");
                    tempWrapper.appendChild(temp[0]);
                    var eleHtml = tempWrapper.innerHTML;

                    var drop = new my.dropDown({
                        label: eleHtml,
                        popup: stateHelpDialog,
                        orient: ['TR']
                    }, ele);
                }
            },

            ".change-organisation-state-help": {
                'found': function (ele) {
                    var msg;
                    msg = "<div class='state-help-popup-info-wrapper'>"; // main wrapper
                    msg += "<div class='state-help-title'><span class='info-image'></span>Aidstream Organisation File States</div>"; // title
                    // message body
                    msg += "<div class= 'state-help-body'><p>Aidstream maintains a simple workflow to ensure that the organisation files are properly verified before it gets registered in IATI Registry for public view. The following states are maintained in the organisation file.</p>";
                    msg += "<ul class='states-list'><li>Draft</li><li>Completed</li><li>Verified</li><li>Published</li></ul>";
                    msg += "<p>When the organisation file is in any one of the states, any further changes in the organisation file will return its state back to Draft. The entire flow has to be followed before the changes get updated in the organisation xml files and registered in IATI Registry. It is recommended that different users are given access to each of these roles for the workflow.</p>"
                    msg += "</div></div>"; // closing body and main div
                    var stateHelpDialog = new dijit.TooltipDialog({
                        content: msg,
                        style: 'width:590px',
                        autofocus: false
                    });
                    //get html of the element
                    var element = new dojo.NodeList(ele);
                    var temp = element.clone();
                    var tempWrapper = dojo.create("div");
                    tempWrapper.appendChild(temp[0]);
                    var eleHtml = tempWrapper.innerHTML;

                    var drop = new my.dropDown({
                        label: eleHtml,
                        popup: stateHelpDialog,
                        orient: ['TR']
                    }, ele);
                }
            },

            ".v2-add-element": {
                "onclick": function (evt) {
                    evt.preventDefault();
                    var node = new dojo.NodeList(getTarget(evt));

                    var value = node.attr('value')[0];
                    var url = APP_BASEPATH + "/ajax/get-form";
                    var wrapperNode = dojo.query(getTarget(evt).parentNode.parentNode); //dojo.query(getTarget(evt)).parentNode);
                    var lastNode = wrapperNode.query(">.form-wrapper").last();
                    var input = lastNode.query('input').attr('id');
                    var refEle = input[0];
                    refEle = refEle.replace(/-\w+$/, '');

                    // prepare input object for get parameter
                    var data = new Object();
                    data['classname'] = value;
                    data['refele'] = refEle;

                    dojo.xhrGet({
                        url: url,
                        handleAs: 'text',
                        content: data,
                        load: function (data) {
                            dojo.place(data, getTarget(evt).parentNode, "before");
                            dojo.behavior.apply();
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                }
            },

            ".v2-remove-element": {
                "onmouseover": function (evt) {
                    var node = dojo.query(getTarget(evt)).parents(".form-wrapper").first();
                    dojo.addClass(node[0], 'remove-highlight');
                },

                "onmouseout": function (evt) {
                    var node = dojo.query(getTarget(evt)).parents(".form-wrapper").first();
                    dojo.removeClass(node[0], 'remove-highlight');
                },

                "onclick": function (evt) {
                    evt.preventDefault();
                    var removeNode = getTarget(evt).parentNode;
                    var removeUrl = APP_BASEPATH + "/ajax/remove-form";
                    var classname = dojo.attr(getTarget(evt), 'value');

                    //var grandParent = new dojo.NodeList(getTarget(evt).parentNode.parentNode);
                    //var parentNode = new dojo.NodeList(getTarget(evt).parentNode);
                    var msg = '<div><p>Are you sure you want to delete this item?</p>';
                    msg += '<p>This will remove all the subitems(if any) as well.</p>';
                    msg += '<p style="margin-left:100px"><button dojoType="dijit.form.Button" type="button" id="cd-ok">OK</button>';
                    msg += '<button dojoType="dijit.form.Button" type="button" id="cd-cancel">Cancel</button></p>';

                    // Destroy all dialog box before creating new. Used to remove dialog box remaining after clicking close.
                    dojo.query('.dijitDialog').forEach(dojo.destroy);

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

                        var parentNode = dojo.NodeList(removeNode.parentNode);
                        var removeId = dojo.attr(parentNode.query('input[type="hidden"]')[0], 'value');
                        if (parseInt(removeId)) {
                            dojo.xhrGet({
                                url: removeUrl,
                                handleAs: 'text',
                                content: {
                                    "id": removeId,
                                    "classname": classname
                                },
                                load: function (data) {
                                    //console.log(data);
                                    if (grandParent.children('.form-wrapper').length > 1) {
                                        dojo.destroy(parentNode[0]);
                                    } else {
                                        window.location.reload();
                                    }
                                },
                                error: function (data) {
                                    //console.log(data);
                                    messageDialog("Error", "Something went wrong! Please try again");
                                }
                            });
                        } else {
                            if (grandParent.children('.form-wrapper').length > 1) {
                                dojo.destroy(parentNode[0]);
                            }
                        }

                    });

                    confirmDlg.show();
                    evt.preventDefault();
                }
            },

            ".view-element-link": {
                'onclick': function (evt) {
                    var node = getTarget(evt);
                    var url = dojo.attr(node, 'value');
                    var dialog = dijit.Dialog({
                        title: "Element Detail",
                        href: url,
                        style: "width:650px;",
                        parseOnLoad: true
                    });
                    dialog.attr('class', 'view-element-dialogbox');

                    dialog.show();
                }
            },

            ".delete-element-link": {
                'onclick': function (evt) {
                    evt.preventDefault();
                    var msg = '<div><p>Are you sure you want to delete this element?</p>';
                    msg += '<p>This will remove all the sub elements(if any) as well.</p>';

                    msg += '<p style="margin-left:100px"><button dojoType="dijit.form.Button" type="button" id="cd-ok">OK</button>';
                    msg += '<button dojoType="dijit.form.Button" type="button" id="cd-cancel">Cancel</button></p>';
                    // Destroy all dialog box before creating new. Used to remove dialog box remaining after clicking close.
                    dojo.query('.dijitDialog').forEach(dojo.destroy);

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
                        var url = dojo.query(getTarget(evt)).attr('href');
                        window.location = url;
                    });

                    confirmDlg.show();

                }
            },

            ".v2-result-add-element": {
                "onclick": function (evt) {
                    evt.preventDefault();
                    var node = new dojo.NodeList(getTarget(evt));

                    var value = node.attr('value')[0];
                    var url = APP_BASEPATH + "/ajax/get-form";
                    var wrapperNode = dojo.query(getTarget(evt).parentNode.parentNode.parentNode);
                    var lastNode = wrapperNode.query(">.elements-wrapper").last();
                    var input = lastNode.query('input').attr('id');
                    var refEle = input[0];
                    refEle = refEle.replace(/-\w+$/, '');

                    // prepare input object for get parameter
                    var data = new Object();
                    data['classname'] = value;
                    data['refele'] = refEle;

                    dojo.xhrGet({
                        url: url,
                        handleAs: 'text',
                        content: data,
                        load: function (data) {
                            wrapperNode.append(data);
                            dojo.behavior.apply();
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                }
            },

            ".v2-result-remove-element": {
                "onmouseover": function (evt) {
                    var node = dojo.query(getTarget(evt)).parents(".elements-wrapper").first();
                    dojo.addClass(node[0], 'remove-highlight');
                },

                "onmouseout": function (evt) {
                    var node = dojo.query(getTarget(evt)).parents(".elements-wrapper").first();
                    dojo.removeClass(node[0], 'remove-highlight');
                },

                "onclick": function (evt) {
                    evt.preventDefault();
                    var removeNode = getTarget(evt).parentNode;
                    var removeUrl = APP_BASEPATH + "/ajax/remove-form";
                    var classname = dojo.attr(getTarget(evt), 'value');

                    //var grandParent = new dojo.NodeList(getTarget(evt).parentNode.parentNode);
                    //var parentNode = new dojo.NodeList(getTarget(evt).parentNode);
                    var msg = '<div><p>Are you sure you want to delete this item?</p>';
                    msg += '<p>This will remove all the subitems(if any) as well.</p>';
                    msg += '<p style="margin-left:100px"><button dojoType="dijit.form.Button" type="button" id="cd-ok">OK</button>';
                    msg += '<button dojoType="dijit.form.Button" type="button" id="cd-cancel">Cancel</button></p>';

                    // Destroy all dialog box before creating new. Used to remove dialog box remaining after clicking close.
                    dojo.query('.dijitDialog').forEach(dojo.destroy);

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

                        var parentNode = dojo.NodeList(removeNode.parentNode);
                        var removeId = dojo.attr(parentNode.query('input[type="hidden"]')[0], 'value');
                        if (parseInt(removeId)) {
                            dojo.xhrGet({
                                url: removeUrl,
                                handleAs: 'text',
                                content: {
                                    "id": removeId,
                                    "classname": classname
                                },
                                load: function (data) {
                                    if (grandParent.children('.elements-wrapper').length > 1) {
                                        dojo.destroy(parentNode[0]);
                                    } else {
                                        window.location.reload();
                                    }
                                },
                                error: function (data) {
                                    //console.log(data);
                                    messageDialog("Error", "Something went wrong! Please try again");
                                }
                            });
                        } else {
                            if (grandParent.children('.elements-wrapper').length > 1) {
                                dojo.destroy(parentNode[0]);
                            }
                        }

                    });

                    confirmDlg.show();
                    evt.preventDefault();
                }
            },

            ".close-dialog-button": {
                'onclick': function (evt) {
                    var node = dojo.NodeList(getTarget(evt));
                    var parent = node.parents('.custom-tooltip-dialog');
                    parent.style('display', 'none');
                }
            },

            ".close": {
                'onclick': function (evt) {
                    var node = getTarget(evt);
                    var state = dojo.attr(node, 'value');
                    dojo.xhrPost({
                        url: APP_BASEPATH + "/ajax/change-state",
                        handleAs: "text",
                        content: {
                            id: state
                        }
                    });
                }
            },

            "#change_state": {
                "onclick": function (evt) {
                    var node = getTarget(evt);
                    if (node.value == 'Published') {
                        evt.preventDefault();
                        var form = node.form.name;
                        if (form == 'iati_activity_change_status') {
                            var msg = "The activity will be published. The already published activities will ";
                                msg += "be republished. Are you sure you want to publish the activities?";

                            new confirmDialog('', msg, 'Yes, publish the activities', "Don't publish", function () {
                                dojo.byId('iati_activity_change_status').submit();
                            });
                        } else {
                            var msg = "The organisation data will be published. The already published organisation data will ";
                                msg += "be republished. Are you sure you want to publish the organisation data?";

                            new confirmDialog('', msg, 'Yes, publish the organisation data', "Don't publish", function () {
                                dojo.byId('iati_organisationdata_change_status').submit();
                            });
                        }
                    }
                }
            },

            '#upload-document-form': {
                "onsubmit": function (evt) {
                    evt.preventDefault();
                    var node = getTarget(evt);

                    dojo.io.iframe.send({
                        url: APP_BASEPATH + "/ajax/document-upload",
                        form: "upload-document-form",
                        handleAs: "html",
                        handle: function (response, ioArgs) {
                            uploadDialog.set('content', response.body.innerHTML);
                            uploadDialog.show();
                            dojo.behavior.apply();
                        },
                        error: function (err, ioArgs) {
                            console.log(err);
                            console.log(ioArgs);
                        }
                    });
                }
            },

            '.upload-here': {
                "onclick": function (evt) {
                    var node = getTarget(evt);
                    evt.preventDefault();
                    targetNode = dojo.query('textarea', node.parentNode.parentNode);

                    dojo.xhrGet({
                        url: APP_BASEPATH + "/ajax/document-upload",
                        handleAs: "text",
                        timeout: 10000,
                        content: '',
                        load: function (data) {
                            uploadDialog = new dijit.Dialog({
                                parseOnLoad: true,
                                content: data,
                                style: "width: 520px;",
                                autofocus: true
                            });
                            uploadDialog.attr('class', 'document-dialogbox');
                            uploadDialog.show();
                            dojo.behavior.apply();
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });

                }
            },

            ".use-this": {
                "onclick": function (evt) {
                    evt.preventDefault();
                    var node = getTarget(evt);
                    var url = dojo.attr(getTarget(evt), 'href');
                    targetNode.attr('value', url);
                    uploadDialog.hide();
                }
            },

            ".existing-doc": {
                "onclick": function (evt) {
                    var node = getTarget(evt);
                    evt.preventDefault();
                    targetNode = dojo.query('textarea', node.parentNode.parentNode);

                    dojo.xhrGet({
                        url: APP_BASEPATH + "/ajax/previous-documents",
                        handleAs: "text",
                        timeout: 10000,
                        content: '',
                        load: function (data) {
                            uploadDialog = new dijit.Dialog({
                                parseOnLoad: true,
                                content: data,
                                style: "width: 520px;",
                                autofocus: true
                            });
                            uploadDialog.attr('class', 'document-dialogbox');
                            uploadDialog.show();
                            dojo.behavior.apply();
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                }
            },

            ".pos-map": {
                "found": function (ele) {
                    var id = dojo.attr(ele, 'id');
                    var lat = dojo.attr(id + '-latitude', 'value');
                    var lng = dojo.attr(id + '-longitude', 'value');
                    if (lat && lng) {
                        var map = initMap(ele, [lat, lng]);
                    } else {
                        var map = initMap(ele, '');
                    }
                }
            },
            ".element-default-toogle-button": {
                "onclick": function (evt) {
                    var elementName = dojo.query(this).attr('value');
                    if (dojo.hasClass(this, 'show')) {
                        dojo.query(this).parents('form').first().query('.default-item.element-' + elementName).removeClass('hidden').addClass('visible');
                        dojo.query(this).removeClass('show');
                        dojo.query(this).addClass('hide');
                        dojo.query(this).innerHTML('Hide Defaults');
                    } else if (dojo.hasClass(this, 'hide')) {
                        dojo.query(this).parents('form').first().query('.default-item.element-' + elementName).removeClass('visible').addClass('hidden');
                        dojo.query(this).removeClass('hide');
                        dojo.query(this).addClass('show');
                        dojo.query(this).innerHTML('Show Defaults');
                    }
                    evt.preventDefault();
                }
            },
            "#change-username": {
                "onclick": function (evt) {
                    evt.preventDefault();
                    var msg = "This action will affect all your users. Are you sure you want to "
                    msg += "change your <b>Organisation User Identifier</b> and in turn your organisation Username prefix?";
                    new confirmDialog('', msg, 'Yes', 'Cancel', function () {
                        dojo.byId('changeusername').submit();
                    });
                }
            },
            '#delete-group': {
                "onclick": function (evt) {
                    evt.preventDefault();
                    var msg = "Are you sure you want to delete this group?";
                    new confirmDialog('', msg, 'Yes', 'Cancel', function(){
                        var url = dojo.query(getTarget(evt)).attr('href');
                        window.location = url;
                    });
                }
            },
            "#block-generate-published-xml-files a": {
                "onclick": function (evt) {
                    evt.preventDefault();
                    var msg = "Are you sure you want to generate the XML file?";
                    new confirmDialog('', msg, 'Yes, Generate and Redirect', 'Cancel', function () {
                        document.location.assign('/admin/generate-published-xml-files');
                    });
                }
            },
            ".use-reporting-org": {
                "onclick": function (evt) {
                    evt.preventDefault();
                    var node = getTarget(evt);
                    var reporting_org_name = dojo.attr(node, 'reporting-org-name');
                    var reporting_org_ref = dojo.attr(node, 'reporting-org-ref');
                    var providerOrgTextNode = dojo.query(evt.target.parentElement.previousElementSibling);
                    var providerOrgIdentifierCodeNode = dojo.query(dojo.byId('Transaction-ProviderOrg-ref'));
                    providerOrgTextNode.attr('value', reporting_org_name);
                    providerOrgIdentifierCodeNode.attr('value', reporting_org_ref);                    
                }
            },
        });

        // End of dojo.behavior.add
        dojo.behavior.apply();
    }

dojo.addOnLoad(initialize);

dojo.ready(function () {
    var checklist = dojo.query('.default-activity-list input[type=checkbox]:checked');
    if (checklist.length == 29) {
        dojo.query(".check-uncheck").html('Uncheck All');
    }

    dojo.query('.currency').forEach(function (node) {
        var amount = dojo.query(node).val();
        if (amount) {
            var num = dojo.number.format(amount, {
                places: 2
            });
            if (num) {
                dojo.query(node).val(num);
            }
        }
    });
    dojo.query(".form-submit").connect("click", function (evt) {
        dojo.query('.currency').forEach(function (node) {
            var amount = dojo.query(node).val();
            if (amount) {
                match = amount.match(/,,,*/g);
                if (!match) {
                    amount = amount.replace(/,/g, "");
                    var num = dojo.number.round(amount, 2);
                    if (num) {
                        dojo.query(node).val(num);
                    }
                }
            }
        });
    });

});