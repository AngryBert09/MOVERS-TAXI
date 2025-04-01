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
                            <h3 class="page-title">Employees</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Employees</li>
                            </ul>
                        </div>
                        <div class="col-auto float-right ml-auto">

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
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Birth Date</th>
                                        <th>Job Type</th>
                                        <th class="text-center">Gender</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Actions</th> <!-- Added actions column -->
                                    </tr>
                                </thead>
                                <tbody id="applicantTableBody">
                                    @foreach ($employees as $employee)
                                        <tr>
                                            <td>{{ $employee['id'] }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar">
                                                        <img alt="Profile Picture"
                                                            src="{{ asset('assets/img/default.jpg') }}">
                                                    </a>
                                                    <a href="#">{{ $employee['first_name'] }}
                                                        {{ $employee['last_name'] }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $employee['email'] }}</td>
                                            <td>{{ $employee['contact'] ?? 'N/A' }}</td>
                                            <td>{{ $employee['department'] ?? 'N/A' }}</td>
                                            <td>{{ $employee['position'] ?? 'N/A' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($employee['bdate'])->format('d M Y') }}</td>
                                            <td>{{ ucfirst($employee['job_type']) }}</td>
                                            <td class="text-center">{{ ucfirst($employee['gender']) }}</td>
                                            <td>
                                                <span
                                                    class="
                                                {{ $employee['status'] == 'active'
                                                    ? 'text-success'
                                                    : ($employee['status'] == 'inactive'
                                                        ? 'text-danger'
                                                        : 'text-warning') }}">
                                                    <i class="fa fa-dot-circle-o"></i>
                                                    {{ ucfirst($employee['status']) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <!-- Trigger modal with employee's achievements -->
                                                <a href="#viewAchievementsModal{{ $employee['id'] }}"
                                                    class="btn btn-primary btn-sm" data-toggle="modal">
                                                    View Achievements
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Modal for each employee -->
                                        <div class="modal fade" id="viewAchievementsModal{{ $employee['id'] }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="viewAchievementsModalLabel{{ $employee['id'] }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title"
                                                            id="viewAchievementsModalLabel{{ $employee['id'] }}">
                                                            <i class="fa fa-trophy"></i> Training Achievements:
                                                            {{ $employee['first_name'] }} {{ $employee['last_name'] }}
                                                        </h5>
                                                        <button type="button" class="close text-white"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- List the achievements here -->
                                                        @if (count($employee['achievements']) > 0)
                                                            <div class="list-group">
                                                                @foreach ($employee['achievements'] as $achievement)
                                                                    <div class="list-group-item">
                                                                        <h6 class="font-weight-bold">
                                                                            {{ $achievement->type }}</h6>
                                                                        <p class="text-muted">
                                                                            <small>Completed on:
                                                                                {{ \Carbon\Carbon::parse($achievement->created_at)->format('d M Y') }}</small>
                                                                        </p>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <p class="text-center text-muted">No achievements available.
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal"><i class="fa fa-times"></i>
                                                            Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>



                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->



            <!-- Edit Performance Appraisal Modal -->
            {{-- @foreach ($trainees as $trainee)
                <div id="edit_appraisal_{{ $trainee->id }}" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Performance Appraisal</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('performance.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label">Trainee</label>
                                                <input type="text" class="form-control" value="{{ $trainee->name }}"
                                                    readonly>
                                                <input type="hidden" name="trainee_id" value="{{ $trainee->id }}">
                                            </div>

                                        </div>
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="tab-box">
                                                        <div class="row user-tabs">
                                                            <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                                                                <ul class="nav nav-tabs nav-tabs-solid">
                                                                    <li class="nav-item">
                                                                        <a href="#appr_technical1" data-toggle="tab"
                                                                            class="nav-link active">Technical</a>
                                                                    </li>
                                                                    <li class="nav-item">
                                                                        <a href="#appr_organizational1"
                                                                            data-toggle="tab"
                                                                            class="nav-link">Organizational</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="tab-content">
                                                        <div id="appr_technical1"
                                                            class="pro-overview tab-pane fade show active">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="bg-white">
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Technical Competencies</th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <th colspan="2">Indicator</th>
                                                                                    <th colspan="2">Expected Value
                                                                                    </th>
                                                                                    <th>Set Value</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Customer
                                                                                        Experience</td>
                                                                                    <td colspan="2">Intermediate
                                                                                    </td>
                                                                                    <td>
                                                                                        <select
                                                                                            name="customer_experience"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                            <option
                                                                                                value="Expert / Leader">
                                                                                                Expert / Leader</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Marketing</td>
                                                                                    <td colspan="2">Advanced</td>
                                                                                    <td>
                                                                                        <select name="marketing"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                            <option
                                                                                                value="Expert / Leader">
                                                                                                Expert / Leader</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Management</td>
                                                                                    <td colspan="2">Advanced</td>
                                                                                    <td>
                                                                                        <select name="management"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                            <option
                                                                                                value="Expert / Leader">
                                                                                                Expert / Leader</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Administration
                                                                                    </td>
                                                                                    <td colspan="2">Advanced</td>
                                                                                    <td>
                                                                                        <select name="administration"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                            <option
                                                                                                value="Expert / Leader">
                                                                                                Expert / Leader</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Presentation
                                                                                        Skill</td>
                                                                                    <td colspan="2">Expert / Leader
                                                                                    </td>
                                                                                    <td>
                                                                                        <select
                                                                                            name="presentation_skill"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                            <option
                                                                                                value="Expert / Leader">
                                                                                                Expert / Leader</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Quality Of Work
                                                                                    </td>
                                                                                    <td colspan="2">Expert / Leader
                                                                                    </td>
                                                                                    <td>
                                                                                        <select name="quality_of_work"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                            <option
                                                                                                value="Expert / Leader">
                                                                                                Expert / Leader</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Efficiency</td>
                                                                                    <td colspan="2">Expert / Leader
                                                                                    </td>
                                                                                    <td>
                                                                                        <select name="efficiency"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                            <option
                                                                                                value="Expert / Leader">
                                                                                                Expert / Leader</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="appr_organizational1">
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="bg-white">
                                                                        <table class="table">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Organizational Competencies</th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                    <th></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <th colspan="2">Indicator</th>
                                                                                    <th colspan="2">Expected Value
                                                                                    </th>
                                                                                    <th>Set Value</th>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Integrity</td>
                                                                                    <td colspan="2">Beginner</td>
                                                                                    <td>
                                                                                        <select name="integrity"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Professionalism
                                                                                    </td>
                                                                                    <td colspan="2">Beginner</td>
                                                                                    <td>
                                                                                        <select name="professionalism"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Team Work</td>
                                                                                    <td colspan="2">Intermediate
                                                                                    </td>
                                                                                    <td>
                                                                                        <select name="team_work"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Critical
                                                                                        Thinking</td>
                                                                                    <td colspan="2">Advanced</td>
                                                                                    <td>
                                                                                        <select
                                                                                            name="critical_thinking"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Conflict
                                                                                        Management</td>
                                                                                    <td colspan="2">Intermediate
                                                                                    </td>
                                                                                    <td>
                                                                                        <select
                                                                                            name="conflict_management"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Attendance</td>
                                                                                    <td colspan="2">Intermediate
                                                                                    </td>
                                                                                    <td>
                                                                                        <select name="attendance"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">Ability To Meet
                                                                                        Deadline</td>
                                                                                    <td colspan="2">Advanced</td>
                                                                                    <td>
                                                                                        <select
                                                                                            name="ability_to_meet_deadline"
                                                                                            class="form-control">
                                                                                            <option value="">None
                                                                                            </option>
                                                                                            <option value="Beginner">
                                                                                                Beginner</option>
                                                                                            <option
                                                                                                value="Intermediate">
                                                                                                Intermediate</option>
                                                                                            <option value="Advanced">
                                                                                                Advanced</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label class="col-form-label">Status</label>
                                                <select name="status" class="select">
                                                    <option value="Active">Active</option>
                                                    <option value="Inactive">Inactive</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="submit-section">
                                        <button type="submit" class="btn btn-primary submit-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach --}}

            <!-- /Edit Performance Appraisal Modal -->

            <!-- Delete Performance Appraisal Modal -->
            <div class="modal custom-modal fade" id="delete_appraisal" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Performance Appraisal List</h3>
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
            <!-- /Delete Performance Appraisal Modal -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->

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
    <script>
        < script >
            $(document).ready(function() {
                $("#searchInput").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#applicantTableBody tr").filter(function() {
                        $(this).toggle(
                            $(this).text().toLowerCase().indexOf(value) > -1
                        );
                    });
                });
            }); <
        />
    </script>
    <!-- jQuery -->
    <script src="assets/js/jquery-3.5.1.min.js"></script>

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
