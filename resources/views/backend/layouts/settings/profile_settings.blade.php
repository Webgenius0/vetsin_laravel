@extends('backend.app')

@section('title', 'Profile settings')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-style settings-card-1 mb-30">
                    <div class="title mb-30 d-flex justify-content-between align-items-center">
                        <h4>My Profile</h4>
                    </div>
                    <div class="profile-info">
                        <div class="d-flex align-items-center mb-30">
                            <div class="profile-image mb-5">
                                <img id="profile-picture"
                                    src="{{ asset(Auth::user()->avatar ?? 'backend/images/profile.jpeg') }}"
                                    alt="Profile Picture">
                                <div class="update-image">
                                    <input type="file" name="profile_picture" id="profile_picture_input"
                                        style="display: none;">
                                    <label for="profile_picture_input"><i class="lni lni-cloud-upload"></i></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                                <div class="card card-body h-100">
                                    <h4 class="mb-4">Update Profile</h4>
                                    <form method="POST" action="{{ route('update.profile') }}">
                                        @csrf
                                        <div class="input-style-1 mb-4">
                                            <label for="name">User Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                name="name" id="name" value="{{ Auth::user()->name }}"
                                                placeholder="Full Name" />
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="input-style-1 mb-4">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Email" name="email" id="email"
                                                value="{{ Auth::user()->email }}" />
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                                    </form>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="card card-body h-100">
                                    <h4 class="mb-4">Change Password</h4>
                                    <form method="POST" action="{{ route('update.Password') }}">
                                        @csrf
                                        <div class="input-style-1 mb-4">
                                            <label for="old_password">Current Password</label>
                                            <input type="password"
                                                class="form-control @error('old_password') is-invalid @enderror"
                                                placeholder="Current Password" name="old_password" id="old_password" />
                                            @error('old_password')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="input-style-1 mb-4">
                                            <label for="password">New Password</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="New Password" name="password" id="password" />
                                            @error('password')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="input-style-1 mb-4">
                                            <label for="password_confirmation">Confirm Password</label>
                                            <input type="password"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                placeholder="Confirm Password" name="password_confirmation"
                                                id="password_confirmation" />
                                            @error('password_confirmation')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary w-100">Change Password</button>
                                            <a href="{{ route('admin.dashboard') }}" class="btn btn-danger w-100">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(document).ready(function() {
            $('#profile_picture_input').change(function() {
                const formData = new FormData();
                formData.append('profile_picture', $(this)[0].files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                $.ajax({
                    url: '{{ route('update.profile.picture') }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.success) {
                            $('#profile-picture').attr('src', data.image_url);
                            toastr.success('Profile picture updated successfully.');
                        } else {
                            toastr.error(data.message);
                        }
                    },
                    error: function() {
                        toastr.error(data.message);
                    }
                });
            });
        });
    </script>
@endpush
