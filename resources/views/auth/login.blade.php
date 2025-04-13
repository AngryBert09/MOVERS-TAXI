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
                        <h3 class="account-title">Login</h3>
                        <p class="account-subtitle">Welcome to MOVERS!</p>

                        <!-- Account Form -->
                        <form action="{{ route('auth.login.post') }}" method="POST">
                            @csrf

                            <!-- Display success message -->
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Display authentication errors -->
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="form-group">
                                <label>Email Address</label>
                                <input class="form-control @error('email') is-invalid @enderror" type="email"
                                    name="email" required value="{{ old('email') }}">

                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Password</label>
                                    </div>
                                </div>

                                <div class="input-group">
                                    <input class="form-control @error('password') is-invalid @enderror" type="password"
                                        name="password" id="password" required>

                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            <i class="fas fa-eye"></i> <!-- FontAwesome eye icon -->
                                        </button>
                                    </div>
                                </div>

                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                            <!-- JavaScript for Toggle Password Visibility -->
                            <script>
                                document.getElementById('togglePassword').addEventListener('click', function() {
                                    let passwordField = document.getElementById('password');
                                    let icon = this.querySelector('i');

                                    if (passwordField.type === 'password') {
                                        passwordField.type = 'text';
                                        icon.classList.replace('fa-eye', 'fa-eye-slash'); // Change to "eye slash" icon
                                    } else {
                                        passwordField.type = 'password';
                                        icon.classList.replace('fa-eye-slash', 'fa-eye'); // Change back to "eye" icon
                                    }
                                });
                            </script>

                            <div class="form-group text-center">
                                <button class="btn btn-primary account-btn" type="submit">Login</button>
                            </div>

                            <!-- Google reCAPTCHA -->
                            <div class="form-group text-center">
                                <div class="g-recaptcha" data-sitekey="6LfueP0qAAAAAB6tNErAOoENm_K3f8n04yymRjb7"
                                    style="margin-left:60px;"></div>
                                @error('g-recaptcha-response')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="account-footer">
                                <p>Don't have an account yet? <a href="{{ route('auth.register') }}">Register</a>
                                </p>
                            </div>
                        </form>

                        <!-- Include Google reCAPTCHA script -->
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                        <!-- /Account Form -->

                    </div>
                </div>
            </div>
        </div>
    </div>
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
