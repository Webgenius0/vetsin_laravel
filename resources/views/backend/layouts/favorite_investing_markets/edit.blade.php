@extends('backend.app')
@section('title', 'Edit Favorite Investing Market')
@section('header_title', 'Edit Favorite Investing Market')

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="bg-white p-5">
                        <form action="{{ route('favorite-investing-markets.update', $data->id) }}" method="POST">
                            @csrf
                     

                            <div class="mb-3">
                                <label for="title" class="form-label">Market Title</label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $data->title) }}" placeholder="Enter market title" required>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="active" {{ $data->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ $data->status == 'inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('favorite-investing-markets.index') }}"
                                    class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
