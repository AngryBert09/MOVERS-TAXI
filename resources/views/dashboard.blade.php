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
                            <h3 class="page-title">Job Dashboard</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item">Jobs</li>
                                <li class="breadcrumb-item active">Job Dashboard</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="row">
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-briefcase"></i></span>
                                <div class="dash-widget-info">
                                    <h3>{{ $hiredCount }}</h3>
                                    <span>Jobs</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-users"></i></span>
                                <div class="dash-widget-info">
                                    <h3>{{ $activeTrainingCount }}</h3>
                                    <span>Trainings</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                                <div class="dash-widget-info">
                                    <h3>{{ $hiredCount }}</h3>
                                    <span>Employees</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-clipboard"></i></span>
                                <div class="dash-widget-info">
                                    <h3>{{ $jobApplicationsCount }}</h3>
                                    <span>Applications</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 text-center d-flex">
                                <div class="card flex-fill">
                                    <script>
                                        var jobApplicationsByMonth = @json($jobApplicationsByMonth);
                                        var hiredByMonth = @json($hiredByMonth);
                                        var activeTrainingByMonth = @json($activeTrainingByMonth);
                                    </script>

                                    <div class="card-body">
                                        <h3 class="card-title">Overview</h3>
                                        <canvas id="lineChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex">
                                <div class="card flex-fill">
                                    <div class="card-body">
                                        <h3 class="card-title text-center">Latest Jobs</h3>
                                        <ul class="list-group">
                                            @foreach ($latestJobPosts as $job)
                                                <li class="list-group-item list-group-item-action">
                                                    {{ $job->job_title }}
                                                    <span class="float-right text-sm text-muted">
                                                        {{ \Carbon\Carbon::parse($job->created_at)->diffForHumans() }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-table">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Applicants List</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-nowrap custom-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Job Title</th>
                                                <th>Departments</th>
                                                <th>Start Date</th>
                                                <th>Expire Date</th>
                                                <th class="text-center">Job Types</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Applicants</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jobs as $job)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><a href="">{{ $job->job_title }}</a></td>
                                                    <td>{{ $job->department }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($job->start_date)->format('d M Y') }}
                                                    </td>

                                                    <td>{{ \Carbon\Carbon::parse($job->expired_date)->format('d M Y') }}
                                                    </td>
                                                    <td class="text-center">{{ $job->job_type }}</td>
                                                    <td class="text-center">{{ $job->status }}</td>
                                                    <td>
                                                        <a href="{{ route('job.applicants', $job->id) }}"
                                                            class="btn btn-sm btn-primary">View Applicants</a>
                                                    </td>


                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




            </div>
            <!-- /Page Content -->

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

    <!-- Chart JS -->
    <script src="assets/js/Chart.min.js"></script>
    <script src="assets/js/line-chart.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>

</body>

</html>
