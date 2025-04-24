<!DOCTYPE html>
<html lang="en">

@include('layout.head')

<body class="account-page">

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <div class="account-content">

            <div class="container">



                            <div class="account-box">
                                <div class="account-wrapper">
                                    <h3 class="account-title">OTP</h3>
                                    <p class="account-subtitle">Verification your account</p>

                                    <!-- Expiry Banner -->
                                    <div class="alert alert-warning text-center">
                                        The OTP will expire within 3 minutes.
                                    </div>
                                    <!-- /Expiry Banner -->

                                    <!-- Account Form -->
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif

                                    <p class="text-center">Enter the 4-digit code sent to your email.</p>

                                    <form action="{{ route('2fa.check') }}" method="POST" id="otpForm">
                                        @csrf
                                        <div class="otp-wrap d-flex justify-content-center">
                                            <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
                                            <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
                                            <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
                                            <input type="text" name="otp[]" maxlength="1" class="otp-input" required>
                                        </div>

                                        <input type="hidden" name="two_factor_code" id="otpHidden">

                                        <div class="form-group text-center mt-3">
                                            <button class="btn btn-primary account-btn w-100" type="submit">Verify</button>
                                        </div>
                                        <div class="text-center mt-2">
                                            <p>Didn't receive a code? <a href="{{ route('2fa.resend') }}">Resend OTP</a></p>
                                        </div>
                                    </form>
                                    <!-- /Account Form -->

                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {
                                            const inputs = document.querySelectorAll(".otp-input");
                                            const hiddenInput = document.getElementById("otpHidden");

                                            inputs.forEach((input, index) => {
                                                input.addEventListener("input", function() {
                                                    if (this.value.length === 1 && index < inputs.length - 1) {
                                                        inputs[index + 1].focus();
                                                    }
                                                    updateHiddenField();
                                                });

                                                input.addEventListener("keydown", function(e) {
                                                    if (e.key === "Backspace" && this.value.length === 0 && index > 0) {
                                                        inputs[index - 1].focus();
                                                    }
                                                });
                                            });

                                            function updateHiddenField() {
                                                hiddenInput.value = Array.from(inputs).map(input => input.value).join('');
                                            }
                                        });
                                    </script>
                                </div>
                            </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

</body>

</html>
