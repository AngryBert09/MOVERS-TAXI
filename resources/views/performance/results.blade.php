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
                            <h3 class="page-title">Performance Results</h3>
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
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Trainee ID</th>
                                        <th>Evaluation Date</th>
                                        <th>Customer Exp</th>
                                        <th>Marketing</th>
                                        <th>Management</th>
                                        <th>Admin</th>
                                        <th>Presentation</th>
                                        <th>Quality</th>
                                        <th>Efficiency</th>
                                        <th>Integrity</th>
                                        <th>Professionalism</th>
                                        <th>Team Work</th>
                                        <th>Critical Thinking</th>
                                        <th>Conflict Mgmt</th>
                                        <th>Attendance</th>
                                        <th>Deadlines</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($trainees as $trainee)
                                        <tr>
                                            <td>{{ $trainee->trainee_id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($trainee->evaluation_date)->format('M d, Y') }}
                                            </td>
                                            <td>{{ $trainee->customer_experience }}</td>
                                            <td>{{ $trainee->marketing }}</td>
                                            <td>{{ $trainee->management }}</td>
                                            <td>{{ $trainee->administration }}</td>
                                            <td>{{ $trainee->presentation_skill }}</td>
                                            <td>{{ $trainee->quality_of_work }}</td>
                                            <td>{{ $trainee->efficiency }}</td>
                                            <td>{{ $trainee->integrity }}</td>
                                            <td>{{ $trainee->professionalism }}</td>
                                            <td>{{ $trainee->team_work }}</td>
                                            <td>{{ $trainee->critical_thinking }}</td>
                                            <td>{{ $trainee->conflict_management }}</td>
                                            <td>{{ $trainee->attendance }}</td>
                                            <td>{{ $trainee->ability_to_meet_deadline }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $trainee->status == 'passed' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($trainee->status) }}
                                                </span>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="18" class="text-center">No evaluation records found</td>
                                        </tr>
                                    @endforelse
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
