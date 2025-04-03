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
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Job Applicants</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Job Applicants</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="row">
                    <div class="col-md-12">
                        <!-- Search Bar -->

                        <!-- Stylish Search Bar -->
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
                                        <th>Apply Date</th>
                                        <th class="text-center">Status</th>
                                        <th>Resume</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="applicantTableBody">
                                    @foreach ($jobApplications as $index => $applicant)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $applicant->name }}</td>
                                            <td>{{ $applicant->email }}</td>
                                            <td>{{ $applicant->phone }}</td>
                                            <td>{{ \Carbon\Carbon::parse($applicant->apply_date)->format('d M Y') }}
                                            </td>
                                            <td class="text-center">
                                                <div class="dropdown action-label">
                                                    <a class="btn btn-white btn-sm btn-rounded dropdown-toggle"
                                                        href="#" data-toggle="dropdown">
                                                        <i
                                                            class="fa fa-dot-circle-o
                                                            @if ($applicant->status == 'Pending') text-info
                                                            @elseif ($applicant->status == 'Hired') text-success
                                                            @elseif ($applicant->status == 'Rejected') text-danger
                                                            @elseif ($applicant->status == 'Scheduled') text-primary
                                                            @elseif ($applicant->status == 'Interviewed') text-warning
                                                            @else text-warning @endif">
                                                        </i>
                                                        <span class="status-text">{{ $applicant->status }}</span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        @if ($applicant->status == 'Rejected')
                                                            <a class="dropdown-item disabled" href="#">
                                                                <i class="fa fa-dot-circle-o text-danger"></i>
                                                                Rejected(Locked)
                                                            </a>
                                                        @elseif ($applicant->status == 'Hired')
                                                            <a class="dropdown-item disabled" href="#">
                                                                <i class="fa fa-dot-circle-o text-success"></i>
                                                                Hired(Locked)
                                                            </a>
                                                        @elseif ($applicant->status == 'Interviewed')
                                                            <!-- Only Hired and Rejected will be shown if status is Interviewed -->
                                                            <a class="dropdown-item update-status" href="#"
                                                                data-id="{{ $applicant->id }}" data-status="Hired">
                                                                <i class="fa fa-dot-circle-o text-success"></i> Hired
                                                            </a>
                                                            <a class="dropdown-item update-status" href="#"
                                                                data-id="{{ $applicant->id }}" data-status="Rejected">
                                                                <i class="fa fa-dot-circle-o text-danger"></i> Rejected
                                                            </a>
                                                        @elseif ($applicant->status == 'Scheduled')
                                                            <a class="dropdown-item update-status" href="#"
                                                                data-id="{{ $applicant->id }}"
                                                                data-status="Interviewed">
                                                                <i class="fa fa-dot-circle-o text-warning"></i>
                                                                Interviewed
                                                            </a>
                                                        @else
                                                            <!-- Show Pending, Hired, Rejected, and Interviewed if not Interviewed or Rejected -->
                                                            <a class="dropdown-item update-status" href="#"
                                                                data-id="{{ $applicant->id }}" data-status="Pending">
                                                                <i class="fa fa-dot-circle-o text-info"></i> Pending
                                                            </a>
                                                            <a class="dropdown-item update-status" href="#"
                                                                data-id="{{ $applicant->id }}" data-status="Hired">
                                                                <i class="fa fa-dot-circle-o text-success"></i> Hired
                                                            </a>
                                                            <a class="dropdown-item update-status" href="#"
                                                                data-id="{{ $applicant->id }}" data-status="Rejected">
                                                                <i class="fa fa-dot-circle-o text-danger"></i> Rejected
                                                            </a>
                                                            <a class="dropdown-item update-status" href="#"
                                                                data-id="{{ $applicant->id }}"
                                                                data-status="Interviewed">
                                                                <i class="fa fa-dot-circle-o text-warning"></i>
                                                                Interviewed
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>



                                            <td>
                                                <a href="{{ asset('storage/' . $applicant->resume) }}"
                                                    class="btn btn-sm btn-primary" download>
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            </td>
                                            <td class="text-right">
                                                @if (!in_array($applicant->status, ['Hired', 'Rejected']))
                                                    <div class="dropdown dropdown-action">
                                                        <a href="#" class="action-icon dropdown-toggle"
                                                            data-toggle="dropdown">
                                                            <i class="material-icons">more_vert</i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            @if ($applicant->status === 'Pending')
                                                                <a class="dropdown-item schedule-interview"
                                                                    href="#" data-id="{{ $applicant->id }}"
                                                                    data-toggle="modal"
                                                                    data-target="#scheduleInterviewModal">
                                                                    <i class="fa fa-clock-o"></i> Send Interview Sched
                                                                </a>
                                                            @endif

                                                            @if (in_array($applicant->status, ['Pending', 'Interviewed']))
                                                                <a class="dropdown-item analyze-resume" href="#"
                                                                    data-id="{{ $applicant->id }}" data-toggle="modal"
                                                                    data-target="#analyzeResumeModal">
                                                                    <i class="fa fa-file-text-o"></i> AI Analyze Resume
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- jQuery Script for Search Functionality -->
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                <script>
                    $(document).on('click', '.update-status', function(e) {
                        e.preventDefault(); // Prevents jumping to "#"

                        let applicantId = $(this).data('id');
                        let newStatus = $(this).data('status');

                        $.ajax({
                            url: "{{ route('update.applicant.status') }}", // Ensure the correct route
                            type: "POST",
                            data: {
                                applicant_id: applicantId,
                                status: newStatus,
                                _token: "{{ csrf_token() }}"
                            },
                            beforeSend: function() {
                                // Show loader before sending request
                                Swal.fire({
                                    title: "Updating Status...",
                                    html: '<div class="spinner" style="margin-top: 15px;"></div>',
                                    allowOutsideClick: false,
                                    showConfirmButton: false,
                                    didOpen: () => {
                                        // Custom spinner animation
                                        Swal.showLoading();
                                    }
                                });
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: "Success",
                                        text: "Status updated successfully!",
                                        icon: "success",
                                        timer: 1500, // Auto-close after 1.5 seconds
                                        showConfirmButton: false
                                    }).then(() => {
                                        location.reload(); // Reload page after alert closes
                                    });
                                } else {
                                    Swal.fire("Error", response.message, "error");
                                }
                            },
                            error: function(xhr) {
                                Swal.fire("Error", "Something went wrong!", "error");
                            },
                            complete: function() {
                                // Hide loader after request is complete
                                Swal.close();
                            }
                        });

                    });
                </script>


            </div>
        </div>
        <!-- /Page Content -->

    </div>
    <!-- /Page Wrapper -->

    </div>


    <!-- Schedule Interview Modal -->
    <div class="modal fade" id="scheduleInterviewModal" tabindex="-1" role="dialog"
        aria-labelledby="scheduleInterviewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleInterviewModalLabel">Schedule Interview</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="scheduleInterviewForm">
                        <input type="hidden" name="applicant_id" id="applicant_id" value="">

                        <!-- Interview Date -->
                        <div class="form-group">
                            <label for="interview_date">Interview Date</label>
                            <input type="date" class="form-control" id="interview_date" name="interview_date"
                                required>
                        </div>

                        <!-- Interview Time -->
                        <div class="form-group">
                            <label for="interview_time">Interview Time</label>
                            <input type="time" class="form-control" id="interview_time" name="interview_time"
                                required>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveInterviewBtn">Schedule Interview</button>
                </div>
            </div>
        </div>
    </div>

    <!-- AI ANALYZER -->
    <div class="modal fade" id="analyzeResumeModal" tabindex="-1" aria-labelledby="analyzeResumeLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl"> <!-- Extra-large modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="analyzeResumeLabel">AI Resume Analysis</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;"> <!-- Scrollable content -->
                    <div id="aiAnalysisResult" style="word-wrap: break-word; white-space: normal;">
                        <p class="text-center">Analyzing resume... Please wait.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="copyAnalysis">Copy to Clipboard</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(".analyze-resume").on("click", function() {
                var applicantId = $(this).data("id");
                $("#aiAnalysisResult").html('<p class="text-center">Analyzing resume... Please wait.</p>');

                $.ajax({
                    url: "/analyze-resume",
                    type: "POST",
                    data: {
                        applicant_id: applicantId,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.analysis) {
                            let cleanText = response.analysis.replace(/[*-]/g,
                                ""); // Remove asterisks and dashes
                            cleanText = cleanText.replace(/\n\s*\n/g,
                                "\n\n"); // Remove excessive line breaks

                            $("#aiAnalysisResult").html(
                                "<pre style='white-space: pre-wrap; word-wrap: break-word;'>" +
                                cleanText +
                                "</pre>");
                        } else {
                            $("#aiAnalysisResult").html(
                                "<p class='text-danger'>No analysis found.</p>");
                        }
                    },

                    error: function(xhr) {
                        $("#aiAnalysisResult").html("<p class='text-danger'>Error: " + (xhr
                            .responseJSON?.error || "Unexpected error") + "</p>");
                    }
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.schedule-interview').on('click', function() {
                var applicantId = $(this).data('id');
                $('#applicant_id').val(applicantId);
            });

            $('#saveInterviewBtn').on('click', function() {
                var formData = $('#scheduleInterviewForm').serialize();

                $.ajax({
                    url: '{{ route('schedule.interview') }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    data: formData,
                    beforeSend: function() {
                        // Show loader before sending request
                        Swal.fire({
                            title: "Scheduling Interview...",
                            html: '<div class="spinner" style="margin-top: 15px;"></div>',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success',
                            text: response.success,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            $('#scheduleInterviewModal').modal('hide');
                            $('#scheduleInterviewForm')[0].reset(); // Clear form fields
                            location.reload(); // Reload the page to reflect changes
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Error scheduling interview. Please try again.',
                            icon: 'error'
                        });
                    },
                    complete: function() {
                        // Hide loader after request is complete
                        Swal.close();
                    }
                });
            });


        });
    </script>
    <!-- /Main Wrapper -->
    <script>
        $(document).ready(function() {
            // Search filter
            $("#searchApplicants").on("keyup", function() {
                let value = $(this).val().toLowerCase();
                $("#applicantsTable tr").filter(function() {
                    $(this).toggle(
                        $(this).text().toLowerCase().indexOf(value) > -1
                    );
                });
            });


        });
    </script>

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    <!-- jQuery CDN (if not already included) -->


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
