$(document).ready(function (e) {

    checkCompanyOrIndividual();
    $('#upload_pic').on('submit',(function(e) {
       $(this).find(".btn-primary").addClass('m-loader');
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({

            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){
            $(".btn-primary").removeClass('m-loader');
             
                if(data.status==1)
                {
                  errortype = 'success';
                }
                else if(data.status==0 || data.status==2)
                {
                  errortype = 'danger';
                }

                var msg = data.message;
                showAlert(errortype,data.message);

			          $('#profile_pic_data').attr('src',data.uploaded_image);
			          $('#remove_picture').show();
            },
        });

       var savedDob = $('.datetimepickerdobupdate').val();
       
        $('.datetimepickerdobupdate').datetimepicker({
          format: 'YYYY-MM-DD',
          maxDate:new Date(savedDob),
          icons: {
          previous: "fas fa-chevron-left",
          next: "fas fa-chevron-right",
          
        },
      });

    }));

    //change password for investor
    $('#change_password_form').on('submit',(function(e) {
       $(this).find(".btn-primary").addClass('m-loader');
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: $(this).attr('action'),
            data:form.serialize(), // serializes the form's elements.
            success:function(data){
                $(".btn-primary").removeClass('m-loader');
                $("#message").removeClass("alert-danger alert-success");
                $(".ptype").removeClass("is-invalid");

                var message = data.message;

                if(data.status==1)
                {
                    errortype = 'success';
                }
                else if(data.status==0 || data.status==2)
                {
                    errortype = 'danger';
                }

                if(data.status==2)
                {
                    var errors = data.message;
                    var errorString = '<ul>';
                    $.each( errors, function( key, value) {
                        errorString += '<li>' + value + '</li>';
                    });
                    errorString += '</ul>';
                    message = errorString;
                }
              
                showAlert(errortype,message);
                
                
            },
        });
    }));

    //make investment
    //change password for investor
    $('#make_inv').on('submit',(function(e) {
       $(this).find(".btn-primary").addClass('m-loader');
        e.preventDefault();
        var form = $(this);

        var min_inv_amount = $("#min_inv_amount").val();

       if($.isNumeric(min_inv_amount)) 
       {
            $.ajax({
                type:'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: $(this).attr('action'),
                data:form.serialize(), // serializes the form's elements.
                success:function(data){
                    $(".btn-primary").removeClass('m-loader');
                    alert('here');
                    console.log(data);

                },
            });
       }
       else{
        alert('Please provide valid value.');
       } 
        
    }));

    //update invester information

    $('#invest_info').on('submit',(function(e) {
       $(this).find(".btn-primary").addClass('m-loader');
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: $(this).attr('action'),
            data:form.serialize(), // serializes the form's elements.
            success:function(data){
                $(".btn-primary").removeClass('m-loader');
                $("#message").removeClass("alert-danger alert-success");
                $(".ptype").removeClass("is-invalid");

                var message  = data.message;

                if(data.status==1)
                {
                    errortype = 'success';
                }
                else if(data.status==0 || data.status==2)
                {
                    errortype = 'danger';
                }

                if(data.status==2)
                {
                    var errors = data.message;
                    var errorString = '<ul>';
                    $.each( errors, function( key, value) {
                        errorString += '<li>' + value + '</li>';
                    });
                    errorString += '</ul>';
                    message = errorString;
                }

                showAlert(errortype,message);
            },
        });
    }));

    $("#file_upload").on("change", function() {
        
        $("#upload_pic").submit();
    });

    $('#make_payment').on('hidden.bs.modal', function () {

       $('#ach_method')[0].reset();
       $('#check_method')[0].reset();
       $('#wire_method')[0].reset();

    });
    
    //submit for for ach payment method investment
    $('#ach_method,#check_method,#check_method,#wire_method').on('submit',(function(e) {
       $(this).find(".btn-primary").addClass('m-loader');
        e.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){

                $(".btn-primary").removeClass('m-loader');
                //alert(data.message);
                //console.log(data);
                var message = data.message;
                if(data.status==1)
                {
                    errortype = 'success';
                }
                else if(data.status==0 || data.status==2)
                {
                    errortype = 'danger';
                }

                showAlert(errortype,message);

                if(data.status==1)
                {
                    $('#make_payment').modal('toggle');
                }
                

            },
        });
      

    }));
 function checkCompanyOrIndividual()
 {
    var typeOfInvestor = $("#investor_type_update").val();
    if(typeOfInvestor==1)
       {
            $('#contact_name').prop('required',false);
            $("#section_contact_name").hide();
            $('#company_region').prop('required',false);
            $("#section_region_name").hide();
            $('#date_of_birth').prop('required',true);
            $("#section_dob").show();
            
        
       }
       else if(typeOfInvestor==2)
       {
            $('#contact_name').prop('required',true);
            $("#section_contact_name").show();
            $('#company_region').prop('required',true);
            $("#section_region_name").show();
            $('#date_of_birth').prop('required',false);
            $("#section_dob").hide();
            
        
       }
 }
    $("#investor_type_update").on("change", function() {
        
      checkCompanyOrIndividual();

    });

    
    //remove profile picture
    $("#remove_picture").on("click", function() {

        $.ajax({
	            type:'POST',
	            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            url: '/investor/remove_profile_pic',
	            data:{type:1},
	            success:function(data){
					
                $('#profile_pic_data').attr('src',data.uploaded_image);
                $('#remove_picture').hide();

                var message = data.message;

                if(data.status==1)
                {
                  errortype = 'success';
                }
                else if(data.status==0 || data.status==2)
                {
                  errortype = 'danger';
                }

                showAlert(errortype,message);
	            }
        	});
    });

    //get states for a country
    $('#country').on('change',(function() {

    	var selection = $(this).val();
    	if(selection!=0)
    	{
    		$.ajax({
	            type:'POST',
	            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            url: '/investor/get_country',
	            data:{type:1,typeId:selection},
	            success:function(data){
	            	$('#state option:not(:first)').remove();
	            	$('#city option:not(:first)').remove();
                $('#state').append(data.data);
                $('#company_region option:not(:first)').remove();
                $('#company_region').append(data.data);
	            }
        	});
    	}
        
    }));

    $('#state').on('change',(function() {

    	var selection = $(this).val();

  		if(selection!=0)
  		{
        $.ajax({
          type:'POST',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          url: '/investor/get_country',
          data:{type:2,typeId:selection},
          success:function(data){
            $('#city option:not(:first)').remove();
            $('#city').append(data.data);
          }
    	  });
  		}
        
    }));

    $("input[name$='accredited_type']").change(function() {
          
        showType();
    });

    function showType() {

          var value = $( 'input[name=accredited_type]:checked' ).val();
          $('.type_section').hide();
          if(value==1)
          {
            $('#type_yes').show();
          }
          else if(value==2)
          {
            $('#type_no').show();
          }
          else if(value==3)
          {
            $('#type_dont').show();
          }
        }
});

