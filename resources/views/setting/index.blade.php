@extends('template.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Akun</h4>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i> Akun</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/setting-attendance"><i class="bx bx-bell me-1"></i>
                            Kehadiran</a>
                    </li>
                </ul>
                <div class="card mb-4">
                    <form id="formAccountSettings" method="POST" action="/setting-user" enctype="multipart/form-data">

                        @method('post')
                        @csrf
                        <h5 class="card-header">Detail Akun</h5>
                        <!-- Account -->
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img src="/storage/images/{{ $user->photo }}" alt="user-avatar" class="d-block rounded"
                                    height="100" width="100" id="uploadedAvatar" />
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Upload new photo</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload" name="photo" class="account-file-input"
                                            hidden accept="image/png, image/jpeg" />
                                    </label>
                                    {{-- <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button> --}}

                                    <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                </div>
                            </div>
                        </div>
                        <hr class="my-0" />
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif


                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="firstName" class="form-label">Nama Lengkap</label>
                                    <input class="form-control" type="text" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" placeholder="Masukkan Nama Lengkap"
                                        autofocus />
                                    <small class="text-danger">{{ $errors->first('name') }}</small>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input class="form-control" type="text" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" placeholder="Masukkan Email" />
                                    <small class="text-danger">{{ $errors->first('email') }}</small>
                                </div>
                                <div class="mb-3 col-md-12">
                                    <label for="firstName" class="form-label">Nama Sekolah</label>
                                    <input class="form-control" type="text" id="school_name" name="school_name"
                                        value="{{ old('school_name', $user->school_name) }}"
                                        placeholder="Masukkan Nama Sekolah" autofocus />
                                    <small class="text-danger">{{ $errors->first('school_name') }}</small>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">Password</label>
                                    <input class="form-control" type="password" id="password" name="password"
                                        placeholder="Masukkan Password" />
                                    <small class="text-danger">{{ $errors->first('password') }}</small>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">Ulangi Password</label>
                                    <input class="form-control" type="password" id="re_password" name="re_password"
                                        placeholder="Masukkan Ulangi Password" />
                                    <small class="text-danger">{{ $errors->first('re_password') }}</small>
                                </div>

                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                {{-- <button type="reset" class="btn btn-outline-secondary">Cancel</button> --}}
                            </div>
                        </div>
                    </form>
                    <!-- /Account -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#upload').change(function() {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onloadend = function() {
                    $('#uploadedAvatar').attr('src', reader.result);
                }
                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    $('#uploadedAvatar').attr('src', '');
                }
            });
        });
    </script>
@endsection
