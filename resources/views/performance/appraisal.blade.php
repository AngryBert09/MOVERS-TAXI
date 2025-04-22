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
                            <div class="modal-content shadow-lg border-0 rounded-3">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title">Performance Evaluation</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body px-4 py-3">
                                    <form action="{{ route('performance.store') }}" method="POST">
                                        @csrf

                                        <div class="form-group">
                                            <label><strong>Employee</strong></label>
                                            <input type="text" class="form-control mb-2"
                                                value="{{ $trainee['first_name'] }} {{ $trainee['last_name'] }}"
                                                readonly />
                                            <input type="hidden" name="trainee_id" value="{{ $trainee['id'] }}" />
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 pl-4">
                                                <h5 class="text-primary mb-3">Employee Performance Evaluation</h5>
                                                @foreach ([
        'punctuality' => 'Punctuality and attendance consistency',
        'quality' => 'Quality and accuracy of work',
        'communication' => 'Communication with team and supervisors',
        'feedback' => 'Willingness to accept feedback and improve',
        'problem_solving' => 'Ability to solve problems and take initiative',
        'teamwork' => 'Teamwork and collaboration',
        'attitude' => 'Attitude and professionalism',
        'adaptability' => 'Adaptability to change and pressure',
        'time_management' => 'Time management and meeting deadlines',
        'behavior' => 'Behavior in workplace (respect, conduct, ethics)',
    ] as $name => $label)
                                                    <div class="mb-3">
                                                        <label>{{ $label }}</label>
                                                        <div class="d-flex gap-2">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <div class="form-check form-check-inline">
                                                                    <input
                                                                        class="form-check-input checkbox-group-{{ $name }}"
                                                                        type="checkbox"
                                                                        name="performance[{{ $name }}]"
                                                                        value="{{ $i }}"
                                                                        onclick="checkOnlyOne(this, 'checkbox-group-{{ $name }}')"
                                                                        id="checkbox-{{ $name }}-{{ $i }}">
                                                                    <label class="form-check-label"
                                                                        for="checkbox-{{ $name }}-{{ $i }}">{{ $i }}</label>
                                                                </div>
                                                            @endfor
                                                        </div>


                                                    </div>
                                                @endforeach

                                                {{-- Supervisor Feedback --}}
                                            </div> <!-- closes the col-md-6 -->

                                        </div> <!-- close col-md-6 -->

                                        <div class="col-12 mt-3">
                                            <div class="mb-3">
                                                <label for="supervisor_feedback"><strong>Supervisor
                                                        Feedback</strong></label>
                                                <textarea name="supervisor_feedback" id="supervisor_feedback" rows="4" class="form-control w-100"
                                                    placeholder="Provide additional comments or recommendations here..."></textarea>
                                            </div>

                                            <div class="submit-section text-right mt-4">
                                                <button type="submit" class="btn btn-primary px-4 w-100">Save</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <!-- JavaScript to allow only one checkbox per group -->
            <script>
                // JavaScript function to ensure only one checkbox is selected
                function checkOnlyOne(checkbox, group) {
                    const checkboxes = document.querySelectorAll(`.${group}`);
                    checkboxes.forEach((cb) => {
                        if (cb !== checkbox) cb.checked = false;
                    });
                }

                // Validate checkbox group before form submission
                function validateCheckboxGroup(name) {
                    const checkboxes = document.getElementsByName(`performance[${name}]`);
                    const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

                    if (!isChecked) {
                        alert('Please select a rating for ' + name);
                        return false;
                    }
                    return true;
                }

                // Attach the validation to form submit (you can adjust based on how you're submitting the form)
                document.querySelector('form').onsubmit = function() {
                    const checkboxNames = ['{{ $name }}']; // Add more names as needed
                    for (let name of checkboxNames) {
                        if (!validateCheckboxGroup(name)) {
                            return false; // Stop form submission if validation fails
                        }
                    }
                    return true; // Allow form submission if validation passes
                };
            </script>




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