//load more advertisements
function loadMoreInvestments()
{
    
    var searchText = $.trim($("#search_input").val());
    var minAmount = $.trim($("#min_amount").val());
    $("#loadmorebutton").addClass('m-loader');
    $('#loadmorebutton').attr("disabled","disabled");
    var lastId = $('#investment_section div.investment_block:last').attr('data-id');

    $.ajax({
        type:'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '/investor/dashboard',
        data:{lastId:lastId,search:searchText,minAmount:minAmount}, 
        success:function(result){

             $("#load_more").before(result);     
             var totalItems = $("#loadmoredata").val();  
             var items = $("#investment_section").find(".investment_block").length; 
             if(totalItems==items)
             {
                $("#load_more").hide();    
             }
        },

    });

    $("#loadmorebutton").removeClass('m-loader');
    $('#loadmorebutton').removeAttr("disabled");
    
}


jQuery(function ($) {

    var $inputs = $('#search_input,#min_amount');
    $inputs.on('input', function () {
        // Set the required property of the other input to false if this input is not empty.
        $inputs.not(this).prop('required', !$(this).val().length);
    });

});


function showAlert(type,msg)
{
    $.notifyClose();

    $.notify({
     // options
      message: msg
      },{
      // settings
      type: type,
      z_index: 2000,
      placement: {
        from: "top",
        align: "center",
        allow_dismiss: true,
        delay: 500,
        
      },
    });
}