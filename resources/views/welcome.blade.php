@extends('layout')

@section('content')
<section>
    <div class="container">
        <div class="mt-3">
            <h1>Order Page</h1>
            <form method="post" action="/checkout" class="row">
                @csrf

                <div class="col-6">
                    <div class="mb-3 row">
                        <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                        <div class="col-sm-10">
                            <input type="number" step=0.01 placeholder=100 min=100 id="amount" value="100"
                                class="form-control" name="amount">
                        </div>
                        @error('amount')
                        <div class="alert alert-danger" role="alert">
                            Minimum amount is Php 100
                        </div>
                        @enderror
                    </div>
                </div>

                <input type="submit" name="Submit" class="btn btn-primary">
            </form>
        </div>
    </div>
</section>
@endsection