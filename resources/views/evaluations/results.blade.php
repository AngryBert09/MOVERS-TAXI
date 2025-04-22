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
                            <h3 class="page-title">Employee Performance Evaluation Results</h3>
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
                        <div class="search-container mb-4">
                            <div class="input-group" style="max-width: 300px; float: right;">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search..."
                                    aria-label="Search">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0 datatable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Employee Name</th>
                                        <th>Evaluation Date</th>
                                        <th>Department</th>
                                        <th>Performance Rating (Avg)</th>
                                        <th>Status</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($trainees as $trainee)
                                        <tr>
                                            <td>{{ $trainee['trainee_id'] }}</td>
                                            <td>{{ $trainee['full_name'] }}</td>
                                            <td>{{ \Carbon\Carbon::parse($trainee['evaluation_date'])->format('M d, Y') }}
                                            </td>
                                            <td>{{ $trainee['department'] }}</td>


                                            <!-- Performance Rating Average -->
                                            <td>
                                                @php
                                                    $performanceRatings = collect([
                                                        $trainee['punctuality'],
                                                        $trainee['quality'],
                                                        $trainee['communication'],
                                                        $trainee['feedback'],
                                                        $trainee['problem_solving'],
                                                        $trainee['teamwork'],
                                                        $trainee['attitude'],
                                                        $trainee['adaptability'],
                                                        $trainee['time_management'],
                                                        $trainee['behavior'],
                                                    ]);
                                                    $performanceAvg = $performanceRatings->filter()->avg();
                                                @endphp
                                                {{ $performanceAvg ? number_format($performanceAvg, 2) : 'N/A' }}
                                            </td>

                                            <!-- Status Calculation Based Only on Performance -->
                                            <td>
                                                @php
                                                    // Define the threshold for passing based on performance evaluation
                                                    $passThreshold = 3;
                                                    // Check if performance average is above or equal to the threshold
                                                    $status = $performanceAvg >= $passThreshold ? 'passed' : 'failed';
                                                @endphp
                                                <span
                                                    class="badge badge-{{ $status == 'passed' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($status) }}
                                                </span>
                                            </td>
                                            <td class="text-right">
                                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal"
                                                    data-target="#detailsModal{{ $trainee['trainee_id'] }}">
                                                    View Details
                                                </a>
                                                <a href="#" class="btn btn-sm btn-warning" data-toggle="modal"
                                                    data-target="#feedbackModal{{ $trainee['trainee_id'] }}">
                                                    Feedback
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Modal for trainee details -->
                                        <div class="modal fade" id="detailsModal{{ $trainee['trainee_id'] }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="detailsModalLabel{{ $trainee['trainee_id'] }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Evaluation Details:
                                                            {{ $trainee['full_name'] }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body px-4 py-3"
                                                        style="max-height: calc(100vh - 200px); overflow-y: auto;">
                                                        <h6><strong>Performance Evaluation</strong></h6>
                                                        <ul class="list-unstyled">
                                                            @php
                                                                $criteria = [
                                                                    'punctuality' => 'Punctuality',
                                                                    'quality' => 'Quality',
                                                                    'communication' => 'Communication',
                                                                    'feedback' => 'Feedback',
                                                                    'problem_solving' => 'Problem Solving',
                                                                    'teamwork' => 'Teamwork',
                                                                    'attitude' => 'Attitude',
                                                                    'adaptability' => 'Adaptability',
                                                                    'time_management' => 'Time Management',
                                                                    'behavior' => 'Behavior',
                                                                ];
                                                            @endphp

                                                            @foreach ($criteria as $key => $label)
                                                                <li class="mb-3">
                                                                    <strong>{{ $label }}:</strong>
                                                                    @php
                                                                        $rating = $trainee[$key] ?? 0;
                                                                    @endphp
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <i
                                                                            class="fa-star {{ $i <= $rating ? 'fas text-warning' : 'far text-muted' }}"></i>
                                                                    @endfor
                                                                    <span
                                                                        class="ml-2 text-muted">({{ $rating ?? 'N/A' }})</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal for feedback -->
                                        <div class="modal fade" id="feedbackModal{{ $trainee['trainee_id'] }}"
                                            tabindex="-1" role="dialog"
                                            aria-labelledby="feedbackModalLabel{{ $trainee['trainee_id'] }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content shadow-lg rounded-lg">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title text-dark font-weight-bold"
                                                            style="font-size: 1.25rem;">Supervisor Feedback for:
                                                            {{ $trainee['full_name'] }}</h5>
                                                        <button type="button" class="close text-dark"
                                                            data-dismiss="modal" aria-label="Close">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body px-4 py-3">
                                                        <div class="form-group">
                                                            <label for="feedback" class="text-muted font-weight-bold"
                                                                style="font-size: 1rem;">Supervisor Feedback</label>
                                                            <div id="feedback"
                                                                class="form-control-plaintext p-4 bg-white rounded-lg shadow-sm"
                                                                style="white-space: pre-wrap; overflow-y: auto; max-height: 400px; font-size: 1rem; line-height: 1.6;">
                                                                {{ $trainee['supervisor_feedback'] ?? 'No feedback available' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 d-flex justify-content-end">
                                                        <button type="button"
                                                            class="btn btn-secondary btn-sm rounded-pill text-uppercase"
                                                            data-dismiss="modal">
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No evaluation records found</td>
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
