@extends('template.main')

@section('content')
    <!-- Content -->


    <div class="container-xxl flex-grow-1 container-p-y">


        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data /</span> Absensi</h4>

        {{-- alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Tambah Kelas</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" id="form-filter">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" id="class_id">
                                <label for="name">Tanggal Absensi</label>
                                <input type="date" class="form-control" id="date" name="date"
                                    placeholder="Enter date" value="{{ $date }}">
                                <small class="text-danger" id="alert_date"></small>
                            </div>
                            <div class="form-group mt-2">
                                <button type="button" class="btn btn-primary" id="btn-fiter">Filter</button>
                                <span id="cancel-filter">
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <div class="col-md-8">
                <!-- Basic Bootstrap Table -->
                <div class="card">

                    <div class="d-flex justify-content-between bd-highlight">
                        <div class="bd-highlight">
                            <h4>
                                <div class="m-3">Data Absensi - {{ date('d M Y', strtotime($date)) }}</div>
                            </h4>
                        </div>
                    </div>

                    <!-- Toast with Placements -->
                    <div class="bs-toast toast toast-placement-ex m-2" role="alert" aria-live="assertive"
                        aria-atomic="true" data-delay="2000">
                        <div class="toast-header">
                            <i class="bx bx-bell me-2"></i>
                            <div class="me-auto fw-semibold">Bootstrap</div>
                            {{-- <small>11 mins ago</small> --}}
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body"></div>
                    </div>
                    <!-- Toast with Placements -->


                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="3%">No</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th class="text-end">Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($students as $student)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $student->name }}</td>
                                        <td>{{ $student->studentClass->name }}</td>
                                        <td class="text-end">
                                            @if ($student->attendances->where('date', $date)->count() > 0)
                                                <button type="button" class="btn btn-success btn-sm" id="btn-attendance"
                                                    data-id="{{ $student->id }}"
                                                    data-date="{{ $date }}">Hadir</button>
                                            @else
                                                <button type="button" class="btn btn-danger btn-sm" id="btn-attendance"
                                                    data-id="{{ $student->id }}" data-date="{{ $date }}">Tidak
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--/ Basic Bootstrap Table -->

    </div>
    <!-- / Content -->
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#btn-fiter').click(function() {
                var date = $('#date').val();
                if (date == '') {
                    $('#alert_date').text('Tanggal tidak boleh kosong');
                } else {
                    $('#alert_date').text('');
                    window.location.href = "/dashboard/attendance/{{ $id }}/" + date;
                }
            });
        });
    </script>


    <script>
        const toastPlacementExample = document.querySelector(".toast-placement-ex"),
            toastPlacementBtn = document.querySelector("#showToastPlacement");
        let selectedType, selectedPlacement, toastPlacement;

        // Dispose toast when open another
        function toastDispose(toast) {
            if (toast && toast._element !== null) {
                if (toastPlacementExample) {
                    toastPlacementExample.classList.remove(selectedType);
                    DOMTokenList.prototype.remove.apply(
                        toastPlacementExample.classList,
                        selectedPlacement
                    );
                }
                toast.dispose();
            }
        }

        function showToast(type, placement, message) {

            // remove class from previous toast
            toastDispose(toastPlacement);

            $('.toast-body').text(message);
            toastPlacementExample.classList.add(type);
            DOMTokenList.prototype.add.apply(
                toastPlacementExample.classList,
                placement.split(" ")
            );
            toastPlacement = new bootstrap.Toast(toastPlacementExample);
            toastPlacement.show();
        }
    </script>
@endsection
