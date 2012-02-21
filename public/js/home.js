dojo.require('dojo.behavior');
dojo.require('dojo.NodeList-manipulate');
dojo.require('dojo.NodeList-traverse');

var getTarget = function (event) {
    // w3 || IE
    return event.target || event.srcElement;
}

//login slide down
var loginSlideDown = function(target) {
        dojo.query('#login-hidden-overlay').style('display', 'block');
        dojo.query(target).addClass('active');
        dojo.animateProperty({
                node: dojo.byId('user-login'),
                duration: 500,
                properties: {
                    height: {
                              start: "0",
                              end: "98"
                      }
                }
        }).play();
    
        dojo.animateProperty({
                node: dojo.byId('registration-section'),
                duration: 300,
                properties: {
                    height: {
                              start: "0",
                              end: "65"
                      }
                }
        }).play();
}

//login slide up
var loginSlide = function (target) {
        dojo.query('#login-hidden-overlay').style('display', 'none');
        dojo.query(target).removeClass('active');
                dojo.animateProperty({
                node: dojo.byId('user-login'),
                duration: 300,
                properties: {
                    height: {
                              start: "98",
                              end: "0"
                      }
                }
              }).play();
    
                dojo.animateProperty({
                node: dojo.byId('registration-section'),
                duration: 300,
                properties: {
                    height: {
                              start: "65",
                              end: "0"
                      }
                }
              }).play();
}


var validateContact = function(){
	flag = 1;
	if(dojo.query("#contact-form #name").val() == "")
	{
		flag = 0;
		dojo.query("#contact-form #name").addClass('error');
		dojo.query("#contact-form #name").val('Name cannot be empty');
	}
	if(dojo.query("#contact-form #email").val() == "")
	{
		flag = 0;
		dojo.query("#contact-form #email").addClass('error');
		dojo.query("#contact-form #email").val('Email cannot be empty');
	}
	else
	{
		var email = dojo.query("#contact-form #email").val();
		var checkEmail = validEmail(email);
		if(!checkEmail)
		{
			flag = 0;
			dojo.query("#contact-form #email").addClass('error');
			dojo.query("#contact-form #email").val('Please input a valid email address!');
		}
	}
	if(dojo.query("#contact-form #message").val() == "")
	{
		flag = 0;
		dojo.query("#contact-form #message").addClass('error');
		dojo.query("#contact-form #message").val('Message cannot be empty');
		
	}
	else if(dojo.query('#contact-form #message').val().length >= 1000)
	{
				
		flag = 0;
		dojo.query('#contact-form #message').addClass('error');
		dojo.query('#contact-form #message').val('Query is too long. Please use maximum of 1000 characters');
		
	}

	if(flag == 1)
	{
		if((dojo.query('#contact-form #name').attr('class') == 'error') || (dojo.query('#contact-form #email').attr('class') == 'error') || (dojo.query('#contact-form #message"').attr('class') == 'error'))
		{
			flag = 0;
		}
	}
	return flag;
}

var validEmail = function(email){
		var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
			if (filter.test(email))
			{
				return true;
			}
			else{
				return false;
			}
}


