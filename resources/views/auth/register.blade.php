<!DOCTYPE html>
<html lang="en">

@include('layout.head')

<body class="account-page">

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div class="account-content">
            <a href="{{ route('jobs.vacancies') }}" class="btn btn-primary apply-btn">Apply Job</a>
            <div class="container">

                <!-- Account Logo -->
                <div class="account-logo ">
                    <a href="{{ route('auth.login') }}">
                        <img src="assets/img/moverslogo.png" alt="movers" style=" width: 50%;">
                    </a>
                </div>
                <!-- /Account Logo -->

                <div class="account-box">
                    <div class="account-wrapper">
                        <h3 class="account-title">Register</h3>
                        <p class="account-subtitle">Wanna be part of our team?</p>

                        <!-- Account Form -->
                        <form action="{{ route('auth.register.post') }}" method="POST">
                            @csrf

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label>First Name</label>
                                    <input class="form-control @error('first_name') is-invalid @enderror" type="text"
                                        name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Last Name</label>
                                    <input class="form-control @error('last_name') is-invalid @enderror" type="text"
                                        name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Contact Number</label>
                                <input class="form-control @error('contact_number') is-invalid @enderror" type="text"
                                    name="contact_number" value="{{ old('contact_number') }}" required>
                                @error('contact_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label>Gender</label>
                                    <select class="form-control @error('gender') is-invalid @enderror" name="gender"
                                        required>
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                    @error('gender')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label>Birthdate</label>
                                    <input class="form-control @error('birthdate') is-invalid @enderror" type="date"
                                        name="birthdate" value="{{ old('birthdate') }}" required>
                                    @error('birthdate')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email"
                                    name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <div class="input-group">
                                    <input class="form-control @error('password') is-invalid @enderror" type="password"
                                        name="password" id="password" required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                            data-target="password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Repeat Password</label>
                                <div class="input-group">
                                    <input class="form-control @error('password_confirmation') is-invalid @enderror"
                                        type="password" name="password_confirmation" id="password_confirmation"
                                        required>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary toggle-password"
                                            data-target="password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms and Conditions Checkbox -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="termsAgreement"
                                        name="terms" required>
                                    <label class="custom-control-label" for="termsAgreement">
                                        I agree to the <a href="#" id="openTermsModal">terms and
                                            conditions</a>.
                                    </label>
                                </div>
                            </div>

                            <script>
                                document.querySelectorAll('.toggle-password').forEach(button => {
                                    button.addEventListener('click', function() {
                                        let input = document.getElementById(this.getAttribute('data-target'));
                                        let icon = this.querySelector('i');
                                        if (input.type === 'password') {
                                            input.type = 'text';
                                            icon.classList.replace('fa-eye', 'fa-eye-slash');
                                        } else {
                                            input.type = 'password';
                                            icon.classList.replace('fa-eye-slash', 'fa-eye');
                                        }
                                    });
                                });
                            </script>

                            <div class="form-group text-center">
                                <button class="btn btn-primary account-btn" type="submit">Register</button>
                            </div>

                            <div class="account-footer">
                                <p>Already have an account? <a href="{{ route('auth.login') }}">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
        </div>
    </div>



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


    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="assets/js/jquery-3.5.1.min.js"></script>

    <!-- Bootstrap Core JS -->
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>

</body>

</html>
