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
                            <h3 class="page-title">Jobs</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Jobs</li>
                            </ul>
                        </div>
                        <div class="col-auto float-right ml-auto">
                            <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_job"><i
                                    class="fa fa-plus"></i> Add Job</a>
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
                                        <th>Job Title</th>
                                        <th>Department</th>
                                        <th>Start Date</th>
                                        <th>Expire Date</th>
                                        <th class="text-center">Job Type</th>
                                        <th class="text-center">Status</th>
                                        <th>Applicants</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="applicantTableBody">
                                    @foreach ($jobs as $job)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><a href="">{{ $job->job_title }}</a></td>
                                            <td>{{ $job->department }}</td>
                                            <td>{{ \Carbon\Carbon::parse($job->start_date)->format('d M Y') }}</td>

                                            <td>{{ \Carbon\Carbon::parse($job->expired_date)->format('d M Y') }}</td>
                                            <td class="text-center">{{ $job->job_type }}</td>
                                            <td class="text-center">{{ $job->status }}</td>
                                            <td>
                                                <a href="{{ route('job.applicants', $job->id) }}"
                                                    class="btn btn-sm btn-primary">View Applicants</a>
                                            </td>

                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false"><i
                                                            class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="#" class="dropdown-item" data-toggle="modal"
                                                            data-target="#edit_job"><i class="fa fa-pencil m-r-5"></i>
                                                            Edit</a>
                                                        <a href="#" class="dropdown-item" data-toggle="modal"
                                                            data-target="#delete_job"><i
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

            <!-- Add Job Modal -->
            <div id="add_job" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Job</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('jobs.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Job Title</label>
                                            <input class="form-control" type="text" name="job_title" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Department</label>
                                            <select class="select form-control" name="department" required>
                                                <option value="">- Select Department -</option>
                                                <option value="Web Development">Web Development</option>
                                                <option value="Application Development">Application Development
                                                </option>
                                                <option value="IT Management">IT Management</option>
                                                <option value="Accounts Management">Accounts Management</option>
                                                <option value="Support Management">Support Management</option>
                                                <option value="Marketing">Marketing</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Job Location</label>
                                            <input class="form-control" type="text" name="job_location" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>No of Vacancies</label>
                                            <input class="form-control" type="number" name="no_of_vacancies"
                                                min="1" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Experience</label>
                                            <input class="form-control" type="text" name="experience" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Age</label>
                                            <input class="form-control" type="number" name="age"
                                                min="18">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Salary From</label>
                                            <input type="number" class="form-control" name="salary_from"
                                                min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Salary To</label>
                                            <input type="number" class="form-control" name="salary_to"
                                                min="0" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Job Type</label>
                                            <select class="select form-control" name="job_type" required>
                                                <option value="Full Time">Full Time</option>
                                                <option value="Part Time">Part Time</option>
                                                <option value="Internship">Internship</option>
                                                <option value="Temporary">Temporary</option>
                                                <option value="Remote">Remote</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="select form-control" name="status" required>
                                                <option value="Open">Open</option>
                                                <option value="Closed">Closed</option>
                                                <option value="Cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input type="date" class="form-control" name="start_date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Expired Date</label>
                                            <input type="date" class="form-control" name="expired_date" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" name="description" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="submit-section">
                                    <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>

                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const startDateInput = document.querySelector("input[name='start_date']");
                                const expiredDateInput = document.querySelector("input[name='expired_date']");
                                const form = document.querySelector("form");

                                // Set minimum value for Start Date (today)
                                const today = new Date().toISOString().split("T")[0];
                                startDateInput.setAttribute("min", today);

                                // When the start date changes, update the minimum for expired date
                                startDateInput.addEventListener("change", function() {
                                    expiredDateInput.value = ""; // Reset expired date
                                    expiredDateInput.setAttribute("min", startDateInput.value);
                                });

                                form.addEventListener("submit", function(event) {
                                    const startDate = new Date(startDateInput.value);
                                    const expiredDate = new Date(expiredDateInput.value);

                                    if (!startDateInput.value || !expiredDateInput.value) {
                                        event.preventDefault();
                                        Swal.fire({
                                            icon: "error",
                                            title: "Missing Date",
                                            text: "Both Start Date and Expired Date are required!",
                                            confirmButtonColor: "#d33",
                                        });
                                        return;
                                    }

                                    if (expiredDate <= startDate) {
                                        event.preventDefault(); // Stop form submission
                                        Swal.fire({
                                            icon: "error",
                                            title: "Invalid Expired Date",
                                            text: "Expired Date must be after Start Date!",
                                            confirmButtonColor: "#d33",
                                        });
                                        return;
                                    }
                                });
                            });
                        </script>


                    </div>
                </div>
            </div>
            <!-- /Add Job Modal -->

            <!-- Edit Job Modal -->
            <div id="edit_job" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Job</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Job Title</label>
                                            <input class="form-control" type="text" value="Web Developer">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Department</label>
                                            <select class="select">
                                                <option>-</option>
                                                <option selected>Web Development</option>
                                                <option>Application Development</option>
                                                <option>IT Management</option>
                                                <option>Accounts Management</option>
                                                <option>Support Management</option>
                                                <option>Marketing</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Job Location</label>
                                            <input class="form-control" type="text" value="California">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>No of Vacancies</label>
                                            <input class="form-control" type="text" value="5">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Experience</label>
                                            <input class="form-control" type="text" value="2 Years">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Age</label>
                                            <input class="form-control" type="text" value="-">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Salary From</label>
                                            <input type="text" class="form-control" value="32k">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Salary To</label>
                                            <input type="text" class="form-control" value="38k">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Job Type</label>
                                            <select class="select">
                                                <option selected>Full Time</option>
                                                <option>Part Time</option>
                                                <option>Internship</option>
                                                <option>Temporary</option>
                                                <option>Remote</option>
                                                <option>Others</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="select">
                                                <option selected>Open</option>
                                                <option>Closed</option>
                                                <option>Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input type="text" class="form-control datetimepicker"
                                                value="3 Mar 2019">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Expired Date</label>
                                            <input type="text" class="form-control datetimepicker"
                                                value="31 May 2019">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Edit Job Modal -->

            <!-- Delete Job Modal -->
            <div class="modal custom-modal fade" id="delete_job" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Job</h3>
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
            <!-- /Delete Job Modal -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable(); // Activates DataTables
        });
    </script>
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

    <script src="assets/js/jquery-3.5.1.min.js"></script>
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
