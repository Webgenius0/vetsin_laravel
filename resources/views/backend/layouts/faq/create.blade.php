@extends('backend.app')
@section('title', 'FAQ')
@section('content')


    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-body">
                        <h1 class="mb-4">Add Faq</h1>
                        <form action="{{ route('admin.faqs.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mt-4">
                                <label for="question" class="form-label">Question</label>
                                <textarea name="question" id="question" class="form-control @error('question') is-invalid @enderror"
                                    placeholder="Enter Question" value="{{ old('question') }}" rows="7"></textarea>
                                @error('question')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="answer" class="form-label">Answer</label>
                                <textarea name="answer" id="answer" class="form-control @error('answer') is-invalid @enderror"
                                    placeholder="Enter Answer" value="{{ old('answer') }}" rows="7"></textarea>
                                @error('answer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <input type="submit" class="btn btn-primary btn-lg" value="Submit">
                                <a href="{{ route('admin.faqs.index') }}" class="btn btn-danger btn-lg">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
