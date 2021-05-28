@extends('layouts.main_layout')

@section('content')
<section class="banner_main">
      <div class="container">
        <div class="banner">
          <h1>
            Invest in the <br />
            next <span>SaaS</span> unicorn
          </h1>
          <p>
            It is a long established fact that a reader will be distracted by
            the readable content of a page when looking at its layout. The point
            of using Lorem Ipsum is that it .
          </p>
          <a href="javascript:;" class="btn btn-iconed-right btn-primary btn-lg"
            >Explore Now <img src="{{url('/images/arrow_forward.svg')}}" alt="icon"
          /></a>
        </div>
      </div>
    </section>

    <section>
      <div class="container">
        <div class="company_heading">
          <h2>Current Companies</h2>
          <p>
            It is a long established fact that a reader will be distracted by
            the readable content of a page when looking at its layout. The point
            of using Lorem Ipsum is that it.
          </p>
        </div>
        <div class="row">
         
          @if(isset($data) && !empty($data))
            @foreach($data as $invest)
            <div class="col-12 col-lg-6">
              <div class="company">
                <div class="company_image">
                  
                  <img src="@if(isset($invest->investment_image)){{env('AWS_BUCKET_PATH')}}projectImages/{{$invest->investment_image }} @endif" class="responsive" alt="image" />
                </div>
                <div class="company_text">
                  <h3>@if(isset($invest->investment_title)){{$invest->investment_title}}@endif</h3>
                  <p>
                    @if(isset($invest->investment_description)){{$invest->investment_description}}@endif
                  </p>
                  <div class="funds">
                    <dl>
                      <dt>Funding Goal</dt>
                      <dd>$@if(isset($invest->budget)){{$invest->budget}}@endif</dd>
                    </dl>
                    <dl>
                      <dt>Min. Investment</dt>
                      <dd>$@if(isset($invest->min_investment)){{$invest->min_investment}}@endif</dd>
                    </dl>
                  </div>
                  <a href="javascript:;" class="link"

                    >View <img src="{{url('/images/arrow_blue.svg')}}" alt="icon"
                  /></a>
                </div>
              </div>
            </div>
            @endforeach
          @endif
          <div class="col-12">
            <div class="view_all">
              <a
                class="btn btn-primary btn-md btn-iconed-right"
                href="javascript:;" 
                >View All <img src="{{url('/images/arrow_forward.svg')}}" alt="icon"
              /></a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="bg-grey">
      <div class="container">
        <div class="row align-items-center-custom">
          <div class="col-12 col-lg-6">
            <div class="content_text content_text_left">
              <h3>
                Why Invest<br />
                through <span>SaaScorn</span>
              </h3>
              <h6>
                We help organizations across the private, public, and social
                sectors create Change that Matters.
              </h6>
              <p>
                “Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                rutrum, tellus sed imperdiet sodales, dui diam accumsan lorem, a
                accumsan ex leo nec tellus. Phasellus varius dui neque.
              </p>
              <a
                class="btn btn-primary btn-md btn-iconed-right"
                href="javascript:;"

                >Learn More<img src="{{url('/images/arrow_forward.svg')}}" alt="icon"
              /></a>
            </div>
          </div>
          <div class="col-12 col-lg-6">
            <div class="content_image">
              <img src="{{url('/images/experience.png')}}" class="img-fluid" alt="image" />
            </div>
          </div>
        </div>
      </div>
    </section>

    <section>
      <div class="container">
        <div class="row align-items-center-custom">
          <div class="col-12 col-lg-6">
            <div class="content_image">
              <img src="{{ asset('images/work.png') }}" class="img-fluid" alt="image" />
            </div>
          </div>
          <div class="col-12 col-lg-6">
            <div class="content_text content_text_right">
              <h3>How it works</h3>

              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                rutrum, tellus sed imperdiet sodales, dui diam accumsan lorem.
              </p>
              <ul>
                <li>Never worry about overpaying for your energy again.</li>
                <li>
                  We will only switch you to energy companies that we trust and
                  will treat you right
                </li>
                <li>
                  We track the markets daily and know where the savings are.
                </li>
              </ul>
              <a
                class="btn btn-primary btn-md btn-iconed-right"
                href="javascript:;"
                >Learn More <img src="{{asset('images/arrow_forward.svg') }}" alt="icon"
              /></a>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection
