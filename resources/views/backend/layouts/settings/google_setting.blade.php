@extends('backend.app')

@section('title', 'Google settings')

@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 mt-4">
                <div class="card-style mb-4">
                    <div class="card-body card">
                        <form method="POST" action="{{ route('google.update') }}">
                            @csrf
                            <div class="input-style-1">
                                <label for="GOOGLE_CLIENT_ID">GOOGLE CLIENT ID:</label>
                                <input type="text" placeholder="Enter google id" id="GOOGLE_CLIENT_ID"
                                    class="form-control @error('GOOGLE_CLIENT_ID') is-invalid @enderror" name="GOOGLE_CLIENT_ID"
                                    value="{{ env('GOOGLE_CLIENT_ID') }}" />
                                @error('GOOGLE_CLIENT_ID')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="input-style-1 mt-4">
                                <label for="GOOGLE_CLIENT_SECRET">GOOGLE CLIENT SECRET:</label>
                                <input type="text" placeholder="Enter google secret" id="GOOGLE_CLIENT_SECRET"
                                    class="form-control @error('GOOGLE_CLIENT_SECRET') is-invalid @enderror"
                                    name="GOOGLE_CLIENT_SECRET" value="{{ env('GOOGLE_CLIENT_SECRET') }}" />
                                @error('GOOGLE_CLIENT_SECRET')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>


                            <div class="input-style-1 mt-4">
                                <label for="GOOGLE_REDIRECT_URI">GOOGLE REDIRECT URI:</label>
                                <input type="text" placeholder="Enter google uri" id="GOOGLE_REDIRECT_URI"
                                    class="form-control @error('GOOGLE_REDIRECT_URI') is-invalid @enderror"
                                    name="GOOGLE_REDIRECT_URI" value="{{ env('GOOGLE_REDIRECT_URI') }}" />
                                @error('GOOGLE_REDIRECT_URI')
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
