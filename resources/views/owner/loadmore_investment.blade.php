@if(isset($allinvestments) && !empty($allinvestments))
    @foreach($allinvestments as $allinvestment)
  <tr>
    <td>
      {{$allinvestment['name']}}
    </td>
    <td>
      {{$allinvestment['email']}}
    </td>
    <td>
      {{$allinvestment['invested']}}
    </td>
    <td>
      ${{$allinvestment['amount']}}
    </td>
    <td>
      ${{$allinvestment['received']}}
    </td>
    <td>
      {{$allinvestment['reference']}}
    </td>
    <td>
      {{$allinvestment['paymentMethod']}}
    </td>
    <td>
      {{$allinvestment['status']}}
    </td>
    <td>
      <a href="{{route('investmentProjectDetails', ['id'=>$allinvestment['id'],'pid' => $pid])}}" class="link"
      >details</a>
    </td>
  </tr>
  @endforeach
  <input type="hidden" id="total" value="{{$count}}">
@endif
