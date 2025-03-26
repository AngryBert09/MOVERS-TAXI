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
                            <h3 class="page-title">Performance Evaluation</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Performance</li>
                            </ul>
                        </div>
                        <div class="col-auto float-right ml-auto">

                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="row">
                    <div class="col-md-12">


                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0 datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Department</th>
                                        <th>Apply Date</th>
                                        <th>Resume</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="applicantTableBody">
                                    @foreach ($trainees as $trainee)
                                        <tr>
                                            <td>{{ $trainee->id }}</td>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a href="#" class="avatar"><img alt=""
                                                            src="assets/img/default.jpg"></a>
                                                    <a href="#">{{ $trainee->name }}</a>
                                                </h2>
                                            </td>
                                            <td>{{ $trainee->email }}</td>
                                            <td>{{ $trainee->phone }}</td>
                                            <td>{{ $trainee->jobPosting ? $trainee->jobPosting->department : 'N/A' }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($trainee->apply_date)->format('d M Y') }}</td>
                                            <td>
                                                <a href="{{ asset('storage/' . $trainee->resume) }}"
                                                    class="btn btn-sm btn-primary" download>
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false"><i
                                                            class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if ($trainee->performanceEvaluations->isEmpty())
                                                            <!-- Display the Evaluate link -->
                                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                                data-target="#edit_appraisal_{{ $trainee->id }}">
                                                                <i class="fa fa-pencil m-r-5"></i> Evaluate
                                                            </a>
                                                        @else
                                                            <!-- Display "Evaluated" -->
                                                            <span class="dropdown-item text-muted">
                                                                <i class="fa fa-check m-r-5"></i> Evaluated
                                                            </span>
                                                        @endif
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



            <!-- Edit Performance Appraisal Modal -->
            @foreach ($trainees as $trainee)
                <div id="edit_appraisal_{{ $trainee->id }}" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Performance Evaluation</h5>
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
            @endforeach

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
    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <!-- Slimscroll JS -->
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>

    <!-- Select2 JS -->
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    <!-- Datatable JS -->
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Datetimepicker JS -->
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>


</body>

</html>
