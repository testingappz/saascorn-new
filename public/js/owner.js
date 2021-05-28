
$(document).ready(function (e) {

  //add new investment
  $("#add_new_project").submit(function (e) {
      $("#btn-submit").addClass('m-loader');
      $('#btn-submit').attr("disabled","disabled");
      return true;

  });

  //resend link of escrow agreement in mail

  $('#sign_agreement1_form,#sign_agreement2_form').on('submit',(function(e) {
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
                //For wait 2 seconds
                setTimeout(function() 
                {
                  location.reload();  //Refresh page
                }, 2000);
              }
                
            },
        });
        
    })); 

  //resend link of rech service agreement in mail 

  function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#file_upload_project_preview').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$("#file_upload_project").change(function() {
  readURL(this);
});

  var removeImg = window.location.origin+'/images/delete.svg';
    $('#upload_picture').on('submit',(function(e) {
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
              var message = data.message;

              if(data.status==1)
              {
                
                $("#profile_pic_data").attr("src",data.uploaded_image);

                errortype = 'success';
              }
              else if(data.status==0 || data.status==2)
              {
                errortype = 'danger';
              }

              showAlert(errortype,message);
                
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
              $(".ptype").removeClass("is-invalid");

                var message = data.message;
                
                if(data.status==2)
                {
                    var errors = data.message;
                    var errorString = '<ul>';
                    $.each( errors, function( key, value) {
                        $("#"+key).addClass("is-invalid");
                        errorString += '<li>' + value + '</li>';
                    });
                    errorString += '</ul>';

                    message = errorString;
                     
                }
               
                if(data.status==1)
                {
                  errortype = 'success';
                }
                else if(data.status==0 || data.status==2)
                {
                  errortype = 'danger';
                }

                showAlert(errortype,message);
                
            },
            /*complete: function(){
              $(this).find(".btn-primary").removeClass('m-loader');
            }*/
        });

        
    }));

    $("#file_upload").on("change", function() {
        $("#upload_picture").submit();
    });

    //remove profile picture
    $("#remove_picture").on("click", function() {

        $.ajax({
	            type:'POST',
	            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            url: '/owner/remove_profile_picture',
	            data:{type:1},
	            success:function(data){
      					var message = data.message;
      					$('#profile_pic_data').attr('src',data.uploaded_image);
      					$('#remove_picture').hide();

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
	            url: '/owner/get_country_data',
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
              url: '/owner/get_country_data',
              data:{type:2,typeId:selection},
              success:function(data){
                $('#city option:not(:first)').remove();
                $('#city').append(data.data);
              }
          	});
  		}
        
    }));

    //get name of all file in add project

    $("#doc_upload").change(function() {
       
        $("#doclist").html('');
        var documentnames = [];
        var fi = document.getElementById('doc_upload');

        if(fi.files.length>5)
        {
          alert("You can select max 5 documents");
          return false;
        }
       // alert(fi.files.length);
        for (var i = 0; i < fi.files.length; ++i) {
            //alert(fi.files.name);
            documentnames.push(fi.files[i].name);
            //alert(fi.files[i].name);
           $("#doclist").append('<div class="list"><h5 class="img_list" data-name="'+fi.files[i].name+'">'+fi.files[i].name+' ('+fi.files[i].size+')</h5><img data-name="'+fi.files[i].name+'" data-type="1" class="delfile" src="'+removeImg+'" alt="delete"  /></div>');
        }
        $("#doclisthide").val(documentnames);
        
    });

    $("#video_upload").change(function() {
       
        var vnames = [];
        $("#vidlist").html('');
        var fi = document.getElementById('video_upload');
        if(fi.files.length>5)
        {
          alert("You can select max 5 videos.");
          return false;
        }
        for (var i = 0; i < fi.files.length; ++i) {
            vnames.push(fi.files[i].name);
            $("#vidlist").append('<div class="list"><h5 class="vid_list" data-name="'+fi.files[i].name+'">'+fi.files[i].name+' ('+fi.files[i].size+')</h5><img data-name="'+fi.files[i].name+'" data-type="2" class="delfile" src="'+removeImg+'" alt="delete"  /></div>');
        }
        $("#vidlisthide").val(vnames);
        
    });

   //ajax to update description of project
   $('#update_desc').on('submit',(function(e) {

      $(this).find(".btn-primary").addClass('m-loader');
        e.preventDefault();

        var form = document.getElementById('update_desc');
        var formData = new FormData(form);
        var investId = $('#project_id').val();
        formData.append("investId", investId);
        
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: $(this).attr('action'),
            data:formData, 
            processData: false,
            contentType: false,
            cache: false,
            success:function(data){
            $(".btn-primary").removeClass('m-loader');
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
                $("#view_description").text(data.data);
                $("#project_desc").val(data.data);
              }
            },


        });

         
    }));

   //ajax to update invest info of project
   $('#update_invest_info').on('submit',(function(e) {
      $(this).find(".btn-primary").addClass('m-loader');
        e.preventDefault();

        var form = document.getElementById('update_invest_info');
        var formData = new FormData(form);
        var investId = $('#project_id').val();
        formData.append("investId", investId);
        
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: $(this).attr('action'),
            data:formData, 
            processData: false,
            contentType: false,
            cache: false,
            success:function(data){
              $(".btn-primary").removeClass('m-loader');
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
                
                $('input[name="minimum_investment"]').val(data.min);
                $('input[name="offering_end_date"]').val(data.offer_date);
                
              }
            },


        });

    }));


   //ajax to update video of project
   $('#add_new_video_form').on('submit',(function(e) {
    $(this).find(".btn-primary").addClass('m-loader');
        e.preventDefault();
        formData = new FormData(this);
        
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: $(this).attr('action'),
            data:formData, 
            processData: false,
            contentType: false,
            cache: false,
            success:function(data){
              $(".btn-primary").removeClass('m-loader');
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
              
            },


        });

        setTimeout(function () {
        location.reload(true);
      }, 2500);
         
    }));

   //ajax to update doc of project
   $('#add_new_doc_form').on('submit',(function(e) {
      $(this).find(".btn-primary").addClass('m-loader');
        e.preventDefault();
        formData = new FormData(this);
        
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: $(this).attr('action'),
            data:formData, 
            processData: false,
            contentType: false,
            cache: false,
            success:function(data){
              $(".btn-primary").removeClass('m-loader');
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
              
            },


        });
       
        setTimeout(function () {
        location.reload(true);
      }, 2500);
         
    }));

   //function to delete videos,docs already uploaded
   
   $('.delete_image').on('click',(function(e) {
  
      var docId = $(this).attr('data-id');
      var type =  $(this).attr('data-type');
      var res = confirm("Are You Sure You Want To Remove This Document?");
      if(res === true) 
      {
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/owner/delete_doc_video',
            data:{type:type,docId:docId}, 
            success:function(data){
              
              if(data.status==1)
              {
                $("#parent_"+docId).remove();
              }
              else
              {
                alert(data.message);
              }
              
            },

        });
       
      }
   }));

   $('#update_proj_det').on('submit',(function(e) {
       $(this).find(".btn-primary").addClass('m-loader');
        e.preventDefault();
        var formData = new FormData(this);
        var investId = $('#project_id').val();
        formData.append("investId", investId);
        
        $.ajax({
            type:'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: $(this).attr('action'),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            success:function(data){

              $(".btn-primary").removeClass('m-loader');

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
                    setTimeout(function () {
                    location.reload(true);
                  }, 2500);
                }
                
            },
        });
    }));

});

