$(document).ready(function(){
	var oldData = $("#fieldset-reporting_org_info :input[value!='']").serialize();

	// if (oldData != '') {
		$('.defaults-forms form').one('submit', function(event){
			var newData = $('#fieldset-reporting_org_info :input').serialize();
			if (oldData != newData) {
				event.preventDefault();
				var confirmDialog = confirm('It seems you have changed your Reporting Org Information. Are you sure you want to continue?');
				if (confirmDialog == true) {
					$(this).attr("action", "settings?btn=ok") 
					$(this).submit();
				} else { 
					$(this).submit();
				}
			} else {
				$(this).submit();
			} 
		});
	// }
});
