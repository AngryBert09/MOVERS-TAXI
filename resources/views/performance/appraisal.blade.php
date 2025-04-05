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
                                        <th>Position</th>
                                        <th>Gender</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="applicantTableBody">
                                    @if ($trainees && count($trainees) > 0)
                                        @foreach ($trainees as $trainee)
                                            <tr>
                                                <td>{{ $trainee['id'] }}</td>
                                                <td>
                                                    <h2 class="table-avatar">
                                                        <a href="#" class="avatar">
                                                            <img alt=""
                                                                src="{{ asset('assets/img/default.jpg') }}">
                                                        </a>
                                                        <a href="#">{{ $trainee['first_name'] }}
                                                            {{ $trainee['last_name'] }}</a>
                                                    </h2>
                                                </td>
                                                <td>{{ $trainee['email'] }}</td>
                                                <td>{{ $trainee['contact'] }}</td>
                                                <td>{{ $trainee['department'] ?? 'N/A' }}</td>
                                                <td>{{ $trainee['position'] }}</td>
                                                <td>{{ $trainee['gender'] }}</td>

                                                <td class="text-right">
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                            <i class="material-icons">more_vert</i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            @if (!in_array($trainee['id'], $evaluations))
                                                                <a class="dropdown-item" href="#"
                                                                    data-toggle="modal"
                                                                    data-target="#edit_appraisal_{{ $trainee['id'] }}">
                                                                    <i class="fa fa-pencil m-r-5"></i> Evaluate
                                                                </a>
                                                            @else
                                                                <span class="dropdown-item text-muted">
                                                                    <i class="fa fa-check m-r-5"></i> Evaluated
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->



            <!-- Edit Performance Appraisal Modal -->
            @if ($trainees && count($trainees) > 0)
                @foreach ($trainees as $trainee)
                    <div id="edit_appraisal_{{ $trainee['id'] }}" class="modal fade" role="dialog">
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
                                                    <input type="text" class="form-control"
                                                        value="{{ $trainee['first_name'] }} {{ $trainee['last_name'] }}"
                                                        readonly />
                                                    <input type="hidden" name="trainee_id"
                                                        value="{{ $trainee['id'] }}" />
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <h4 class="modal-sub-title">Technical</h4>
                                                @foreach ([
        'customer_experience' => 'Customer Experience',
        'marketing' => 'Marketing',
        'management' => 'Management',
        'administration' => 'Administration',
        'presentation_skill' => 'Presentation Skill',
        'quality_of_work' => 'Quality Of Work',
        'efficiency' => 'Efficiency',
    ] as $name => $label)
                                                    <div class="form-group">
                                                        <label class="col-form-label">{{ $label }}</label>
                                                        <select name="{{ $name }}" class="select">
                                                            <option selected disabled>Select</option>
                                                            <option>None</option>
                                                            <option>Beginner</option>
                                                            <option>Intermediate</option>
                                                            <option>Advanced</option>
                                                            <option>Expert / Leader</option>
                                                        </select>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-sm-6">
                                                <h4 class="modal-sub-title">Organizational</h4>
                                                @foreach ([
        'integrity' => 'Integrity',
        'professionalism' => 'Professionalism',
        'team_work' => 'Team Work',
        'critical_thinking' => 'Critical Thinking',
        'conflict_management' => 'Conflict Management',
        'attendance' => 'Attendance',
        'ability_to_meet_deadline' => 'Ability To Meet Deadline',
    ] as $name => $label)
                                                    <div class="form-group">
                                                        <label class="col-form-label">{{ $label }}</label>
                                                        <select name="{{ $name }}" class="select">
                                                            <option selected disabled>Select</option>
                                                            <option>None</option>
                                                            <option>Beginner</option>
                                                            <option>Intermediate</option>
                                                            <option>Advanced</option>
                                                            <option>Expert / Leader</option>
                                                        </select>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">Status</label>
                                                    <select name="status" class="select">
                                                        <option selected disabled>Select</option>
                                                        <option>Active</option>
                                                        <option>Inactive</option>
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
            @endif
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
