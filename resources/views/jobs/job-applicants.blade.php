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
                                                            @else text-warning @endif">
                                                        </i> <span class="status-text">{{ $applicant->status }}</span>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
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
                                                            data-id="{{ $applicant->id }}" data-status="Interviewed">
                                                            <i class="fa fa-dot-circle-o text-warning"></i> Interviewed
                                                        </a>
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
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                        data-toggle="dropdown">
                                                        <i class="material-icons">more_vert</i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#">
                                                            <i class="fa fa-eye"></i> View Details
                                                        </a>
                                                        <a class="dropdown-item schedule-interview" href="#"
                                                            data-id="{{ $applicant->id }}">
                                                            <i class="fa fa-clock-o"></i> Schedule Interview
                                                        </a>
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
