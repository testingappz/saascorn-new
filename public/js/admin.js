//get states for a country
    $('#country').on('change',(function() {

    	var selection = $(this).val();
    	if(selection!=0)
    	{
    		$.ajax({
	            type:'POST',
	            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            url: '/get_country_admin_dashboard',
	            data:{type:1,typeId:selection},
	            success:function(data){
	            	$('#state option:not(:first)').remove();
                $('#city option:not(:first)').remove();
	            	$('#company_region option:not(:first)').remove();
                $('#state').append(data.data);
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
              url: '/get_country_admin_dashboard',
              data:{type:2,typeId:selection},
              success:function(data){
                $('#city option:not(:first)').remove();
                $('#city').append(data.data);
              }
          	});
  		}
        
    }));

$(document).ready(function (e) {

    $('#manage_payment_form').on('submit',(function(e) {

         $(this).find(".btn-primary").addClass('m-loader');

         $("#cancelbtn").prop('disabled', true);
         $("#savebtn").prop('disabled', true);

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
                  $("#cancelbtn").prop('disabled', false);
                  $("#savebtn").prop('disabled', false);
               
                  if(data.status==1)
                  {
                    errortype = 'success';
                  }
                  else if(data.status==0 || data.status==2)
                  {
                    errortype = 'danger';
                  }

                  var message = data.message;
                  showAlert(errortype,message);

                if(data.status==1)
                {
                  //For wait 2 seconds
                  setTimeout(function() 
                  {
                    location.reload();  //Refresh page
                  }, 2000);
                }
                  
              },
          });

    }));
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