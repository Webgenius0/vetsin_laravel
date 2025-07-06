@extends('backend.app')

@section('title', 'Facebook settings')

@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 mt-4">
                <div class="card-style mb-4">
                    <div class="card card-body">
                        <form method="POST" action="{{ route('facebook.update') }}">
                            @csrf
                            <div class="input-style-1">
                                <label for="FACEBOOK_CLIENT_ID">FACEBOOK CLIENT ID:</label>
                                <input type="text" placeholder="Enter facebook id" id="FACEBOOK_CLIENT_ID"
                                    class="form-control @error('FACEBOOK_CLIENT_ID') is-invalid @enderror"
                                    name="FACEBOOK_CLIENT_ID" value="{{ env('FACEBOOK_CLIENT_ID') }}" />
                                @error('FACEBOOK_CLIENT_ID')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="input-style-1 mt-4">
                                <label for="FACEBOOK_CLIENT_SECRET">FACEBOOK CLIENT SECRET:</label>
                                <input type="text" placeholder="Enter facebook secret" id="FACEBOOK_CLIENT_SECRET"
                                    class="form-control @error('FACEBOOK_CLIENT_SECRET') is-invalid @enderror"
                                    name="FACEBOOK_CLIENT_SECRET" value="{{ env('FACEBOOK_CLIENT_SECRET') }}" />
                                @error('FACEBOOK_CLIENT_SECRET')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="input-style-1 mt-4">
                                <label for="FACEBOOK_REDIRECT_URI">FACEBOOK REDIRECT URI:</label>
                                <input type="text" placeholder="Enter google uri" id="FACEBOOK_REDIRECT_URI"
                                    class="form-control @error('FACEBOOK_REDIRECT_URI') is-invalid @enderror"
                                    name="FACEBOOK_REDIRECT_URI" value="{{ env('FACEBOOK_REDIRECT_URI') }}" />
                                @error('FACEBOOK_REDIRECT_URI')
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
