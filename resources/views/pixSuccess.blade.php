Here's the QR Code to make the payment:
<br><br>
<img src="data:image/png;base64, {{$payment->pix_encoded_image}}" alt="Red dot" />

<br><br> You can also copy and paste the following on your banking app:
<br>{{$payment->pix_qr_code_payload}}