$(document).on('click', '.delfile', function() {
  
    var type =   $(this).attr('data-type');
    var name =  $(this).attr('data-name');

    $(this).parent().remove();
    if(type==1)
     {  
        var vnames = [];
        var data = $("#doclisthide").val('');
        $("#doclist .img_list").each(function(e){
          
          vnames.push($(this).attr('data-name'));
        });
        $("#doclisthide").val(vnames);   
     }
     else if(type==2)
     {
        var vnames = [];
        var data = $("#vidlisthide").val('');
        $("#vidlist .vid_list").each(function(e){
          
          vnames.push($(this).attr('data-name'));
        });
        $("#vidlisthide").val(vnames);   
     }
       
});



//load more advertisements
function loadMoreInvestments()
{
  
    var searchText = $.trim($("#search_input").val());
    $("#loadmorebutton").addClass('m-loader');
    $('#loadmorebutton').attr("disabled","disabled");
    var lastId = $('#investment_section div.investment_block:last').attr('data-id');

    $.ajax({
        type:'POST',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: '/owner/dashboard',
        data:{lastId:lastId,search:searchText}, 
        success:function(result){

             $("#load_more").before(result);         
        },

    });

    $("#loadmorebutton").removeClass('m-loader');
    $('#loadmorebutton').removeAttr("disabled");

    
}


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

//load more advertisements
function loadMoreProjectInvestments()
{
    $("#loadmorebutton_project").addClass('m-loader');
    $('#loadmorebutton_project').attr("disabled","disabled");
    var start = $('#track_investment_table >tbody >tr').length;
    var pid = $('#current_inv_id').val();
    var total = $('#total').val();

    if(total>start)
    {
        $.ajax({
          type:'POST',
          headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          url: '/owner/load_more_data',
          data:{start:start,limit:10,pid:pid}, 
          success:function(result){

              $("#track_investment_table").append(result);  
              $("#loadmorebutton_project").removeClass('m-loader');
              $('#loadmorebutton_project').removeAttr("disabled"); 

              var start = $('#track_investment_table >tbody >tr').length; 
              var total = $('#total').val();  

              if(total<=start)  {

                $("#load_more").hide();

              }
          },

      });
    }
    else
    {
      $("#load_more").hide();
    }
}
