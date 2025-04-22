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
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Facilities Evaluation Results</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Facilities</li>
                            </ul>
                        </div>
                        <div class="col-auto float-right ml-auto">

                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="search-container mb-4">
                            <div class="input-group" style="max-width: 300px; float: right;">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search..."
                                    aria-label="Search">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0 datatable">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Evaluation Date</th>
                                        <th>Facility</th>
                                        <th>Facility Rating (Avg)</th>
                                        <th>Status</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($profilings as $profile)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($profile->created_at)->format('M d, Y') }}</td>
                                            <td>{{ $profile->facility }}</td>
                                            <!-- Calculate Average -->
                                            <td>
                                                @php
                                                    $scores = collect([
                                                        $profile->question1_cleanliness,
                                                        $profile->question2_equipment_availability,
                                                        $profile->question3_technology_functionality,
                                                        $profile->question4_workspace_comfort,
                                                        $profile->question5_safety_preparedness,
                                                        $profile->question6_restroom_accessibility,
                                                        $profile->question7_internet_reliability,
                                                        $profile->question8_break_area_availability,
                                                        $profile->question9_storage_organization,
                                                        $profile->question10_general_appearance,
                                                    ]);
                                                    $avgScore = $scores->filter()->avg();
                                                @endphp
                                                {{ $avgScore ? number_format($avgScore, 2) : 'N/A' }}
                                            </td>

                                            <!-- Facility Status -->
                                            <td>
                                                @php
                                                    if ($avgScore >= 4.5) {
                                                        $facilityStatus = 'Excellent';
                                                        $badgeClass = 'success';
                                                    } elseif ($avgScore >= 3.5) {
                                                        $facilityStatus = 'Good';
                                                        $badgeClass = 'primary';
                                                    } elseif ($avgScore >= 2.5) {
                                                        $facilityStatus = 'Needs Improvement';
                                                        $badgeClass = 'warning';
                                                    } else {
                                                        $facilityStatus = 'Poor';
                                                        $badgeClass = 'danger';
                                                    }
                                                @endphp
                                                <span class="badge badge-{{ $badgeClass }}">
                                                    {{ $facilityStatus }}
                                                </span>
                                            </td>


                                            <!-- Actions -->
                                            <td class="text-right">
                                                <a href="#" class="btn btn-sm btn-info" data-toggle="modal"
                                                    data-target="#profilingDetails{{ $profile->id }}">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Modal for Profiling Details -->
                                        <div class="modal fade" id="profilingDetails{{ $profile->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="profilingDetailsLabel{{ $profile->id }}"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content shadow">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Workplace Profiling Evaluation:
                                                            {{ $profile->employee_id }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body px-4 py-3"
                                                        style="max-height: 80vh; overflow-y: auto;">
                                                        <ul class="list-unstyled">
                                                            @php
                                                                $criteria = [
                                                                    'question1_cleanliness' => 'Cleanliness',
                                                                    'question2_equipment_availability' =>
                                                                        'Equipment Availability',
                                                                    'question3_technology_functionality' =>
                                                                        'Technology Functionality',
                                                                    'question4_workspace_comfort' =>
                                                                        'Workspace Comfort',
                                                                    'question5_safety_preparedness' =>
                                                                        'Safety Preparedness',
                                                                    'question6_restroom_accessibility' =>
                                                                        'Restroom Accessibility',
                                                                    'question7_internet_reliability' =>
                                                                        'Internet Reliability',
                                                                    'question8_break_area_availability' =>
                                                                        'Break Area Availability',
                                                                    'question9_storage_organization' =>
                                                                        'Storage Organization',
                                                                    'question10_general_appearance' =>
                                                                        'General Appearance',
                                                                ];
                                                            @endphp

                                                            @foreach ($criteria as $key => $label)
                                                                <li class="mb-3">
                                                                    <strong>{{ $label }}:</strong>
                                                                    @php
                                                                        $rating = $profile->$key ?? 0;
                                                                    @endphp
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <i
                                                                            class="fa-star {{ $i <= $rating ? 'fas text-warning' : 'far text-muted' }}"></i>
                                                                    @endfor
                                                                    <span
                                                                        class="ml-2 text-muted">({{ $rating }})</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No profiling evaluation records
                                                found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>





                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->



            <!-- Edit Performance Appraisal Modal -->

            <!-- /Page Wrapper -->

        </div>
        <!-- /Main Wrapper -->


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
        <!-- jQuery -->
        <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>

        <!-- Bootstrap Core JS -->
        <script src="{{ asset('assets/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

        <!-- Slimscroll JS -->
        <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>

        <!-- Select2 JS -->
        <script src="{{ asset('assets/js/select2.min.js') }}"></script>

        <!-- Datatable JS -->
        <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

        <!-- Datetimepicker JS -->
        <script src="{{ asset('assets/js/moment.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap-datetimepicker.min.js') }}"></script>

        <!-- Custom JS -->
        <script src="{{ asset('assets/js/app.js') }}"></script>


</body>

</html>
