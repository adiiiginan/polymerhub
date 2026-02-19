@extends('frontend.layout.main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Checkout Successful</div>

                    <div class="panel-body">
                        <p>Your order has been placed successfully.</p>
                        <p>Transaction ID: {{ $transaksi->id_transaksi }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
