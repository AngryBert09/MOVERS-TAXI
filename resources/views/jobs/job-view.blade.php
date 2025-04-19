<!DOCTYPE html>
<html lang="en">
@include('layout.head')

<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
        <div class="header">


            <!-- Header Title -->
            <div class="page-title-box float-left">
                <h3>Movers</h3>
            </div>
            <!-- /Header Title -->

            <!-- Header Menu -->
            <ul class="nav user-menu">

                <!-- Search -->
                {{-- <li class="nav-item">
                    <div class="top-nav-search">
                        <a href="javascript:void(0);" class="responsive-search">
                            <i class="fa fa-search"></i>
                        </a>
                        <form action="search.html">
                            <input class="form-control" type="text" placeholder="Search here">
                            <button class="btn" type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </li> --}}
                <!-- /Search -->



                <li class="nav-item">
                    <a class="nav-link" href="{{ route('auth.login') }}">Login</a>
                </li>

            </ul>
            <!-- /Header Menu -->

            <!-- Mobile Menu -->
            {{-- <div class="dropdown mobile-user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                        class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="login.html">Login</a>
                    <a class="dropdown-item" href="register.html">Register</a>
                </div>
            </div> --}}
            <!-- /Mobile Menu -->

        </div>
        <!-- /Header -->

        <!-- Page Wrapper -->
        <div class="page-wrapper job-wrapper">

            <!-- Page Content -->
            <div class="content container">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Job Details</h3>

                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="row">
                    <div class="col-md-8">
                        <div class="job-info job-widget">
                            <h3 class="job-title">{{ $job->job_title }}</h3>
                            <span class="job-dept">{{ $job->department }}</span>
                            <ul class="job-post-det">
                                <li><i class="fa fa-calendar"></i> Post Date: <span
                                        class="text-blue">{{ $job->created_at->format('M d, Y') }}</span></li>
                                <li><i class="fa fa-calendar"></i> Last Date: <span
                                        class="text-blue">{{ \Carbon\Carbon::parse($job->expired_date)->format('M d, Y') }}</span>
                                </li>

                                <li><i class="fa fa-user-o"></i> Applications: <span
                                        class="text-blue">{{ $job->applications_count }}</span></li>
                            </ul>
                        </div>
                        <div class="job-content job-widget">
                            <div class="job-desc-title">
                                <h4>Job Description</h4>
                            </div>
                            <div class="job-description">
                                <p>{{ $job->description }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="job-det-info job-widget">
                            <a class="btn job-btn" href="#" data-toggle="modal" data-target="#apply_job">Apply For
                                This Job</a>
                            <div class="info-list">
                                <span><i class="fa fa-bar-chart"></i></span>
                                <h5>Job Type</h5>
                                <p>{{ $job->job_type }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-money"></i></span>
                                <h5>Salary</h5>
                                <p>₱{{ number_format($job->salary_from) }} - ₱{{ number_format($job->salary_to) }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-suitcase"></i></span>
                                <h5>Experience</h5>
                                <p>{{ $job->experience }} Years</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-ticket"></i></span>
                                <h5>Vacancy</h5>
                                <p>{{ $job->no_of_vacancies }}</p>
                            </div>
                            <div class="info-list">
                                <span><i class="fa fa-map-signs"></i></span>
                                <h5>Location</h5>
                                <p>{{ $job->job_location }}</p>
                            </div>
                            <div class="info-list text-center">
                                <a class="app-ends" href="#">Application ends in
                                    {{ \Carbon\Carbon::parse($job->expired_date)->diffForHumans() }}</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /Page Content -->

            <!-- Apply Job Modal -->
            <!-- AUTHENTICATED MODAL -->
            @auth
                <div class="modal custom-modal fade" id="apply_job" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Your Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="applyJobForm" enctype="multipart/form-data" method="POST"
                                    action="{{ route('apply.job') }}">
                                    @csrf
                                    <input type="hidden" name="job_posting_id" value="{{ $job->id }}">

                                    <div class="form-group">
                                        <label>Name <span style="font-size: 0.75rem; color: #000000;">(e.g., Richmon D.
                                                Salarda)</span></label>
                                        <input class="form-control" type="text" name="name" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Email Address</label>
                                        <input class="form-control" type="email" name="email" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input class="form-control" type="text" name="phone" required pattern="\d*"
                                            title="Please enter a valid phone number">
                                    </div>

                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select class="form-control" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Birthdate</label>
                                        <input class="form-control" type="date" name="birthdate" required>
                                    </div>

                                    <div class="form-group">
                                        <label>Upload your CV</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="cv_upload" name="resume"
                                                required>
                                            <label class="custom-file-label" for="cv_upload">Choose file</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="termsAgreement"
                                                required>
                                            <label class="custom-control-label" for="termsAgreement">
                                                I agree to the <a href="#" id="openTermsModal">terms and
                                                    conditions</a>.
                                            </label>
                                        </div>
                                    </div>

                                    <div class="submit-section">
                                        <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endauth

            <!-- GUEST MODAL -->
            @guest
                <div class="modal custom-modal fade" id="apply_job" role="dialog">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Login Required</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning text-center">
                                    ⚠️ Please <a href="{{ route('auth.login') }}">log in</a> to apply for this job.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endguest


            <!-- Terms and Conditions Modal -->
            <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>By submitting your application, you acknowledge and agree to the following terms:</p>

                            <ul>
                                <li>You certify that all information provided in this application is accurate and
                                    complete to the best of your knowledge.</li>
                                <li>You consent to the collection, processing, and storage of your personal data in
                                    accordance with <strong>Republic Act No. 10173 or the Data Privacy Act of
                                        2012</strong>.</li>
                                <li>Your personal information will only be used for recruitment and employment purposes
                                    and will be processed securely.</li>
                                <li>We will not share your data with third parties without your explicit consent, except
                                    as required by law or for employment-related processes.</li>
                                <li>You have the right to access, correct, or request deletion of your personal
                                    information by contacting our Data Protection Officer.</li>
                            </ul>

                            <p>For more details on how we handle your personal data, please review our <a
                                    href="#">Privacy Policy</a>.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- JavaScript to Handle Modal Navigation and Form Submission -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

            <script>
                $(document).ready(function() {
                    // Open Terms Modal and Hide Apply Job Modal
                    $("#openTermsModal").on("click", function(event) {
                        event.preventDefault();
                        $("#apply_job").modal("hide"); // Hide Apply Job Modal
                        setTimeout(function() {
                            $("#termsModal").modal("show"); // Show Terms Modal
                        }, 500); // Delay to ensure smooth transition
                    });

                    // When Terms Modal Closes, Reopen Apply Job Modal
                    $("#termsModal").on("hidden.bs.modal", function() {
                        setTimeout(function() {
                            $("#apply_job").modal("show"); // Show Apply Job Modal
                        }, 500);
                    });

                    // Enable Submit Button When Checkbox is Checked
                    $("#termsAgreement").on("change", function() {
                        $("#submitBtn").prop("disabled", !this.checked);
                    });
                });
            </script>


            <!-- /Apply Job Modal -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#applyJobForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                let formData = new FormData(this); // Create form data object

                $.ajax({
                    url: "{{ route('apply.job') }}", // Laravel route
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
                    },
                    beforeSend: function() {
                        $('.submit-btn').prop('disabled', true).text('Submitting...');
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Application Submitted!',
                            text: 'Please save this code to track your application.\nApplication Code: ' +
                                response.application_code,
                            allowOutsideClick: false, // Prevent accidental modal closing
                            confirmButtonText: 'OK'
                        }).then(() => {
                            resetApplyForm(); // Reset form after success
                            $('#apply_job').modal('hide'); // Close modal smoothly
                            $('.modal-backdrop')
                                .remove(); // Remove modal backdrop to fix black screen
                        });
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON?.errors;
                        let errorMessage = "Something went wrong!";

                        if (errors) {
                            errorMessage = Object.values(errors).join("\n");
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Failed!',
                            text: errorMessage,
                        });

                        // Keep modal open for user to fix errors
                    },
                    complete: function() {
                        $('.submit-btn').prop('disabled', false).text('Submit');
                    }
                });
            });

            // Update file input label with selected filename
            $('#cv_upload').on('change', function(e) {
                let fileName = e.target.files[0]?.name || 'Choose file';
                $(this).next('.custom-file-label').text(fileName);
            });

            // Reset form fields properly
            function resetApplyForm() {
                $('#applyJobForm')[0].reset(); // Reset form
                $('.custom-file-label').text('Choose file'); // Reset file input label
                $('#termsAgreement').prop('checked', false); // Uncheck terms checkbox
                $('#submitBtn').prop('disabled', true); // Disable submit button
            }

            // Ensure form resets when modal closes
            $('#apply_job').on('hidden.bs.modal', function() {
                resetApplyForm();
                $('.modal-backdrop').remove(); // Ensure backdrop is removed
            });
        });
    </script>






</body>

</html>
