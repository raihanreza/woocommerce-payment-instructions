jQuery( document ).ready(function() {
	jQuery('.add-btn').click(function(e) {
     jQuery('.add-pic').slideToggle();  
	 jQuery('#sp_amount_input').hide();
	 jQuery('.sp_amount_lebel').show(); 
    });
	jQuery('.cancel-btn').click(function(e) {
		e.preventDefault();
     jQuery('.add-pic').slideUp();   
    });
	
	jQuery('.add-form').on('input', function() {
	var input=jQuery(this);
	var is_name=input.val();
	if(is_name){input.removeClass("invalid").addClass("valid");}
	else{input.removeClass("valid").addClass("invalid");}
	});
	
	jQuery(document).on('click','.submit-btn',function(e) { 
    //e.preventDefault(); 
	jQuery(".add-form .regular-input").each(function() {
		   var input=jQuery(this);
           var is_name=input.val();
		   if(is_name){input.removeClass("invalid").addClass("valid"); return true;}
    else{input.removeClass("valid").addClass("invalid");  e.preventDefault();}
	});
	});

	
});

function del_confirm()
{
    var r = confirm("If you click ok button, this receiver record will be delete!")
	if (r == true)
	  {
	  return true;
	  }
	else
	  {
	   return false;
	  }
}

jQuery( document ).ready(function() {
	jQuery('#sp_amount').click(function(e) {
		 jQuery('#sp_amount_input').slideToggle(); 
		 jQuery('.sp_amount_lebel').hide(); 
		 jQuery('.add-pic').hide();
    });
});


