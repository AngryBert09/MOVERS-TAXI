<!DOCTYPE html>
<html lang="en">

@include('layout.head')

<body>
    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
        @include('layout.navbar')
        <!-- /Header -->

        <!-- Sidebar -->
        @include('layout.left-sidebar')
        <!-- /Sidebar -->

        <!-- Page Wrapper -->
        <div class="page-wrapper">

            <!-- Page Content -->
            <div class="content container-fluid">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Training</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Training</li>
                            </ul>
                        </div>
                        <div class="col-auto float-right ml-auto">
                            <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_training"><i
                                    class="fa fa-plus"></i> Add New </a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="search-container mb-4">
                            <div class="input-group" style="max-width: 300px; float: right;">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search..."
                                    aria-label="Search">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0 datatable">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">#</th>
                                        <th>Training Type</th>
                                        <th>Trainer</th>
                                        <th>Trainee</th>
                                        <th>Time Duration</th>
                                        <th>Description </th>
                                        <th>Cost </th>
                                        <th>Status </th>

                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="applicantTableBody">
                                    @foreach ($trainings as $training)
                                        <tr>
                                            <td>{{ $training->id }}</td>
                                            <td>{{ $training->training_type }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="profile.html" class="avatar"><img alt=""
                                                            src="assets/img/default.jpg"></a>
                                                    <a href="profile.html">{{ $training->trainer }}</a>
                                                </h2>
                                            </td>
                                            <td>
                                                @php

                                                    $trainee = collect($employees)->firstWhere(
                                                        'id',
                                                        $training->trainee_id,
                                                    );
                                                @endphp
                                                {{ $trainee ? $trainee['first_name'] . ' ' . $trainee['last_name'] : 'N/A' }}
                                            </td>
                                            <td>{{ date('d M Y', strtotime($training->start_date)) }} -
                                                {{ date('d M Y', strtotime($training->end_date)) }}</td>
                                            <td>{{ $training->description }}</td>
                                            <td>₱{{ number_format($training->training_cost, 2) }}</td>
                                            <td>
                                                @if ($training->status == 'Pending')
                                                    <i class="fa fa-dot-circle-o text-warning"></i> Pending
                                                @elseif ($training->status == 'Ongoing')
                                                    <i class="fa fa-dot-circle-o text-success"></i> Ongoing
                                                @elseif ($training->status == 'Completed')
                                                    <i class="fa fa-dot-circle-o text-blue"></i> Completed
                                                @else
                                                    <i class="fa fa-dot-circle-o text-danger"></i> Unknown
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        <i class="material-icons">more_vert</i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#edit_training_{{ $training->id }}">
                                                            <i class="fa fa-pencil m-r-5"></i> Edit
                                                        </a>
                                                        @if ($training->status == 'Completed')
                                                            <a class="dropdown-item"
                                                                href="{{ route('trainings.certificate', $training->id) }}"
                                                                target="_blank">
                                                                <i class="fa fa-file-pdf-o m-r-5"></i> View Certificate
                                                            </a>
                                                        @endif

                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Delete Budget Request Modal -->
                                            <div class="modal custom-modal fade" id="deleteBudgetModal" role="dialog">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="form-header">
                                                                <h3>Delete Budget Request</h3>
                                                                <p>Are you sure you want to delete this Training?
                                                                </p>
                                                            </div>
                                                            <div class="modal-btn delete-action">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <form id="deleteBudgetForm"
                                                                            action="{{ route('trainings.destroy', $training->id) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit"
                                                                                class="btn btn-danger btn-lg btn-block continue-btn">
                                                                                Delete
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <a href="javascript:void(0);"
                                                                            data-dismiss="modal"
                                                                            class="btn btn-secondary btn-lg cancel-btn">Cancel</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->


            <!-- Add Training List Modal -->
            <div id="add_training" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Training</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('trainings.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <!-- Training Type -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Training Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" name="training_type" required>
                                                <option value="">Select Training Type</option>
                                                @foreach ($trainingTypes as $type)
                                                    <option value="{{ $type->type_name }}">{{ $type->type_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Trainer (Only Active Trainers) -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Trainer <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" name="trainer" required>
                                                <option value="">Select Trainer</option>
                                                @foreach ($trainers as $trainer)
                                                    @if ($trainer->status === 'Active')
                                                        <option
                                                            value="{{ $trainer->first_name . ' ' . $trainer->last_name }}">
                                                            {{ $trainer->first_name }} {{ $trainer->last_name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Employees (Multiple Selection) -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Trainees</label>
                                            <select class="form-control select" name="trainee_id">
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee['id'] }}">
                                                        {{ $employee['first_name'] }} {{ $employee['last_name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Training Cost -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Training Cost <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" type="number" name="training_cost"
                                                min="0" required>
                                        </div>
                                    </div>

                                    <!-- Start Date -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Start Date <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input class="form-control datetimepicker" type="text"
                                                    name="start_date" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- End Date -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>End Date <span class="text-danger">*</span></label>
                                            <div class="cal-icon">
                                                <input class="form-control datetimepicker" type="text"
                                                    name="end_date" required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="4" name="description" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn px-4 w-100">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Edit Training List Modal -->
            @foreach ($trainings as $training)
                <div id="edit_training_{{ $training->id }}" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Training List</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('trainings.update', $training->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <!-- Training Type -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Training Type</label>
                                                <select class="form-control" name="training_type"
                                                    {{ $training->status == 'Completed' ? 'disabled' : '' }}>
                                                    @foreach ($trainingTypes as $type)
                                                        <option value="{{ $type->type_name }}"
                                                            {{ $type->type_name == $training->training_type ? 'selected' : '' }}>
                                                            {{ $type->type_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Trainer -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Trainer</label>
                                                <select class="form-control" name="trainer"
                                                    {{ $training->status == 'Completed' ? 'disabled' : '' }}>
                                                    @foreach ($trainers as $trainer)
                                                        <option
                                                            value="{{ $trainer->first_name }} {{ $trainer->last_name }}"
                                                            {{ $trainer->first_name . ' ' . $trainer->last_name == $training->trainer ? 'selected' : '' }}>
                                                            {{ $trainer->first_name }} {{ $trainer->last_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Employees -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Trainees</label>
                                                <select class="form-control select" name="trainee_id"
                                                    {{ $training->status == 'Completed' ? 'disabled' : '' }}>
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee['id'] }}"
                                                            {{ $employee['id'] == $training->trainee_id ? 'selected' : '' }}>
                                                            {{ $employee['first_name'] }} {{ $employee['last_name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Training Cost -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Training Cost <span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" type="number" name="training_cost"
                                                    value="{{ $training->training_cost }}"
                                                    {{ $training->status == 'Completed' ? 'disabled' : '' }}>
                                            </div>
                                        </div>

                                        <!-- Start Date -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Start Date <span class="text-danger">*</span></label>
                                                <input class="form-control" type="date" name="start_date"
                                                    value="{{ $training->start_date->format('Y-m-d') }}"
                                                    {{ $training->status == 'Completed' ? 'disabled' : '' }} required>
                                            </div>
                                        </div>

                                        <!-- End Date -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>End Date <span class="text-danger">*</span></label>
                                                <input class="form-control" type="date" name="end_date"
                                                    value="{{ $training->end_date->format('Y-m-d') }}"
                                                    {{ $training->status == 'Completed' ? 'disabled' : '' }} required>
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Description <span class="text-danger">*</span></label>
                                                <textarea class="form-control" rows="4" name="description"
                                                    {{ $training->status == 'Completed' ? 'disabled' : '' }} required>{{ $training->description }}</textarea>
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label">Status</label>
                                                <select class="form-control" name="status"
                                                    {{ $training->status == 'Completed' ? 'disabled' : '' }}>
                                                    @if ($training->status == 'Pending')
                                                        <option value="Ongoing"
                                                            {{ $training->status == 'Ongoing' ? 'selected' : '' }}>
                                                            Ongoing
                                                        </option>
                                                    @elseif ($training->status == 'Ongoing')
                                                        <option value="Completed"
                                                            {{ $training->status == 'Completed' ? 'selected' : '' }}>
                                                            Completed</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="submit-section">
                                        <button type="submit" class="btn btn-primary submit-btn"
                                            {{ $training->status == 'Completed' ? 'disabled' : '' }}>Update
                                            Training</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            @foreach ($trainings as $training)
                <div id="edit_training_{{ $training->id }}" class="modal custom-modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Training List</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('trainings.update', $training->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <!-- Training Type -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Training Type</label>
                                                <select class="form-control" name="training_type">
                                                    @foreach ($trainingTypes as $type)
                                                        <option value="{{ $type->type_name }}"
                                                            {{ $type->type_name == $training->training_type ? 'selected' : '' }}>
                                                            {{ $type->type_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Trainer -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Trainer</label>
                                                <select class="form-control" name="trainer">
                                                    @foreach ($trainers as $trainer)
                                                        <option
                                                            value="{{ $trainer->first_name }} {{ $trainer->last_name }}"
                                                            {{ $trainer->first_name . ' ' . $trainer->last_name == $training->trainer ? 'selected' : '' }}>
                                                            {{ $trainer->first_name }} {{ $trainer->last_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Employees -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Trainees</label>
                                                <select class="form-control select" name="trainee_id">
                                                    @foreach ($employees as $employee)
                                                        <option value="{{ $employee['id'] }}"
                                                            {{ $employee['id'] == $training->trainee_id ? 'selected' : '' }}>
                                                            {{ $employee['first_name'] }} {{ $employee['last_name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Training Cost -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="col-form-label">Training Cost <span
                                                        class="text-danger">*</span></label>
                                                <input class="form-control" type="number" name="training_cost"
                                                    value="{{ $training->training_cost }}" disabled>
                                            </div>
                                        </div>

                                        <!-- Start Date -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>Start Date <span class="text-danger">*</span></label>
                                                <input class="form-control" type="date" name="start_date"
                                                    value="{{ $training->start_date->format('Y-m-d') }}" required>
                                            </div>
                                        </div>

                                        <!-- End Date -->
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label>End Date <span class="text-danger">*</span></label>
                                                <input class="form-control" type="date" name="end_date"
                                                    value="{{ $training->end_date->format('Y-m-d') }}" required>
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Description <span class="text-danger">*</span></label>
                                                <textarea class="form-control" rows="4" name="description" required>{{ $training->description }}</textarea>
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label">Status</label>
                                                <select class="form-control" name="status"
                                                    {{ $training->status == 'Completed' ? 'disabled' : '' }}>
                                                    <option value="Active"
                                                        {{ $training->status == 'Active' ? 'selected' : '' }}>Active
                                                    </option>
                                                    <option value="Inactive"
                                                        {{ $training->status == 'Inactive' ? 'selected' : '' }}>
                                                        Inactive</option>
                                                    <option value="Completed"
                                                        {{ $training->status == 'Completed' ? 'selected' : '' }}>
                                                        Completed</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="submit-section">
                                        <button type="submit" class="btn btn-primary submit-btn"
                                            {{ $training->status == 'Completed' ? 'disabled' : '' }}>Update
                                            Training</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach


            <!-- /Edit Training List Modal -->

            <!-- Delete Training List Modal -->
            <div class="modal custom-modal fade" id="delete_training" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Training List</h3>
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal"
                                            class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Delete Training List Modal -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="assets/js/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#applicantTableBody tr").filter(function() {
                    $(this).toggle(
                        $(this).text().toLowerCase().indexOf(value) > -1
                    );
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll("form").forEach(form => {
                let startDateInput = form.querySelector("input[name='start_date']");
                let endDateInput = form.querySelector("input[name='end_date']");

                if (startDateInput && endDateInput) {
                    startDateInput.addEventListener("change", function() {
                        let startDate = new Date(this.value);

                        // Set the minimum date for the end date
                        endDateInput.min = this.value;

                        // Reset end date if it's before start date
                        if (endDateInput.value && new Date(endDateInput.value) < startDate) {
                            endDateInput.value = this.value;
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(".datetimepicker").datetimepicker({
                format: "DD/MM/YYYY", // Ensures correct format
                useCurrent: false
            });

            $("input[name='start_date']").on("dp.change", function(e) {
                let startDate = e.date ? e.date.format("DD/MM/YYYY") : null;
                let endDatePicker = $(this).closest("form").find("input[name='end_date']");

                if (startDate) {
                    endDatePicker.data("DateTimePicker").minDate(startDate);
                }
            });

            $("input[name='end_date']").on("dp.change", function(e) {
                let startDatePicker = $(this).closest("form").find("input[name='start_date']");
                let startDate = startDatePicker.val();
                let endDate = e.date ? e.date.format("DD/MM/YYYY") : null;

                if (startDate && endDate && endDate < startDate) {
                    alert("End date cannot be before start date!");
                    $(this).val(startDate); // Reset end date if invalid
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Try Again'
                });
            @endif
        });
    </script>

    <!-- Bootstrap Core JS -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Slimscroll JS -->
    <script src="assets/js/jquery.slimscroll.min.js"></script>

    <!-- Select2 JS -->
    <script src="assets/js/select2.min.js"></script>

    <!-- Datatable JS -->
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap4.min.js"></script>

    <!-- Datetimepicker JS -->
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>

</body>

</html>