function initialize() {
    
    // start of dojo.behavior.add
    dojo.behavior.add({
	".hide-div" : {
	    "found" : function (ele) {
		var b = ele.parentNode;
		dojo.query(b).style('display', 'none');
	    }
	},
	
	".help" : {
	    "onclick" : function (evt) {
		var node = getTarget(evt);
		var classname = dojo.attr(node,'class');
		var classes = classname.split(' ');
		var elementname = classes[1];

		//get message for the element
		dojo.xhrPost({
                    url : APP_BASEPATH + "/wep/get-help-message",
                    handleAs : "json",
		    timeout : 10000,
                    content : {"element":elementname},
                    load : function (data) {
                        helpdialog = new dijit.TooltipDialog({
			    content: data,
			    style: "width: 320px",
			    autofocus: false
			});
			dijit.popup.open({
					 popup : helpdialog,
					 around : node
			});
                    },
                    error : function (err) {
                        console.log(err);
                    }
                });		
	    }
	},
        
	"body" : {
	    "onclick" : function (evt) {
		if(!dojo.hasClass(evt.target, "dijitTooltipDialogPopup"))
		{
		    dojo.query('.dijitTooltipDialogPopup').forEach(dojo.destroy);
		}
	    }
	},
	
	".login-parent" : {
	    "onclick" : function (evt) {
		if(dojo.query(this).attr('class') == "login-parent active"){
			//dojo.query('#login-hidden-overlay').style('display', 'block');
			loginSlide(this);
		} else {
			//dojo.query('#login-hidden-overlay').style('display', 'none');
			loginSlideDown(this);
		}
		evt.preventDefault();
	    }
	},
	
	"#user-login" : {
	    "onsubmit" : function (evt) {
		//unset error messages
		dojo.byId('username-error').innerHTML = "";
		dojo.byId('password-error').innerHTML = "";
		
		user = dojo.attr('username','value');
		password = dojo.attr('password','value');
		if(user && password) {
		    return;
		}
		
		evt.preventDefault();
		if(!user) {
		    dojo.byId('username-error').innerHTML = "Username cannot be empty";
		}
		if(!password) {
		    dojo.byId('password-error').innerHTML = "Password cannot be empty";
		}
		return;
	    }
	},


	//function to animate the login form incase of error
	"#index-flash-messages ul.error" : {
		"found" : function(evt){
			dojo.query('.login-parent').addClass('active');
			dojo.query('#user-login #username').val('');
			dojo.query('#user-login #password').val('');
		    	dojo.animateProperty({
				  node: dojo.byId('user-login'),
				  duration: 500,
				  properties: {
				      height: {
						start: "0",
						end: "98"
					}
				  }
				}).play();
                        
                        dojo.animateProperty({
                                node: dojo.byId('registration-section'),
                                duration: 300,
                                properties: {
                                    height: {
                                              start: "0",
                                              end: "65"
                                      }
                                }
                        }).play();
		}
	},

	//function to show popup in index page
	"#about-morelink" : {
		"onclick" : function(evt){
			 dojo.query(".popup-wrapper").style('display' , 'block');
			evt.preventDefault();
		}
	},

	//function to close popup
	".close-link" : {
		"onclick" : function(evt){
			dojo.query(".popup-wrapper").style('display' , 'none');
		}
	},

	//Javascript validate contact form
	"#contact-form #send" : {
            "onclick" : function(evt){
                    var flag = validateContact();
                    if(flag == 0)
                    {
                            evt.preventDefault();
                    }
            }
	},

	//Remove error class from contact input on focus
	"#contact-form input" : {
		"onclick" : function(evt){
			if(dojo.query(this).attr('class') == 'error')
			{
				dojo.query(this).removeClass('error');
				dojo.query(this).val('');
			}
		}
	},

	//Remove error class from contact message on focus
	"#contact-form #message" : {
		"onclick" : function(evt){
			if(dojo.query(this).attr('class') == 'error')
			{
				dojo.query(this).removeClass('error');
				dojo.query(this).val('');
			}
		}
	},
	
	"#account_identifier" : {
	    "onkeyup" : function (evt) {
		var identifier = getTarget(evt).value.replace(/ /g,'');
		dojo.query('#user_name').attr('value', identifier+"_admin");
	    }
	},

	//function to close support form when clicked else were
	"#login-hidden-overlay" : {
		"onclick" : function(evt) {
			if((dojo.query("#login-hidden-overlay").style('display')[0]) == 'block')
			{
				loginSlide('.login-parent');
			}
		}
	},
	
    });

    // End of dojo.behavior.add
    dojo.behavior.apply();
}

dojo.addOnLoad(initialize);
