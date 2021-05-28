$(document).ready( function () {
	$('#table').DataTable();
	
	$('.active_deactive').click(function(event){

      event.preventDefault();
       var el = this;
       var id = this.id;
       var splitid = id.split("_");
    
       var id = splitid[0];
       var active_deactive = splitid[1];
	   var type = splitid[2];
       // AJAX Request
       $.ajax({
           headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },
         url: 'active_deactive',
         type: 'POST',
         data: { id:id,active_deactive:active_deactive,type:type },
         success: function(response){
      
            if(response == 1){

			    location.reload(true);
	
					
				}
		
		    }
     
		});
        
       });
	   
	   	$('.changestatus').click(function(event){

      event.preventDefault();
       var el = this;
       var id = this.id;
       var splitid = id.split("_");
    
       var id = splitid[0];
       var active_deactive = splitid[1];
	
       // AJAX Request
       $.ajax({
           headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

            },
         url: 'change_project_status',
         type: 'POST',
         data: { id:id,active_deactive:active_deactive},
         success: function(response){
      
            if(response == 1){

			    location.reload(true);
	
					
				}
		
		    }
     
		});
        
       });
	
	
} );
			