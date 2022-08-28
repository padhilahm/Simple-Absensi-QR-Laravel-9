@extends('template.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pengaturan /</span> Kehadiran</h4>

        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-md-row mb-3">
                    <li class="nav-item">
                        <a class="nav-link " href="/setting"><i class="bx bx-user me-1"></i> Kehadiran</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-bell me-1"></i>
                            Kehadiran</a>
                    </li>
                </ul>
                <div class="card mb-4">
                    <h5 class="card-header">Pengaturan Kehadiran</h5>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <form id="formAccountSettings" method="POST" action="/setting/{{ $setting->id }}">
                            @method('post') @csrf <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="firstName" class="form-label">Waktu Mulai Absen</label>
                                    <input class="form-control" type="time" name="attendance_start_time"
                                        value="{{ old('attendance_start_time', $setting->attendance_start_time) }}" />
                                    <small class="text-danger">{{ $errors->first('attendance_start_time') }}</small>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">Waktu Mulai Absen</label>
                                    <input class="form-control" type="time" name="attendance_end_time"
                                        value="{{ old('attendance_end_time', $setting->attendance_end_time) }}" />
                                    <small class="text-danger">{{ $errors->first('attendance_end_time') }}</small>
                                </div>

                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!-- /Account -->
                </div>
            </div>
        </div>
    </div>
@endsection
