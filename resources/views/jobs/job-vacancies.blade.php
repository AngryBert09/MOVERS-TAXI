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
                <li class="nav-item">
                    <div class="top-nav-search">
                        <a href="javascript:void(0);" class="responsive-search">
                            <i class="fa fa-search"></i>
                        </a>
                        <form action="search.html">
                            <input class="form-control" type="text" placeholder="Search here">
                            <button class="btn" type="submit"><i class="fa fa-search"></i></button>
                        </form>
                    </div>
                </li>
                <!-- /Search -->



                <li class="nav-item">
                    <a class="nav-link" href="{{ route('auth.login') }}">Login</a>
                </li>

            </ul>
            <!-- /Header Menu -->

            <!-- Mobile Menu -->
            <div class="dropdown mobile-user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i
                        class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="login.html">Login</a>
                    <a class="dropdown-item" href="register.html">Register</a>
                </div>
            </div>
            <!-- /Mobile Menu -->

        </div>
        <!-- /Header -->

        <!-- Page Wrapper -->
        <div class="page-wrapper job-wrapper">

            <!-- Page Content -->
            <div class="content container">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Jobs Vacancies</h3>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="row">
                    <div class="col-md-6">
                        @if ($jobs->isEmpty())
                            <div class="alert alert-info text-center">
                                No job postings available at the moment.
                            </div>
                        @else
                            @foreach ($jobs as $job)
                                <a class="job-list" href="{{ route('jobs.details', $job->id) }}">
                                    <div class="job-list-det">
                                        <div class="job-list-desc">
                                            <h3 class="job-list-title">{{ $job->job_title }}</h3>
                                            <h4 class="job-department">{{ $job->department }}</h4>
                                        </div>
                                        <div class="job-type-info">
                                            <span class="job-types">{{ $job->job_type }}</span>
                                        </div>
                                    </div>
                                    <div class="job-list-footer">
                                        <ul>
                                            <li><i class="fa fa-map-signs"></i> {{ $job->job_location }}</li>
                                            <li><i class="fa fa-money"></i> ${{ number_format($job->salary_from) }} -
                                                ${{ number_format($job->salary_to) }}</li>
                                            <li><i class="fa fa-clock-o"></i> {{ $job->created_at->diffForHumans() }}
                                            </li>
                                        </ul>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="assets/js/jquery-3.5.1.min.js"></script>

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
