@extends('template.main')

@section('content')
    <!-- Content -->


    <div class="container-xxl flex-grow-1 container-p-y">


        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Data /</span> Kelas</h4>

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
                        <form method="post" id="form-save">
                            @csrf
                            <div class="form-group">
                                <input type="hidden" id="class_id">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    placeholder="Enter name">
                                <small class="text-danger" id="alert_name"></small>
                            </div>
                            <div class="form-group mt-2">
                                <button type="button" class="btn btn-primary" id="btn-save">Simpan</button>
                                <span id="cancel-update">
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
                                <div class="m-3">Data Kelas</div>
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
                                    <th width="3%">Kode</th>
                                    <th>Nama</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach ($classes as $class)
                                    <tr>
                                        <td>{{ $class->id }}</td>
                                        <td>{{ $class->name }}</td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="edit({{ $class->id }})">
                                                <i class="bx bx-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="delete_({{ $class->id }})">
                                                <i class="bx bx-trash"></i>
                                            </button>
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
        // if btn_save is clicked
        $('#btn-save').click(function() {
            // get form data
            var formData = $('#form-save').serialize();
            let class_id = $('#class_id').val();

            if (class_id == '') {
                url_save = "{{ route('class.store') }}";
                method_save = 'POST';
            } else {
                url_save = "/class/" + class_id;
                method_save = 'PUT';
            }

            // ajax request
            $.ajax({
                url: url_save,
                type: method_save,
                data: formData,
                success: function(data) {
                    if (data.code == 200) {
                        cancel();
                        showToast('bg-success', 'top-0 end-0', data.message);
                        // reload page
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (data.code == 400) {
                        $('#alert_name').text(data.errors.name);
                    } else {
                        // show error message
                        showToast('bg-danger', 'top-0 end-0', data.message);
                    }
                },
                error: function(data) {
                    // show error message
                    showToast('bg-danger', 'top-0 end-0', 'Something went wrong!');
                }
            });
        });

        // if edit is clicked
        function edit(id) {
            $('#alert_name').text('');
            $('#name').val('');
            $('#btn-save').text('Ubah');
            $('.card-header h4').text('Edit Kelas');
            $('#cancel-update').html(
                '<button type="button" class="btn btn-secondary" onclick="cancel()">Batal Ubah</button>'
            );

            // ajax request
            $.ajax({
                url: '/class/' + id,
                type: 'GET',
                success: function(data) {
                    // if request is successfull
                    if (data.code == 200) {
                        // set value of input field
                        $('#name').val(data.class.name);
                        $('#class_id').val(data.class.id);
                    } else {
                        // show error message
                        showToast('bg-danger', 'top-0 end-0', data.message);
                    }
                }
            });
        }

        function delete_(id) {
            // swal("Good job!", "You clicked the button!", "success");
            // alert(id);

            // sweet alert
            swal({
                title: "Apakah anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                buttons: ["Batal", "Hapus"],
            }).then((result) => {
                if (result) {
                    // ajax request
                    $.ajax({
                        url: '/class/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            // if request is successfull
                            if (data.code == 200) {
                                // show function showToast
                                showToast('bg-success', 'top-0 end-0', data.message);
                                // reload url
                                // settime 1s
                                setTimeout(function() {
                                    location.reload();
                                }, 1000);
                            } else {
                                // show error message
                                showToast('bg-danger', 'top-0 end-0', data.message);
                            }
                        }
                    });
                }
            });
        }

        function cancel() {
            $('#alert_name').text('');
            $('#name').val('');
            $('#btn-save').text('Simpan');
            $('.card-header h4').text('Tambah Kelas');
            $('#cancel-update').html('');
            $('#class_id').val('');
        };

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
