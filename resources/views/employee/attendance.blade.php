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
            <div class="content container-fluid">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Attendance</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Attendance</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <!-- Search Filter -->
                <form method="GET" action="{{ route('employee.attendance.search') }}">
                    <div class="row filter-row">
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group form-focus">
                                <input type="text" name="employee_name" class="form-control floating"
                                    value="{{ request('employee_name') }}">
                                <label class="focus-label">Employee Name</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group form-focus select-focus">
                                <select name="month" class="select floating">
                                    <option value="">-</option>
                                    @foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $index => $month)
                                        <option value="{{ $index + 1 }}"
                                            {{ request('month') == $index + 1 ? 'selected' : '' }}>
                                            {{ $month }}
                                        </option>
                                    @endforeach
                                </select>
                                <label class="focus-label">Select Month</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="form-group form-focus select-focus">
                                <select name="year" class="select floating">
                                    <option value="">-</option>
                                    @for ($y = now()->year; $y >= 2015; $y--)
                                        <option value="{{ $y }}"
                                            {{ request('year') == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                                <label class="focus-label">Select Year</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <button type="submit" class="btn btn-success btn-block">Search</button>
                        </div>
                    </div>
                </form>

                <!-- /Search Filter -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table table-nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        @for ($day = 1; $day <= 31; $day++)
                                            <th>{{ $day }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances as $attendance)
                                        <tr>
                                            <td>
                                                <h2 class="table-avatar">
                                                    <a class="avatar avatar-xs" href="#"><img alt=""
                                                            src="{{ asset('assets/img/default.jpg') }}"></a>
                                                    <a href="#">{{ $attendance['emp_name'] }}</a>
                                                </h2>
                                            </td>

                                            @for ($day = 1; $day <= 31; $day++)
                                                @php
                                                    $attendanceDay = \Carbon\Carbon::parse($attendance['log_date'])
                                                        ->day;
                                                @endphp
                                                <td>
                                                    @if ($attendanceDay == $day)
                                                        <a href="#attendance_info_{{ $attendance['maxtime_in_id'] }}"
                                                            data-toggle="modal">
                                                            <i class="fa fa-check text-success"></i>
                                                        </a>
                                                    @else
                                                        <i class="fa fa-close text-danger"></i>
                                                    @endif
                                                </td>
                                            @endfor
                                        </tr>

                                        {{-- Modal for each attendance --}}
                                        <div class="modal custom-modal fade"
                                            id="attendance_info_{{ $attendance['maxtime_in_id'] }}" role="dialog">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Attendance Info</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="card punch-status">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Timesheet <small
                                                                        class="text-muted">{{ $attendance['log_date'] }}</small>
                                                                </h5>
                                                                <div class="punch-det">
                                                                    <h6>Punch In at</h6>
                                                                    <p>{{ $attendance['log_date'] }}
                                                                        {{ $attendance['time_in'] }}</p>
                                                                </div>
                                                                <div class="punch-info">
                                                                    <div class="punch-hours">
                                                                        <span>{{ $attendance['worked_minutes'] > 0 ? round($attendance['worked_minutes'] / 60, 2) . ' hrs' : 'N/A' }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="punch-det">
                                                                    <h6>Punch Out at</h6>
                                                                    <p>{{ $attendance['log_date'] }}
                                                                        {{ $attendance['time_out'] }}</p>
                                                                </div>
                                                                <div class="statistics">
                                                                    <div class="row">
                                                                        <div class="col-md-6 col-6 text-center">
                                                                            <div class="stats-box">
                                                                                <p>Break</p>
                                                                                <h6>
                                                                                    @if (
                                                                                        !empty($attendance['lunch_out']) &&
                                                                                            !empty($attendance['lunch_in']) &&
                                                                                            $attendance['lunch_out'] != 'N/A' &&
                                                                                            $attendance['lunch_in'] != 'N/A')
                                                                                        @php
                                                                                            $start = \Carbon\Carbon::parse(
                                                                                                $attendance[
                                                                                                    'log_date'
                                                                                                ] .
                                                                                                    ' ' .
                                                                                                    $attendance[
                                                                                                        'lunch_out'
                                                                                                    ],
                                                                                            );
                                                                                            $end = \Carbon\Carbon::parse(
                                                                                                $attendance[
                                                                                                    'log_date'
                                                                                                ] .
                                                                                                    ' ' .
                                                                                                    $attendance[
                                                                                                        'lunch_in'
                                                                                                    ],
                                                                                            );
                                                                                            $breakHours =
                                                                                                $end->diffInMinutes(
                                                                                                    $start,
                                                                                                ) / 60;
                                                                                        @endphp
                                                                                        {{ round($breakHours, 2) }} hrs
                                                                                    @else
                                                                                        N/A
                                                                                    @endif

                                                                                </h6>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6 col-6 text-center">
                                                                            <div class="stats-box">
                                                                                <p>Overtime</p>
                                                                                <h6>{{ $attendance['ot_status'] }}</h6>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> <!-- End statistics -->
                                                            </div>
                                                        </div> <!-- End card -->
                                                    </div> <!-- End modal body -->
                                                </div> <!-- End modal content -->
                                            </div> <!-- End modal dialog -->
                                        </div> <!-- End modal -->
                                    @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->



        </div>
        <!-- Page Wrapper -->

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

    <!-- Datetimepicker JS -->
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>

</body>

</html>
