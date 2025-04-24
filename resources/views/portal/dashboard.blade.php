<!DOCTYPE html>
<html lang="en">

@include('layout.head')

<body>
    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
        @include('portal.layout.navbar')
        <!-- /Header -->

        <!-- Sidebar -->
        @include('portal.layout.left-sidebar')
        <!-- /Sidebar -->

        <!-- Page Wrapper -->
        <div class="page-wrapper">

            <!-- Page Content -->
            <div class="content container-fluid">

                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Applicant Dashboard</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item">Applicant</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <!-- Content Starts -->
                {{-- <div class="card">
                    <div class="card-body">
                        <!-- <h4 class="card-title">Solid justified</h4> -->
                        <ul class="nav nav-tabs nav-tabs-solid nav-justified">
                            <li class="nav-item"><a class="nav-link active" href="user-dashboard.html">Dashboard</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="user-all-jobs.html">All </a></li>
                            <li class="nav-item"><a class="nav-link" href="saved-jobs.html">Saved</a></li>
                            <li class="nav-item"><a class="nav-link" href="applied-jobs.html">Applied</a></li>
                            <li class="nav-item"><a class="nav-link" href="interviewing.html">Interviewing</a></li>
                            <li class="nav-item"><a class="nav-link" href="offered-jobs.html">Offered</a></li>
                            <li class="nav-item"><a class="nav-link" href="visited-jobs.html">Visitied </a></li>
                            <li class="nav-item"><a class="nav-link" href="archived-jobs.html">Archived </a></li>
                        </ul>
                    </div>
                </div> --}}

                {{-- <div class="row">
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-file-text-o"></i></span>
                                <div class="dash-widget-info">
                                    <h3>110</h3>
                                    <span>Offered</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-clipboard"></i></span>
                                <div class="dash-widget-info">
                                    <h3>40</h3>
                                    <span>Applied</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-retweet"></i></span>
                                <div class="dash-widget-info">
                                    <h3>374</h3>
                                    <span>Visited</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-floppy-o"></i></span>
                                <div class="dash-widget-info">
                                    <h3>220</h3>
                                    <span>Saved</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6 text-center d-flex">
                                <div class="card flex-fill">
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
                                            <li class="list-group-item list-group-item-action">UI Developer <span
                                                    class="float-right text-sm text-muted">1 Hours ago</span></li>
                                            <li class="list-group-item list-group-item-action">Android Developer <span
                                                    class="float-right text-sm text-muted">1 Days ago</span></li>
                                            <li class="list-group-item list-group-item-action">IOS Developer<span
                                                    class="float-right text-sm text-muted">2 Days ago</span></li>
                                            <li class="list-group-item list-group-item-action">PHP Developer<span
                                                    class="float-right text-sm text-muted">3 Days ago</span></li>
                                            <li class="list-group-item list-group-item-action">UI Developer<span
                                                    class="float-right text-sm text-muted">3 Days ago</span></li>
                                            <li class="list-group-item list-group-item-action">PHP Developer<span
                                                    class="float-right text-sm text-muted">4 Days ago</span></li>
                                            <li class="list-group-item list-group-item-action">UI Developer<span
                                                    class="float-right text-sm text-muted">4 Days ago</span></li>
                                            <li class="list-group-item list-group-item-action">Android Developer<span
                                                    class="float-right text-sm text-muted">6 Days ago</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}


                {{-- <div class="row">
                    <div class="col-md-12">
                        <div class="card card-table">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Offered Jobs</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-nowrap custom-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Job Title</th>
                                                <th>Department</th>
                                                <th class="text-center">Job Type</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td><a href="job-details.html">Web Developer</a></td>
                                                <td>Development</td>
                                                <td class="text-center">
                                                    <div class="action-label">
                                                        <a class="btn btn-white btn-sm btn-rounded" href="#"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-dot-circle-o text-danger"></i> Full Time
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a href="#"
                                                        class="btn btn-sm btn-info download-offer"><span><i
                                                                class="fa fa-download mr-1"></i> Download
                                                            Offer</span></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td><a href="job-details.html">Web Designer</a></td>
                                                <td>Designing</td>
                                                <td class="text-center">
                                                    <div class="action-label">
                                                        <a class="btn btn-white btn-sm btn-rounded" href="#"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-dot-circle-o text-success"></i> Part Time
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a href="#"
                                                        class="btn btn-sm btn-info download-offer"><span><i
                                                                class="fa fa-download mr-1"></i> Download
                                                            Offer</span></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td><a href="job-details.html">Android Developer</a></td>
                                                <td>Android</td>
                                                <td class="text-center">
                                                    <div class="action-label">
                                                        <a class="btn btn-white btn-sm btn-rounded" href="#"
                                                            data-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa fa-dot-circle-o text-danger"></i> Internship
                                                        </a>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <a href="#"
                                                        class="btn btn-sm btn-info download-offer"><span><i
                                                                class="fa fa-download mr-1"></i> Download
                                                            Offer</span></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}


                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-table">
                            <div class="card-header">
                                <h3 class="card-title mb-0">
                                    <i class="fa fa-file-text-o mr-2"></i> My Application
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-nowrap custom-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Apply Date</th>
                                                <th class="text-center">Status</th>
                                                <th>Position</th>
                                                <th>Resume</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($applications->isEmpty())
                                                <tr>
                                                    <td colspan="9" class="text-center">
                                                        <p class="text-muted mt-3">No applications found.</p>
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($applications as $index => $application)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $application->name }}</td>
                                                        <td>{{ $application->email }}</td>
                                                        <td>{{ $application->phone }}</td>
                                                        <td>{{ $application->apply_date }}</td>
                                                        <td class="text-center">
                                                            <div class="action-label">
                                                                <a class="btn btn-white btn-sm btn-rounded"
                                                                    href="#">
                                                                    <i
                                                                        class="fa fa-dot-circle-o text-{{ match ($application->status) {
                                                                            'Open' => 'success',
                                                                            'Requirements' => 'warning',
                                                                            'Failed' => 'danger',
                                                                            'Not Qualified', 'Qualified' => 'secondary',
                                                                            'Scheduled' => 'dark',
                                                                            'Hired' => 'success',
                                                                            'Rejected' => 'danger',
                                                                            'Final' => 'dark',
                                                                            'Interviewed' => 'warning',
                                                                            default => 'info',
                                                                        } }}"></i>
                                                                    {{ match ($application->status) {
                                                                        'Scheduled' => 'Final',
                                                                        'Not Qualified', 'Qualified' => 'Under Review',
                                                                        'Hired' => 'Hired',
                                                                        'Rejected' => 'Rejected',
                                                                        default => $application->status,
                                                                    } }}
                                                                </a>
                                                            </div>
                                                        </td>
                                                        <td>{{ $application->jobPosting->job_title ?? 'N/A' }}</td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-sm btn-primary viewFileBtn"
                                                                data-file="{{ asset('storage/' . $application->resume) }}"
                                                                data-toggle="modal" data-target="#fileViewModal">
                                                                <i class="fa fa-eye"></i> View
                                                            </button>
                                                            <a href="{{ $application->resume_url }}" target="_blank"
                                                                class="btn btn-sm btn-info">
                                                                <i class="fa fa-download"></i> Download
                                                            </a>
                                                        </td>
                                                        <td class="text-right">
                                                            <div class="dropdown dropdown-action">
                                                                <a href="#" class="action-icon dropdown-toggle"
                                                                    data-toggle="dropdown" aria-expanded="false">
                                                                    <i class="material-icons">more_vert</i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    @if (in_array($application->status, ['Initial', 'Final', 'Pending', 'Qualified', 'Not Qualified']))
                                                                        <a class="dropdown-item" href="#"
                                                                            data-toggle="modal"
                                                                            data-target="#delete_application_{{ $application->id }}">
                                                                            <i class="fa fa-times m-r-5"></i> Withdraw
                                                                            Application
                                                                        </a>
                                                                    @endif
                                                                    @if ($application->status == 'Requirements')
                                                                        <a class="dropdown-item" href="#"
                                                                            data-toggle="modal"
                                                                            data-target="#requirements_modal_{{ $application->id }}">
                                                                            <i class="fa fa-file m-r-5"></i> Submit
                                                                            Requirements
                                                                        </a>
                                                                    @endif
                                                                    @if ($application->status == 'Failed')
                                                                        <a class="dropdown-item" href="#"
                                                                            data-toggle="modal"
                                                                            data-target="#reason_modal_{{ $application->id }}">
                                                                            <i class="fa fa-info-circle m-r-5"></i> View
                                                                            Reason
                                                                        </a>
                                                                    @endif
                                                                    @if (in_array($application->status, ['Initial', 'Final', 'Scheduled']))
                                                                        <a class="dropdown-item" href="#"
                                                                            data-toggle="modal"
                                                                            data-target="#view_schedule_modal_{{ $application->id }}">
                                                                            <i class="fa fa-calendar m-r-5"></i> View
                                                                            Schedule
                                                                        </a>
                                                                    @endif
                                                                    @if ($application->status == 'Hired')
                                                                        <a class="dropdown-item" href="#"
                                                                            data-toggle="modal"
                                                                            data-target="#hired_modal_{{ $application->id }}">
                                                                            <i class="fa fa-check-circle m-r-5"></i>
                                                                            View
                                                                            Congratulations
                                                                        </a>
                                                                    @endif
                                                                    @if ($application->status == 'Rejected')
                                                                        <a class="dropdown-item" href="#"
                                                                            data-toggle="modal"
                                                                            data-target="#rejected_modal_{{ $application->id }}">
                                                                            <i class="fa fa-check-circle m-r-5"></i>
                                                                            View Message
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>

                                                        @if (!in_array($application->status, ['Failed', 'Onboarding']))
                                                            <!-- Withdraw Application Modal -->
                                                            <div class="modal fade"
                                                                id="delete_application_{{ $application->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="withdrawModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered"
                                                                    role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="withdrawModalLabel">
                                                                                Withdraw Application</h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal" aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body"
                                                                            style="white-space: normal; word-wrap: break-word;">
                                                                            <p>We understand that circumstances change,
                                                                                and
                                                                                you
                                                                                may need to withdraw your application.
                                                                                Are
                                                                                you
                                                                                sure you want to proceed?</p>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">Cancel</button>
                                                                            <form
                                                                                action="{{ route('application.withdraw', $application->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <button type="submit"
                                                                                    class="btn btn-danger">Withdraw</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- /Withdraw Application Modal -->
                                                        @endif

                                                        <!-- For Requirements -->
                                                        @if ($application->status == 'Requirements')
                                                            <!-- Requirements Modal -->
                                                            <div class="modal fade"
                                                                id="requirements_modal_{{ $application->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="requirementsModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content text-wrap"
                                                                        style="word-break: break-word;">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="requirementsModalLabel">Submit
                                                                                Requirements</h5>
                                                                            <button type="button" class="close"
                                                                                data-dismiss="modal"
                                                                                aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>

                                                                        <div class="modal-body">
                                                                            @if ($application->comply_date)
                                                                                <div
                                                                                    class="alert alert-info text-center">
                                                                                    Please submit your requirements on
                                                                                    or before
                                                                                    <strong>{{ \Carbon\Carbon::parse($application->comply_date)->format('F d, Y') }}</strong>.
                                                                                    <span
                                                                                        class="text-danger font-weight-bold">Late
                                                                                        submissions may lead to
                                                                                        application
                                                                                        disqualification or delay in
                                                                                        onboarding.</span>
                                                                                </div>
                                                                            @endif


                                                                            <div
                                                                                class="alert alert-warning text-center">
                                                                                <strong>Note:</strong> Upload clear and
                                                                                readable documents.
                                                                                Blurry or incomplete files may cause
                                                                                processing delays.
                                                                                <br>

                                                                            </div>

                                                                            <form
                                                                                action="{{ route('applications.requirements.upload', $application->id) }}"
                                                                                method="POST"
                                                                                enctype="multipart/form-data">
                                                                                @csrf

                                                                                <div class="form-group">
                                                                                    <label for="sss">SSS</label>
                                                                                    <input type="file"
                                                                                        name="sss" id="sss"
                                                                                        class="form-control" required>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="pagibig">Pag-IBIG</label>
                                                                                    <input type="file"
                                                                                        name="pagibig" id="pagibig"
                                                                                        class="form-control" required>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="barangay_clearance">Barangay
                                                                                        Clearance</label>
                                                                                    <input type="file"
                                                                                        name="barangay_clearance"
                                                                                        id="barangay_clearance"
                                                                                        class="form-control" required>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label
                                                                                        for="philhealth">PhilHealth</label>
                                                                                    <input type="file"
                                                                                        name="philhealth"
                                                                                        id="philhealth"
                                                                                        class="form-control" required>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label for="tin">TIN</label>
                                                                                    <input type="file"
                                                                                        name="tin" id="tin"
                                                                                        class="form-control" required>
                                                                                </div>

                                                                                <button type="submit"
                                                                                    class="btn btn-primary w-100 mt-2">Submit</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif


                                                        <!-- Failed Modal -->
                                                        @if ($application->status == 'Failed')
                                                            <!-- Reason Modal -->
                                                            <div class="modal fade"
                                                                id="reason_modal_{{ $application->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="reasonModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered"
                                                                    role="document">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-danger text-white">
                                                                            <h5 class="modal-title"
                                                                                id="reasonModalLabel">
                                                                                <i
                                                                                    class="fa fa-exclamation-circle mr-2"></i>
                                                                                Reason for Failure
                                                                            </h5>
                                                                            <button type="button"
                                                                                class="close text-white"
                                                                                data-dismiss="modal"
                                                                                aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="text-center">
                                                                                <p class="mb-0 ">
                                                                                    Reason: {{ $application->note }}
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary"
                                                                                data-dismiss="modal">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- /Reason Modal -->
                                                        @endif

                                                        <!-- View Schedule Modal -->
                                                        <div class="modal fade"
                                                            id="view_schedule_modal_{{ $application->id }}"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="viewScheduleModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered"
                                                                role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="viewScheduleModalLabel">Interview
                                                                            Schedule</h5>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p><strong>Date:</strong>
                                                                            {{ $application->comply_date ? \Carbon\Carbon::parse($application->comply_date)->format('Y-m-d') : 'N/A' }}
                                                                        </p>
                                                                        <p><strong>Time:</strong>
                                                                            {{ $application->comply_date ? \Carbon\Carbon::parse($application->comply_date)->format('h:i A') : 'N/A' }}
                                                                        </p>
                                                                        <hr>
                                                                        <p><strong>Interview Preparation
                                                                                Guidelines:</strong></p>
                                                                        <ul
                                                                            style="padding-left: 1.2rem; list-style-type: disc;">
                                                                            <li>Review the job description and your
                                                                                submitted application.</li>
                                                                            <li>Ensure a reliable internet connection if
                                                                                the interview is online.</li>
                                                                            <li>Have a copy of your resume and any
                                                                                relevant documents ready.</li>
                                                                            <li>Dress professionally to make a good
                                                                                impression.</li>
                                                                            <li>Be ready at least 10 minutes before the
                                                                                scheduled time.</li>
                                                                        </ul>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button"
                                                                            class="btn btn-secondary"
                                                                            data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Hired Modal -->
                                                        @if ($application->status == 'Hired')
                                                            <div class="modal fade"
                                                                id="hired_modal_{{ $application->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="hiredModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg"
                                                                    role="document">
                                                                    <div class="modal-content text-wrap"
                                                                        style="word-break: break-word;">
                                                                        <div
                                                                            class="modal-header bg-success text-white">
                                                                            <h5 class="modal-title"
                                                                                id="hiredModalLabel">
                                                                                <i class="fa fa-trophy mr-2"></i>
                                                                                You're
                                                                                Hired!
                                                                            </h5>
                                                                            <button type="button"
                                                                                class="close text-white"
                                                                                data-dismiss="modal"
                                                                                aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>

                                                                        <div class="modal-body px-4 py-4">
                                                                            <div class="text-break"
                                                                                style="overflow-wrap: anywhere;">
                                                                                <p class="mb-3 lead">
                                                                                    Dear
                                                                                    <strong>{{ $application->name }}</strong>,
                                                                                </p>
                                                                                <p class="mb-3"
                                                                                    style="line-height: 1.6;">
                                                                                    Congratulations! We are excited to
                                                                                    inform you that you have been
                                                                                    <strong
                                                                                        class="h5 text-success">HIRED</strong>
                                                                                    for the role of
                                                                                    <strong>{{ $application->jobPosting->job_title ?? 'N/A' }}</strong>.
                                                                                </p>
                                                                                <p class="mb-3"
                                                                                    style="line-height: 1.6;">
                                                                                    Your skills, experience, and
                                                                                    enthusiasm stood out, and we are
                                                                                    thrilled to welcome you aboard.
                                                                                    The next steps of the onboarding
                                                                                    process will be shared with you
                                                                                    shortly.
                                                                                </p>
                                                                                <p class="mb-0 h5 text-success">
                                                                                    Welcome to the team — we look
                                                                                    forward to achieving great things
                                                                                    together!
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer px-4  pt-0">
                                                                            <button type="button"
                                                                                class="btn btn-success btn-lg w-100 mt-2"
                                                                                data-dismiss="modal">
                                                                                Close
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <!-- /Hired Modal -->

                                                        <!-- Rejected Modal -->
                                                        @if ($application->status == 'Rejected')
                                                            <div class="modal fade"
                                                                id="rejected_modal_{{ $application->id }}"
                                                                tabindex="-1" role="dialog"
                                                                aria-labelledby="rejectedModalLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg"
                                                                    role="document">
                                                                    <div class="modal-content text-wrap"
                                                                        style="word-break: break-word;">
                                                                        <div class="modal-header bg-danger text-white">
                                                                            <h5 class="modal-title"
                                                                                id="rejectedModalLabel">
                                                                                <i class="fa fa-times-circle mr-2"></i>
                                                                                Application Status Update
                                                                            </h5>
                                                                            <button type="button"
                                                                                class="close text-white"
                                                                                data-dismiss="modal"
                                                                                aria-label="Close">
                                                                                <span aria-hidden="true">&times;</span>
                                                                            </button>
                                                                        </div>

                                                                        <div class="modal-body p-4">
                                                                            <div class="text-break"
                                                                                style="overflow-wrap: anywhere;">
                                                                                <p class="mb-3 lead">
                                                                                    Dear
                                                                                    <strong>{{ $application->name }}</strong>,
                                                                                </p>
                                                                                <p class="mb-3"
                                                                                    style="line-height: 1.6;">
                                                                                    After careful consideration, we
                                                                                    regret to inform you that your
                                                                                    application for
                                                                                    <strong>{{ $application->jobPosting->job_title ?? 'the position' }}</strong>
                                                                                    has not been successful at this
                                                                                    time.
                                                                                </p>
                                                                                <p class="mb-3"
                                                                                    style="line-height: 1.6;">
                                                                                    We appreciate the time and effort
                                                                                    you put into your application and
                                                                                    encourage you to apply
                                                                                    for future opportunities that match
                                                                                    your skills and experience.
                                                                                </p>
                                                                                <p class="mb-0 text-muted">
                                                                                    <small>Thank you for your interest
                                                                                        in our organization.</small>
                                                                                </p>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer px-4 pt-0 mt-2">
                                                                            <button type="button"
                                                                                class="btn btn-danger btn-lg w-100 mt-2"
                                                                                data-dismiss="modal">
                                                                                Close
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- File View Modal -->
                <div class="modal fade" id="fileViewModal" tabindex="-1" role="dialog"
                    aria-labelledby="fileViewModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">File Preview</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body text-center">
                                <div id="filePreviewContainer" style="height: 80vh;">
                                    <!-- File preview will be injected here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const viewButtons = document.querySelectorAll('.viewFileBtn');
                        const previewContainer = document.getElementById('filePreviewContainer');

                        viewButtons.forEach(button => {
                            button.addEventListener('click', function() {
                                const fileUrl = this.getAttribute('data-file');
                                const fileExt = fileUrl.split('.').pop().toLowerCase();

                                let content = '';

                                if (['pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp'].includes(fileExt)) {
                                    content =
                                        `<iframe src="${fileUrl}" style="width:100%; height:100%;" frameborder="0"></iframe>`;
                                } else if (['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'].includes(fileExt)) {
                                    content =
                                        `<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=${encodeURIComponent(fileUrl)}" style="width:100%; height:100%;" frameborder="0"></iframe>`;
                                } else {
                                    content =
                                        `<p class="text-danger">File preview not supported for this file type. Please download to view.</p>`;
                                }

                                previewContainer.innerHTML = content;
                            });
                        });
                    });
                </script>


                <!-- /Content End -->

            </div>
            <!-- /Page Content -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Try Again'
                });
            @endif
        });
    </script>
    <!-- Bootstrap Core JS -->
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

    <!-- Slimscroll JS -->
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>

    <!-- Chart JS -->
    <script src="{{ asset('assets/js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/line-chart.js') }}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

</body>

</html>
