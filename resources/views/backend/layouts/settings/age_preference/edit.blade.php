@extends('backend.app')

@section('title', 'Update Age Preference')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card-style mb-4">
                <div class="card card-body">
                    <form method="POST" action="{{ route('age_preference.update', ['id' => $data->id]) }}">
                        @csrf
                        <div class="input-style-1 mt-4">
                            <label for="title">Title:</label>
                            <input type="text" placeholder="Enter Title" id="title"
                                class="form-control @error('title') is-invalid @enderror" name="title"
                                value="{{ old('title', $data->title) }}" />
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('age_preference.index') }}" class="btn btn-danger me-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
