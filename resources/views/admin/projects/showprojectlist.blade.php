@extends('admin.layouts.admin') 
@section('content')

<style>
	.card-body {
    padding: 0px !important;
}
</style>

<div class="card">
    <div class="card-header">
       Show 
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                		@php $k=0;@endphp
						@foreach($list as $key => $list)


						@if($k % 3 == 0)<tr>@endif
	                        <td>
								@if($list->type==1)
								
								<?php
							
								$exts = array('gif', 'png', 'jpg','jpeg','heic');

								$doc_name = explode('.', $list->doc_name);

								if(in_array($doc_name[1], $exts))
								{ ?>
								
									<a href="@if(isset($list->doc_name)){{env('AWS_BUCKET_PATH')}}docs/{{$list->doc_name}}@endif" ><img width="320px" height="240px" src="@if(isset($list->doc_name)){{env('AWS_BUCKET_PATH')}}docs/{{$list->doc_name}}@endif"></a>
									
								 
								<?php
								}
								else
								{ 
								?>
									<a href="@if(isset($list->doc_name)){{env('AWS_BUCKET_PATH')}}docs/{{$list->doc_name}}@endif" ><img width="320px" height="240px" src="{{ asset('docs/doc.png') }}"></a>
								<?php
								}
								?>
							
					
				
								@else
								<video width="320" height="240" controls="">

	                                <source src="@if(isset($list->doc_name)){{env('AWS_BUCKET_PATH')}}videos/{{$list->doc_name}}@endif" type="video/mp4">
	                                Your browser does not support the video tag.
	                            </video>
								@endif
	                        </td>
                   		@if($k  % 3 == 2)</tr>@endif
                   		@php $k++;@endphp
                   @endforeach   
				
                   
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
				Back To Project Listing
        </div>


    </div>



</div>
@endsection
			
				
				