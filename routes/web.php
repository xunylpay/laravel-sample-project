<?php

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Page where user sees their order for an example would be the "Cart"
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Page where users initiates the payment 
// http://localhost/checkout/1 
Route::get('/checkout/{id}', function ($id) {
    // Optional: Make sure the authenticated user can only access their own checkout
    $transaction = Transaction::find($id);

    if ($transaction) {
        return view('checkout', [
            'amount' => $transaction->amount,
            'description' => $transaction->description,
            'id' => $transaction->id
        ]);
    } else {
        return 'Checkout not found';
    }
})->name('checkout');

// Page where user confirms the payment on the client side
// http://localhost/payment?paymentIntentId=asdfasdfas&clientKey=12341234
Route::get('/payment', function (Request $request) {

    return view('payment', [
        'paymentIntentId' => $request->payment_intent_id,
        'paymentIntentClientKey' => $request->client_key,
    ]);
})->name('payment');


// POST request where the user wants to checkout
Route::post('/checkout', function (Request $request) {

    $formFields = $request->validate([
        'amount' => 'required|gt:99.99'
    ]);

    // We create a transaction on our database for the order

    $id = Transaction::max('id') + 1;
    $newTransaction = [
        'amount' => $request->amount * 100,
        'description' => 'Order ID ' . $id
    ];

    Transaction::create($newTransaction);

    // We redirect the user to the checkout screen to get the Payment and Billing details
    return redirect()->route('checkout', ['id' => $id]);
});

// POST request where the user wants to initiate the payment
Route::post('/payment', function (Request $request) {

    // We get the payment method ID generated from the client side
    $formFields = $request->validate([
        'checkoutId' => 'required',
        'paymentMethodId' => 'required',
    ]);

    // Find the transaction
    $updateTransaction = Transaction::find($request->checkoutId);


    if ($updateTransaction) {
        // Create the Payment Intent
        $authorizationKey = 'Basic ' . base64_encode(env('PAYMONGO_SK'));
        $paymentIntentData = [
            'attributes' => [
                'amount' => $updateTransaction->amount,
                'description' => $updateTransaction->description . '',
                'payment_method_allowed' => ["atome", "card", "dob", "paymaya", "billease", "gcash"],
                'currency' => "PHP",
                'metadata' => [
                    'orderId' => $updateTransaction->id . ''
                ]
            ]
        ];
        $paymentIntentResponseJson = Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => $authorizationKey,
            'content-type' => 'application/json',
        ])->post('https://api.paymongo.com/v1/payment_intents', [
            'data' => $paymentIntentData
        ]);

        $paymentIntentResponse = json_decode($paymentIntentResponseJson);

        $paymentIntentId = $paymentIntentResponse->data->id;
        $paymentIntentClientKey = $paymentIntentResponse->data->attributes->client_key;

        // Update Database
        Transaction::where('id', $updateTransaction->id)
            ->update([
                'paymentIntentId' => $paymentIntentId,
                'clientKey' => $paymentIntentClientKey,
                'paymentMethodId' => $request->paymentMethodId,
            ]);

        // Attach the Payment Intent to the Payment Method

        $attachData = [
            'attributes' => [
                'payment_method' => $request->paymentMethodId,
                'return_url' => env('WEBSITE_DOMAIN') . 'payment?paymentIntentId=' . $paymentIntentId . '&client_key=' . $paymentIntentClientKey,
            ]
        ];
        $attachResponseJson = Http::withHeaders([
            'accept' => 'application/json',
            'authorization' => $authorizationKey,
            'content-type' => 'application/json',
        ])->post('https://api.paymongo.com/v1/payment_intents/' . $paymentIntentId . '/attach', [
            'data' => $attachData
        ]);

        $attachResponse = json_decode($attachResponseJson);

        // We redirect the user to the the authentication screen 
        return redirect($attachResponse->data->attributes->next_action->redirect->url);
    } else {
        return 'Check out not found';
    }
});
