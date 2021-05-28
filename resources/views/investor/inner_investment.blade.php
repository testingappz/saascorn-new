 <input type="hidden" id="loadmoredata" value="{{$totaldata}}">
 @if(isset($data) && !empty($data))
    @foreach($data as $invest)
  <div class="col-12 col-lg-4 investment_block" data-id="{{$invest->id}}">
    <div class="company">
      <div class="company_image">
        
        <img src="@if(isset($invest->investment_image)){{env('AWS_BUCKET_PATH')}}projectImages/{{$invest->investment_image}}@endif" class="responsive" alt="image" />
      </div>
      <div class="company_text">
        <h3>@if(isset($invest->investment_title)){{$invest->investment_title}}@endif</h3>
        <p>
          @if(isset($invest->investment_description)){{$invest->investment_description}}@endif
        </p>
        <div class="funds">
          <dl>
            <dt>Funding Goal</dt>
            <dd>@if(isset($invest->budget)){{$invest->budget}}@endif</dd>
          </dl>
          <dl>
            <dt>Min. Investment</dt>
            <dd>@if(isset($invest->min_investment)){{$invest->min_investment}}@endif</dd>
          </dl>
        </div>
        <a href="{{route('investmentDetails', ['id' => $invest->id])}}" class="link"
          >View <img src="{{url('/images/arrow_blue.svg')}}" alt="icon"
        /></a>
      </div>
    </div>
  </div>
  @endforeach
@endif
