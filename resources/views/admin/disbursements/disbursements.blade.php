@extends('admin.layouts.admin') 
@section('content')
 <div class="row">
                   
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <h5 class="card-header">Disbursement List
            </h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table_dis" class="table table-striped table-bordered first">
                        <thead>
                            <tr>
                                <th>Created At</th>
								<th>Offering</th>
                                <th>Amount</th>
                                <th>Contact Name</th>
                                <th>Via</th>
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
    
    $('#table_dis').DataTable({
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "searching": false,
        "lengthChange": false,
        "ajax":{
                 "url": "{{ url('disbursement_list') }}",
                 "dataType": "json",
                 "type": "POST",
                 "data":{ _token: "{{csrf_token()}}"}
               },
        "columns": [
            { "data": "Created At" },
            { "data": "Offering"},
            { "data": "Amount" },
            { "data": "Contact Name"},
            { "data": "Via"},
            { "data": "Status"},
            { "data": "Details"}
        ],
       "language": {
            "emptyTable": "No data available."
        },

    });
});
</script>
	
@endsection
				
				