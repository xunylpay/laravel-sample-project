@extends('layout')

@section('content')
<section class="container py-4">

    <div class="mx-auto d-flex justify-content-center">
        <div id="loader" class="spinner-border" role="status">
        </div>
    </div>

    <div id="content">
        <h1>Payment for <span id="description"></span></h1>
        <h2 id="status"></h2>

        <a class="btn" id="next-action">Try Again</a>
    </div>
</section>

<script type="text/javascript">
    let paymentIntentId = '{{$paymentIntentId}}'
    let clientKey = '{{$paymentIntentClientKey}}'
    let publicKey = '{{env('PAYMONGO_PK')}}'
    let loading = true
    $(document).ready(function () {

        $("#content").hide()
        const retrievePaymentIntentoptions = {
            method: 'GET',
            headers: {
                accept: 'application/json',
                authorization: 'Basic ' + window.btoa(publicKey)
            }
        };
        
        
        fetch(`https://api.paymongo.com/v1/payment_intents/${paymentIntentId}?client_key=${clientKey}`, retrievePaymentIntentoptions)
        .then(response => response.json())
        .then(response => {
            console.log(response.data)
            let attributes = response.data.attributes
            $("#loader").hide()
            $("#description").text(attributes.description)
            if(attributes.status=='succeeded'){
                $("#next-action").addClass('btn btn-primary')
                $("#next-action").text('Back to Home')
                $("#next-action").attr("href", '/');
                $("#status").text('Payment Successful')
            }
            else{
                $("#next-action").addClass('btn btn-danger')
                $("#next-action").text('Try again')
                $("#next-action").attr("href", `/checkout/${attributes.metadata.orderId}`);
                $("#status").text('Payment Failed')
            }
            
           $("#content").show()
        })
        .catch(err => console.error(err));
    });
</script>

@endsection