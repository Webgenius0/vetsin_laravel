@extends('backend.app')

@section('title', 'Stripe settings')

@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 mt-4">
                <div class="card-style mb-4">
                    <div class="card card-body">
                        <form method="POST" action="{{ route('stripe.update') }}">
                            @csrf
                            <div class="input-style-1">
                                <label for="STRIPE_PK">STRIPE KEY:</label>
                                <input type="text" placeholder="Enter stripe key" id="STRIPE_PK"
                                    class="form-control @error('STRIPE_PK') is-invalid @enderror" name="STRIPE_PK"
                                    value="{{ env('STRIPE_PK') }}" />
                                @error('STRIPE_PK')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="input-style-1 mt-4">
                                <label for="STRIPE_SK">STRIPE_SECRET:</label>
                                <input type="text" placeholder="Enter stripe secret" id="STRIPE_SK"
                                    class="form-control @error('STRIPE_SK') is-invalid @enderror" name="STRIPE_SK"
                                    value="{{ env('STRIPE_SK') }}" />
                                @error('STRIPE_SK')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-danger me-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
