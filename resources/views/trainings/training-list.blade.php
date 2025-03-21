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
                                        <th>Employee</th>
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
                                                            src="assets/img/profiles/avatar-02.jpg"></a>
                                                    <a href="profile.html">{{ $training->trainer }}</a>
                                                </h2>
                                            </td>
                                            <td>
                                                <ul class="team-members">
                                                    @foreach ($training->employees as $employee)
                                                        <li>
                                                            <a href="#" title="{{ $employee['name'] }}"
                                                                data-toggle="tooltip">
                                                                <img alt="" src="{{ $employee['avatar'] }}">
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                    @if (count($training->employees) > 3)
                                                        <li class="dropdown avatar-dropdown">
                                                            <a href="#" class="all-users dropdown-toggle"
                                                                data-toggle="dropdown" aria-expanded="false">
                                                                +{{ count($training->employees) - 3 }}
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <div class="avatar-group">
                                                                    @foreach (array_slice($training->employees, 3) as $employee)
                                                                        <a class="avatar avatar-xs" href="#">
                                                                            <img alt=""
                                                                                src="{{ $employee['avatar'] }}">
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </td>
                                            <td>{{ date('d M Y', strtotime($training->start_date)) }} -
                                                {{ date('d M Y', strtotime($training->end_date)) }}</td>
                                            <td>{{ $training->description }}</td>
                                            <td>${{ number_format($training->training_cost, 2) }}</td>
                                            <td>
                                                <div class="dropdown action-label">
                                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                                        href="#" data-toggle="dropdown" aria-expanded="false">
                                                        @if ($training->status == 'Active')
                                                            <i class="fa fa-dot-circle-o text-success"></i> Active
                                                        @else
                                                            <i class="fa fa-dot-circle-o text-danger"></i> Inactive
                                                        @endif
                                                    </a>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="#"><i
                                                                class="fa fa-dot-circle-o text-success"></i> Active</a>
                                                        <a class="dropdown-item" href="#"><i
                                                                class="fa fa-dot-circle-o text-danger"></i> Inactive</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false">
                                                        <i class="material-icons">more_vert</i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#edit_training" data-id="{{ $training->id }}"
                                                            data-training_type="{{ $training->training_type }}"
                                                            data-trainer="{{ $training->trainer }}"
                                                            data-cost="{{ $training->training_cost }}"
                                                            data-start_date="{{ $training->start_date }}"
                                                            data-end_date="{{ $training->end_date }}"
                                                            data-description="{{ $training->description }}"><i
                                                                class="fa fa-pencil m-r-5"></i> Edit</a>
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#delete_training"
                                                            data-id="{{ $training->id }}"><i
                                                                class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>
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
                                            <select class="form-control" name="training_type_id" required>
                                                <option value="">Select Training Type</option>
                                                @foreach ($trainingTypes as $type)
                                                    <option value="{{ $type->id }}">{{ $type->type_name }}
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
                                            <select class="form-control" name="trainer_id" required>
                                                <option value="">Select Trainer</option>
                                                @foreach ($trainers as $trainer)
                                                    @if ($trainer->status === 'Active')
                                                        <option value="{{ $trainer->id }}">
                                                            {{ $trainer->first_name }} {{ $trainer->last_name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Employees (Multiple Selection) -->
                                    {{-- <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Employees <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control select2" name="employee_ids[]" multiple
                                                required>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}">
                                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}

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

                                    <!-- Status -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Status</label>
                                            <select class="form-control" name="status">
                                                <option value="Active">Active</option>
                                                <option value="Inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /Add Training List Modal -->

            <!-- /Add Training List Modal -->

            <!-- Edit Training List Modal -->
            <div id="edit_training" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Training List</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Training Type</label>
                                            <select class="select">
                                                <option selected>Node Training</option>
                                                <option>Swift Training</option>
                                                <option>Git Training</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Trainer</label>
                                            <select class="select">
                                                <option>Mike Litorus </option>
                                                <option selected>John Doe</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Employees</label>
                                            <select class="select">
                                                <option>Bernardo Galaviz</option>
                                                <option selected>Jeffrey Warden</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label class="col-form-label">Training Cost <span
                                                    class="text-danger">*</span></label>
                                            <input class="form-control" type="text" value="$400">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Start Date <span class="text-danger">*</span></label>
                                            <div class="cal-icon"><input class="form-control datetimepicker"
                                                    value="07-08-2019" type="text"></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>End Date <span class="text-danger">*</span></label>
                                            <div class="cal-icon"><input class="form-control datetimepicker"
                                                    value="10-08-2019" type="text"></div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Description <span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="4">Lorem ipsum ismap</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label class="col-form-label">Status</label>
                                            <select class="select">
                                                <option selected>Active</option>
                                                <option>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
