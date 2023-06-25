@if($payment)
@include(Str::camel(strtolower($payment->billingType->type)) . 'Success')
@else
There was no payment made successfully, please go back and try again.
@endif
<br>
<br>
<a href="{{ route('index') }}" target="_blank">Go back!</a>
