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

                            <!-- Display authentication errors -->
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
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

                            <!-- JS to toggle password visibility -->
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
