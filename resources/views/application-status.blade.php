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
                        <h3 class="account-title">Application Status</h3>
                        <p class="account-subtitle">Check your application status with MOVERS!</p>

                        <!-- Search Form -->
                        <form id="applicationSearchForm">
                            @csrf
                            <div class="form-group">
                                <label for="search_query">Search</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="search_query" id="search_query"
                                        placeholder="Enter Application Code or Email" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- Search Results -->
                        <div id="searchResults" class="mt-3" style="display: none;">
                            <div class="card shadow-lg border-0 rounded-lg">
                                <div class="card-body">
                                    <h5 class="card-title text-center font-weight-bold text-primary">
                                        <i class="fas fa-info-circle"></i> Application Details
                                    </h5>
                                    <hr>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><i class="fas fa-hashtag text-secondary"></i> <strong>Application
                                                    Code:</strong></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-dark" id="appCode"></p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><i class="fas fa-envelope text-secondary"></i> <strong>Email:</strong>
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="text-dark" id="appEmail"></p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><i class="fas fa-check-circle text-secondary"></i>
                                                <strong>Status:</strong></p>
                                        </div>
                                        <div class="col-md-6">
                                            <span id="appStatus" class="badge badge-pill"
                                                  :class="{
                                                      'badge-success': response.data.status === 'Approved',
                                                      'badge-warning': response.data.status === 'Pending',
                                                      'badge-danger': response.data.status === 'Rejected'
                                                  }">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Error Message -->
                        <div id="errorMessage" class="alert alert-danger mt-3" style="display: none;"></div>
                    </div>

                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        $(document).ready(function() {
                            $('#applicationSearchForm').on('submit', function(e) {
                                e.preventDefault();
                                let query = $('#search_query').val().trim();

                                if (query === '') {
                                    $('#errorMessage').text('Please enter an application code or email.').show();
                                    $('#searchResults').hide();
                                    return;
                                }

                                $.ajax({
                                    url: "{{ route('application.search') }}",
                                    type: "GET",
                                    data: {
                                        query: query
                                    },
                                    beforeSend: function() {
                                        $('#errorMessage').hide();
                                        $('#searchResults').hide();
                                    },
                                    success: function(response) {
                                        $('#appCode').text(response.data.application_code);
                                        $('#appEmail').text(response.data.email);
                                        $('#appStatus').text(response.data.status);
                                        $('#searchResults').show();
                                    },
                                    error: function(xhr) {
                                        $('#errorMessage').text(xhr.responseJSON.message).show();
                                        $('#searchResults').hide();
                                    }
                                });
                            });
                        });
                    </script>





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
