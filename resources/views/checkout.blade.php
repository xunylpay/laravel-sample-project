@extends('layout')

@section('content')
<section>
    <div class="container">
        <div class="mt-3">
            <h1>Payment form</h1>
            <p class="lead">Amount: Php {{$amount/100}}</p>
            <p class="lead">Description: {{$description}}</p>
            <div class="py-4">
                <h2>Select Payment Method</h2>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment_method"
                        onclick="selectPaymentMethod(this)" value="card">
                    <label class="form-check-label" for="card">
                        Visa/Mastercard
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment_method"
                        onclick="selectPaymentMethod(this)" value="gcash">
                    <label class="form-check-label" for="gcash">
                        GCash
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" id="payment_method"
                        onclick="selectPaymentMethod(this)" value="paymaya">
                    <label class="form-check-label" for="paymaya">
                        Maya
                    </label>
                </div>
            </div>
            <form id="payment-form" class="row">
                <h3>Customer Information</h3>
                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="name" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                            <input type="text" id="name" class="form-control" name="name" value="Test">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" id="email" class="form-control" name="email" value="test@gmail.com">
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                            <input type="text" id="phone" class="form-control" name="phone" value="09369788867">
                        </div>
                    </div>
                </div>
                <div id="billing-details-div">
                    <h3>Billing Details</h3>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="postal_code" class="col-sm-2 col-form-label">Postal code</label>
                            <div class="col-sm-10">
                                <input type="text" id="postal_code" class="form-control" name="postal_code">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="line1" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <input type="text" id="line1" class="form-control" name="line1">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="city" class="col-sm-2 col-form-label">City</label>
                            <div class="col-sm-10">
                                <input type="text" id="city" class="form-control" name="city">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="state" class="col-sm-2 col-form-label">State</label>
                            <div class="col-sm-10">
                                <input type="text" id="state" class="form-control" name="state">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="country" class="col-sm-2 col-form-label">Country</label>
                            <div class="col-sm-10">
                                <input type="text" id="country" class="form-control" name="country">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="card_no" class="col-sm-2 col-form-label">Card no</label>
                            <div class="col-sm-10">
                                <input type="text" id="card_no" class="form-control" name="card_no">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="exp_month" class="col-sm-2 col-form-label">Card Exp. month</label>
                            <div class="col-sm-10">
                                <input type="text" id="exp_month" class="form-control" name="exp_month">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="exp_year" class="col-sm-2 col-form-label">Card Exp. year</label>
                            <div class="col-sm-10">
                                <input type="text" id="exp_year" class="form-control" name="exp_year">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="cvc" class="col-sm-2 col-form-label">CVV</label>
                            <div class="col-sm-10">
                                <input type="text" id="cvc" class="form-control" name="cvc">
                            </div>
                        </div>
                    </div>
                </div>
                <input type="submit" name="Submit" class="btn btn-primary">
            </form>

            {{-- Make more secure --}}
            <form id="attach-form" method="POST" action="/payment" class="hidden">
                @csrf
                <input type="hidden" id="paymentMethodId" name="paymentMethodId" value="">
                <input type="hidden" id="checkoutId" name="checkoutId" value="">
            </form>
        </div>
    </div>
</section>

<script type="text/javascript">
    let payment_method = ''
    let publicKey = '{{env('PAYMONGO_PK')}}'
    let checkoutId = '{{$id}}'
    $(document).ready(function () {
    });

    function selectPaymentMethod(myRadio) {
        payment_method = myRadio.value;
        if(payment_method == 'card'){
            $("#billing-details-div").css("display", "block");;
        }
        else{
            $("#billing-details-div").css("display", "none");;
        }
    }

    $("#payment-form").on('submit', function(e){
        e.preventDefault();
        const formData = new FormData(e.target);
        const formProps = Object.fromEntries(formData);
        
        // Create the payment methods
        const paymentMethodOptions = {
            method: 'POST',
            headers: {
            accept: 'application/json',
            'Content-Type': 'application/json',
            authorization: 'Basic ' + window.btoa(publicKey)
            },
            body: JSON.stringify({
                data: {
                    attributes: {
                        billing: {
                            name: formProps.name, 
                            email: formProps.email, 
                            phone: formProps.phone
                        }, 
                        type: payment_method
                    }
                }
            })
        };


        fetch('https://api.paymongo.com/v1/payment_methods', paymentMethodOptions)
        .then(response => response.json())
        .then(response => {
            $("#paymentMethodId").val(response.data.id)
            $("#checkoutId").val(checkoutId)
            $("#attach-form").submit()
        })
        .catch(err => console.error(err));
    });
</script>
@endsection