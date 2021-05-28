@extends('admin.layouts.admin') 
@section('content')
 <div class="row">
                   
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header">Investment List</h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table_investment" class="table table-striped table-bordered first">
                        <thead>
                            <tr>
                                
								<th>Sno</th>
								<th>Investment Title</th>
								<th>Investor Name</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Details</th>
								
                            </tr>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end basic table  -->
    <!-- ============================================================== -->
</div>
<script type="text/javascript">
  $(document).ready(function () {
    
    $('#table_investment').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax":{
                 "url": "{{ url('investment_list_all') }}",
                 "dataType": "json",
                 "type": "POST",
                 "data":{ _token: "{{csrf_token()}}"}
               },
        "columns": [
            { "data": "Sno" },
            { "data": "Investment Title"},
            { "data": "Investor Name" },
            { "data": "Amount" },
            { "data": "Status" },
            { "data": "Action" }
        ],
       "language": {
            "emptyTable": "No data available."
        },

        "fnRowCallback" : function(nRow, aData, iDisplayIndex){
        $("td:first", nRow).html(iDisplayIndex +1);
        return nRow;
     },

    });
});
</script>				
@endsection
				
				