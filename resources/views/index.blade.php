<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ASAAS integrated payment</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link href="https://getbootstrap.com/docs/4.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

        <!-- Styles -->
        <style>

        </style>
    </head>
    <body class="bg-light" cz-shortcut-listen="true">
        <div class="container">
      <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="https://getbootstrap.com/docs/4.5/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
        <h2>Checkout form</h2>
      </div>

      <div class="row">
        <div class="col order-md-1">
          <form id="paymentForm" class="needs-validation" novalidate="" action="{{route('checkout')}}" method="POST">
            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <h4 class="mb-3">Payment</h4>

            <div class="d-block my-3">
                @foreach ($billingTypes as $billingType)
                <div class="custom-control custom-radio">
                  <input id="{{$billingType->type}}" name="billingType" type="radio" class="custom-control-input" required="" value="{{$billingType->type}}">
                  <label class="custom-control-label" for="{{$billingType->type}}">{{$billingType->description}}</label>
                </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-6">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" name="name" id="name" required="">
                  <div class="invalid-feedback">
                    Name is required
                  </div>
                </div>
                <div class="col-3">
                  <label for="cpf">Cpf</label>
                  <input type="text" class="form-control" name="cpf" id="cpf" required="">
                  <div class="invalid-feedback">
                    CPF is required
                  </div>
                </div>
                <div class="col-3">
                  <label for="amount">Value</label>
                  <input type="number" class="form-control" name="amount" id="amount" required="">
                  <div class="invalid-feedback">
                    Value is required
                  </div>
                </div>

            </div>
            <div class="row creditCardRow">
              <div class="col-md-6 mb-3">
                <label for="ccName">Name on card</label>
                <input type="text" class="form-control" name="ccName" id="ccName" placeholder="" required="">
                <small class="text-muted">Full name as displayed on card</small>
                <div class="invalid-feedback">
                  Name on card is required
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="ccNumber">Credit card number</label>
                <input type="text" class="form-control" name="ccNumber" id="ccNumber" placeholder="" required="" maxlength="16" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                <div class="invalid-feedback">
                  Credit card number is required
                </div>
              </div>
            </div>
            <div class="row creditCardRow">
              <div class="col-md-3 mb-3">
                <label for="ccExpiration">Expiration</label>
                <input type="text" class="form-control" name="ccExpiration" id="ccExpiration" placeholder="" required="" pattern="\d{2}\/\d{2}">
                <div class="invalid-feedback">
                  Expiration date required
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="ccCcv">CCV</label>
                <input type="text" class="form-control" name="ccCcv" id="ccCcv" placeholder="" required="" maxlength="3" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
                <div class="invalid-feedback">
                  Security code required
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <label for="ccInstallmentCount">Amount of Installments</label>
                <input type="number" class="form-control" name="ccInstallmentCount" id="ccInstallmentCount" placeholder="">
              </div>
              <div class="col-md-3 mb-3">
                <label for="ccInstallmentValue">Installment Value</label>
                <input type="number" class="form-control" name="ccInstallmentValue" id="ccInstallmentValue" placeholder="" maxlength="3">
              </div>
            </div>
            <h4 class="mb-3 creditCardRow">Credit card holder information</h4>
            <div class="row creditCardRow">
                <div class="col-3">
                  <label for="ccCpf">Cpf</label>
                  <input type="text" class="form-control" name="ccCpf" id="ccCpf">
                  <div class="invalid-feedback">
                    CPF is required
                  </div>
                </div>
                <div class="col-3">
                  <label for="ccPostalCode">Postal code</label>
                  <input type="text" class="form-control" name="ccPostalCode" id="ccPostalCode">
                  <div class="invalid-feedback">
                    Postal code is required
                  </div>
                </div>
                <div class="col-3">
                  <label for="ccEmail">Email</label>
                  <input type="text" class="form-control" name="ccEmail" id="ccEmail">
                  <div class="invalid-feedback">
                    Email is required
                  </div>
                </div>
                <div class="col-3">
                  <label for="ccAddressNumber">Address number</label>
                  <input type="text" class="form-control" name="ccAddressNumber" id="ccAddressNumber">
                  <div class="invalid-feedback">
                    Address number is required
                  </div>
                </div>
                <div class="col-3">
                  <label for="ccPhone">Phone</label>
                  <input type="text" class="form-control" name="ccPhone" id="ccPhone">
                  <div class="invalid-feedback">
                    Phone is required
                  </div>
                </div>
            </div>
            <div id="error_message_container" style="display:none">
              <span id="msg" style="color:red"></span>
              <a id="clear_ack" href="#"
                onclick="$(this).parents('div#error_message_container').fadeOut(400); return false;">
                clear
              </a>
            </div>
            <hr class="mb-4">
            <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
          </form>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> --}}
          <script src="https://getbootstrap.com/docs/4.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
            <script src="https://getbootstrap.com/docs/4.5/examples/checkout/form-validation.js"></script>
            <script src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>
            <script>
                $("#paymentForm").submit(function(e) {

                    e.preventDefault();

                    let form = $(this);
                    let actionUrl = form.attr('action');
                    $.ajax({
                        type: "POST",
                        url: actionUrl,
                        data: form.serialize(),
                        success: function(data){
                            window.location.href = "{{ route('success') }}";
                        },
                        error: function(err){
                            let error = err?.responseJSON?.message;
                            if(error){
                                showErrorMessage(error);
                            }
                        }
                    });

                });
                function showErrorMessage(message){
                  let container = $('#error_message_container');
                  $(container).find('span#msg').html(message);
                  $(container).show();
                }
                function fixBillingTypeDisplay(){
                    let type = $(":radio[name=billingType]:checked").val();
                    if(type === "{{ \App\Models\BillingType::CARTAO_CREDITO }}"){
                        $('.creditCardRow').show();
                    }else{
                        $('.creditCardRow').hide();
                    }
                }
                $(":radio[name=billingType]").change(fixBillingTypeDisplay);
                $("#ccExpiration").inputmask({"mask": "99/99"});
                fixBillingTypeDisplay();
            </script>

    </body>
</html>
