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
                                                        <a href="javascript:void(0);" class="dropdown-item"
                                                            data-toggle="modal" data-target="#delete_job"
                                                            onclick="setDeleteUrl('{{ route('jobs.destroy', $job->id) }}')">
                                                            <i class="fa fa-trash-o m-r-5"></i> Delete
                                                        </a>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Job Modal -->
                                        <div id="edit_job" class="modal custom-modal fade" role="dialog">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Job</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form id="editJobForm"
                                                            action="{{ route('jobs.update', $job->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Job Title</label>
                                                                        <input class="form-control" type="text"
                                                                            name="job_title"
                                                                            value="{{ $job->job_title }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Department</label>
                                                                        <select class="form-control" name="department"
                                                                            required>
                                                                            <option value="">- Select Department -
                                                                            </option>
                                                                            @foreach ($departments as $department)
                                                                                <option
                                                                                    value="{{ $department->department_name }}"
                                                                                    {{ $job->department_id == $department->id ? 'selected' : '' }}>
                                                                                    {{ $department->department_name }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Job Location</label>
                                                                        <input class="form-control" type="text"
                                                                            name="job_location"
                                                                            value="{{ $job->job_location }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>No of Vacancies</label>
                                                                        <input class="form-control" type="number"
                                                                            name="no_of_vacancies"
                                                                            value="{{ $job->no_of_vacancies }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Experience</label>
                                                                        <input class="form-control" type="text"
                                                                            name="experience"
                                                                            value="{{ $job->experience }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Age</label>
                                                                        <input class="form-control" type="number"
                                                                            name="age"
                                                                            value="{{ $job->age }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Salary From</label>
                                                                        <input type="number" class="form-control"
                                                                            name="salary_from"
                                                                            value="{{ $job->salary_from }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Salary To</label>
                                                                        <input type="number" class="form-control"
                                                                            name="salary_to"
                                                                            value="{{ $job->salary_to }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Job Type</label>
                                                                        <select class="form-control" name="job_type">
                                                                            <option value="Full Time"
                                                                                {{ $job->job_type == 'Full Time' ? 'selected' : '' }}>
                                                                                Full Time
                                                                            </option>
                                                                            <option value="Part Time"
                                                                                {{ $job->job_type == 'Part Time' ? 'selected' : '' }}>
                                                                                Part Time
                                                                            </option>
                                                                            <option value="Internship"
                                                                                {{ $job->job_type == 'Internship' ? 'selected' : '' }}>
                                                                                Internship
                                                                            </option>
                                                                            <option value="Temporary"
                                                                                {{ $job->job_type == 'Temporary' ? 'selected' : '' }}>
                                                                                Temporary
                                                                            </option>
                                                                            <option value="Remote"
                                                                                {{ $job->job_type == 'Remote' ? 'selected' : '' }}>
                                                                                Remote</option>
                                                                            <option value="Others"
                                                                                {{ $job->job_type == 'Others' ? 'selected' : '' }}>
                                                                                Others</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Status</label>
                                                                        <select class="form-control" name="status">
                                                                            <option value="Open"
                                                                                {{ $job->status == 'Open' ? 'selected' : '' }}>
                                                                                Open</option>
                                                                            <option value="Closed"
                                                                                {{ $job->status == 'Closed' ? 'selected' : '' }}>
                                                                                Closed</option>
                                                                            <option value="Cancelled"
                                                                                {{ $job->status == 'Cancelled' ? 'selected' : '' }}>
                                                                                Cancelled
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Start Date</label>
                                                                        <input type="date" class="form-control"
                                                                            name="start_date"
                                                                            value="{{ $job->start_date }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Expired Date</label>
                                                                        <input type="date" class="form-control"
                                                                            name="expired_date"
                                                                            value="{{ $job->expired_date }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Description</label>
                                                                <textarea class="form-control" name="description">{{ $job->description }}</textarea>
                                                            </div>

                                                            <div class="submit-section">
                                                                <button type="submit"
                                                                    class="btn btn-primary submit-btn"
                                                                    onclick="confirmUpdate(event)">Save</button>
                                                            </div>
                                                        </form>
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
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->department_name }}">
                                                        {{ $department->department_name }}</option>
                                                @endforeach
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



            <!-- SweetAlert Confirmation -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


            <!-- /Edit Job Modal -->

            <!-- Delete Job Modal -->
            <div class="modal custom-modal fade" id="delete_job" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header text-center">
                                <h3>Delete Job</h3>
                                <p>Are you sure you want to delete this job?</p>
                            </div>
                            <form id="deleteJobForm" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-btn delete-action">
                                    <div class="row">
                                        <div class="col-6">
                                            <button type="submit"
                                                class="btn btn-danger btn-lg btn-block continue-btn">
                                                Delete
                                            </button>
                                        </div>
                                        <div class="col-6">
                                            <a href="javascript:void(0);" data-dismiss="modal"
                                                class="btn btn-secondary btn-lg btn-block cancel-btn">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function setDeleteUrl(deleteUrl) {
                    document.getElementById('deleteJobForm').setAttribute('action', deleteUrl);
                }
            </script>


            <!-- /Delete Job Modal -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->





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
