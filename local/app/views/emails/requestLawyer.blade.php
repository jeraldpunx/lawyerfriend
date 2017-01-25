<p>Hello <b>{{ $lawyer['first_name'] }} {{ $lawyer['last_name'] }}</b>, you have a new message request from a user looking for legal representation in your practice area.</p>

 <table>
  <tr>
    <td>Practice Area</td>
    <td>:</td>
    <td>{{ $input['practice_area'] }}</td>
  </tr>
  <tr>
    <td>State</td>
    <td>:</td>
    <td>{{ State::stateName($customer['state_id']) }}</td>
  </tr>
  <tr>
    <td>City</td>
    <td>:</td>
    <td>{{ City::cityName($customer['city_id']) }}</td>
  </tr>
  <tr>
    <td>Contact Number</td>
    <td>:</td>
    <td>{{ $customer['mobile_number'] }} / {{ $customer['home_number'] }}</td>
  </tr>
  <tr>
    <td>Rate</td>
    <td>:</td>
    <td>${{ $input['rate'] }}</td>
  </tr>
</table>
<hr>
 <table>
  @foreach($input['form']['label'] as $key => $question)
   <tr>
    <td>{{ $question }}</td>
    <td>:</td>
    <td>{{ $input['form']['answer'][$key] }}</td>
  </tr>
  @endforeach
</table> 

<p>Additional info:</p>
<p style="padding-left: 20px;">{{ $input['info'] }}</p>
<p>I would like to be contacted by: {{ $input['contact'] }}</p>

<hr>
<p>This message is from Lawyer Friend. Just reply directly here.</p>