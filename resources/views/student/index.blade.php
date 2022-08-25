@extends('template.main')

@section('content')
    <!-- Content -->


    <div class="container-xxl flex-grow-1 container-p-y">


        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tables /</span> Basic Tables</h4>

        {{-- alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Basic Bootstrap Table -->
        <div class="card">

            <div class="d-flex justify-content-between bd-highlight">
                <div class="bd-highlight">
                    <h4>
                        <div class="m-3">Data</div>
                    </h4>
                    <h3>
                        <div class="ms-3">Students</div>
                    </h3>
                </div>
                <div class="bd-highlight pt-4">
                    <button type="button" class="btn btn-danger text-end" onclick="multi_delete()">Delete</button>
                    <button type="button" class="btn btn-primary me-3 text-end" onclick="add()">Add Student</button>
                    <form action="">
                        <input type="text" placeholder="Search ..." class="form-control mt-3" name="search"
                            id="search">
                    </form>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="basicModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Add Student</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="/student" method="POST">
                            <input type="hidden" id="student_id" value="">
                            @csrf
                            @method('POST')
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="studentIdNumber" class="form-label">Student ID Number</label>
                                        <input type="text" id="student_id_number" name="student_id_number"
                                            class="form-control" placeholder="Enter Student ID Number" required />
                                        <small class="text-danger" id="alert_student_id_number"></small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" id="name" name="name" class="form-control"
                                            placeholder="Enter Name" required />
                                        <small class="text-danger" id="alert_name"></small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="email" class="form-label">Class</label>
                                        <select name="student_class_id" id="student_class_id" class="form-control">
                                            <option value=""> - Select Class - </option>
                                            @foreach ($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-danger" id="alert_student_class_id"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Close
                                </button>
                                <button type="button" class="btn btn-primary" id="btn_save">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Toast with Placements -->
            <div class="bs-toast toast toast-placement-ex m-2" role="alert" aria-live="assertive" aria-atomic="true"
                data-delay="2000">
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
                            <th width="3%">#</th>
                            <th width="3%">No</th>
                            <th>Student ID Number</th>
                            <th>Name</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($students as $student)
                            <tr>
                                <td>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="checkbox"
                                            name="checkbox" value="{{ $student->id }}">
                                        <label class="custom-control-label" for="checkbox-{{ $student->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    {{ $no++ }}
                                </td>
                                <td>
                                    <strong>{{ $student->student_id_number }}</strong>
                                </td>
                                <td>{{ $student->name }}</td>

                                <td class="text-end">
                                    <a class="btn-sm btn-warning" href="javascript:void(0);"
                                        onclick="edit({{ $student->id }})"><i class="bx bx-edit-alt me-1"></i>
                                        Edit</a>
                                    <a class="btn-sm btn-danger" href="javascript:void(0);"
                                        onclick="delete_({{ $student->id }})"><i class="bx bx-trash me-1"></i>
                                        Delete</a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
            <div class="mt-3 mx-2">
                {{ $students->links() }}
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->

    </div>
    <!-- / Content -->
@endsection

@section('script')
    <script>
        // if btn_save is clicked
        $('#btn_save').click(function() {
            // get the value of the input field
            let student_id_number = $('#student_id_number').val();
            let name = $('#name').val();
            let student_class_id = $('#student_class_id').val();
            let student_id = $('#student_id').val();
            let mode = $('#exampleModalLabel1').text();
            let url_mode = '';
            let type_mode = '';

            $('#alert_student_id_number').text('');
            $('#alert_name').text('');
            $('#alert_student_class_id').text();

            if (mode == 'Add Student') {
                type_mode = 'POST';
                url_mode = '/student';
            } else {
                type_mode = 'PUT';
                url_mode = '/student/' + student_id;
            }

            // ajax request
            $.ajax({
                url: url_mode,
                type: type_mode,
                data: {
                    student_id_number: student_id_number,
                    name: name,
                    student_class_id: student_class_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    // if request is successfull
                    if (data.code == 200) {
                        // show function showToast
                        showToast('bg-success', 'top-0 end-0', data.message);

                        // hide modal
                        $('#basicModal').modal('hide');

                        // set time 1s
                        setTimeout(function() {
                            // reload url
                            if (mode == 'Add Student') {
                                let url = '/student';
                                window.location.href = url;
                            } else {
                                window.location.reload();
                            }

                        }, 1000);
                    } else if (data.code == 400) {
                        // show error message
                        // showToast('bg-danger', 'top-0 end-0', data.message);

                        $('#alert_student_id_number').text(data.errors.student_id_number);
                        $('#alert_name').text(data.errors.name);
                        $('#alert_student_class_id').text(data.errors.student_class_id);
                    } else {
                        // show error message
                        showToast('bg-danger', 'top-0 end-0', data.message);
                    }
                },
                error: function(data) {
                    // show error message
                    swal({
                        title: "Error!",
                        text: "Something went wrong!",
                        icon: "error",
                        button: "OK",
                    });
                }
            });
        });

        // if edit is clicked
        function edit(id) {
            $('#alert_student_id_number').text('');
            $('#alert_name').text('');
            $('#alert_student_class_id').text('');
            $('#exampleModalLabel1').text('Edit Student');

            // ajax request
            $.ajax({
                url: '/student/' + id,
                type: 'GET',
                success: function(data) {
                    // if request is successfull
                    if (data.code == 200) {
                        // set value of input field
                        $('#student_id_number').val(data.student.student_id_number);
                        $('#name').val(data.student.name);
                        $('#student_class_id').val(data.student.student_class_id);
                        $('#student_id').val(data.student.id);
                        // show modal
                        $('#basicModal').modal('show');
                    } else {
                        // show error message
                        showToast('bg-danger', 'top-0 end-0', data.message);
                    }
                }
            });
        }

        function add() {
            $('#alert_student_id_number').text('');
            $('#alert_name').text('');
            $('#alert_student_class_id').text('');
            $('#exampleModalLabel1').text('Add Student');
            $('#student_id_number').val('');
            $('#name').val('');
            $('#student_class_id').val('');
            $('#student_id').val('');
            $('#basicModal').modal('show');
        }

        function delete_(id) {
            // swal("Good job!", "You clicked the button!", "success");

            // sweet alert
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                buttons: ["Cancel", "Delete"],
            }).then((result) => {
                if (result) {
                    // ajax request
                    $.ajax({
                        url: '/student/' + id,
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
                                window.location.reload();
                            } else {
                                // show error message
                                showToast('bg-danger', 'top-0 end-0', data.message);
                            }
                        }
                    });
                }
            });
        }

        function multi_delete() {
            // swal("Good job!", "You clicked the button!", "success");
            // sweet alert
            let ids = [];
            $.each($("input[name='checkbox']:checked"), function() {
                ids.push($(this).val());
            });
            if (ids == '') {
                return swal("No data selected!", "", "warning");
            }

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                buttons: ["Cancel", "Delete"],
            }).then((result) => {
                if (result) {
                    // get all id of checkbox that is checked
                    // let ids = [];
                    // $.each($("input[name='checkbox']:checked"), function() {
                    //     ids.push($(this).val());
                    // });
                    // if (ids == '') {
                    //     return showToast('bg-danger', 'top-0 end-0', 'Please select data to delete');
                    // }

                    console.log(ids);
                    // ajax request
                    $.ajax({
                        url: '/student-m',
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: ids
                        },
                        success: function(data) {
                            // if request is successfull
                            if (data.code == 200) {
                                // show function showToast
                                showToast('bg-success', 'top-0 end-0', data.message);
                                // reload url
                                window.location.reload();
                            } else {
                                // show error message
                                showToast('bg-danger', 'top-0 end-0', data.message);
                            }
                            console.log(data);
                        }
                    });
                }
            });
        }

        function search() {
            // get value of input field
            let search = $('#search').val();
            // ajax request
            $.ajax({
                url: '/student-search',
                type: 'GET',
                data: {
                    search: search
                },
                success: function(data) {
                    console.log(data);
                }
            });
        }

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
