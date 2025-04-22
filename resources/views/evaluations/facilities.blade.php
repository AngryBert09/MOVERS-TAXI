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
                            <h3 class="page-title">Facility Evaluation</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Performance</li>
                            </ul>
                        </div>
                        <div class="col-auto float-right ml-auto">

                        </div>
                    </div>
                </div>
                <!-- /Page Header -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- Banner at the top -->
                        <div class="alert alert-info">
                            <strong>Evaluate HR Facility and equipments</strong>
                        </div>

                        <h4>Questionnaire</h4>

                        @php
                            // User is an employee AND already submitted
                            $isEmployee = Auth::id() == $employeeId && $hasSubmitted;
                        @endphp


                        <form action="{{ route('facility-evaluation.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="question1">1. Cleanliness of the work environment</label>
                                <select class="form-control" id="question1" name="question1"
                                    {{ $isEmployee ? 'disabled' : '' }} required>
                                    <option value="" selected disabled>Select Rating</option>
                                    <option value="1">1 - Very Poor</option>
                                    <option value="2">2 - Poor</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="question2">2. Availability of necessary equipment and tools</label>
                                <select class="form-control" id="question2" name="question2"
                                    {{ $isEmployee ? 'disabled' : '' }} required>
                                    <option value="" selected disabled>Select Rating</option>
                                    <option value="1">1 - Very Poor</option>
                                    <option value="2">2 - Poor</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="question3">3. Functionality of machines and technology</label>
                                <select class="form-control" id="question3" name="question3"
                                    {{ $isEmployee ? 'disabled' : '' }} required>
                                    <option value="" selected disabled>Select Rating</option>
                                    <option value="1">1 - Very Poor</option>
                                    <option value="2">2 - Poor</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="question4">4. Workspace comfort (lighting, ventilation, noise)</label>
                                <select class="form-control" id="question4" name="question4"
                                    {{ $isEmployee ? 'disabled' : '' }} required>
                                    <option value="" selected disabled>Select Rating</option>
                                    <option value="1">1 - Very Poor</option>
                                    <option value="2">2 - Poor</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="question5">5. Safety and emergency preparedness</label>
                                <select class="form-control" id="question5" name="question5"
                                    {{ $isEmployee ? 'disabled' : '' }} required>
                                    <option value="" selected disabled>Select Rating</option>
                                    <option value="1">1 - Very Poor</option>
                                    <option value="2">2 - Poor</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="question6">6. Accessibility of restrooms and hygiene supplies</label>
                                <select class="form-control" id="question6" name="question6"
                                    {{ $isEmployee ? 'disabled' : '' }} required>
                                    <option value="" selected disabled>Select Rating</option>
                                    <option value="1">1 - Very Poor</option>
                                    <option value="2">2 - Poor</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="question7">7. Internet and connectivity reliability</label>
                                <select class="form-control" id="question7" name="question7"
                                    {{ $isEmployee ? 'disabled' : '' }} required>
                                    <option value="" selected disabled>Select Rating</option>
                                    <option value="1">1 - Very Poor</option>
                                    <option value="2">2 - Poor</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="question8">8. Availability of break/lunch areas</label>
                                <select class="form-control" id="question8" name="question8"
                                    {{ $isEmployee ? 'disabled' : '' }} required>
                                    <option value="" selected disabled>Select Rating</option>
                                    <option value="1">1 - Very Poor</option>
                                    <option value="2">2 - Poor</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="question9">9. Storage and organization of work materials</label>
                                <select class="form-control" id="question9" name="question9"
                                    {{ $isEmployee ? 'disabled' : '' }} required>
                                    <option value="" selected disabled>Select Rating</option>
                                    <option value="1">1 - Very Poor</option>
                                    <option value="2">2 - Poor</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="question10">10. General appearance and upkeep of facilities</label>
                                <select class="form-control" id="question10" name="question10"
                                    {{ $isEmployee ? 'disabled' : '' }} required>
                                    <option value="" selected disabled>Select Rating</option>
                                    <option value="1">1 - Very Poor</option>
                                    <option value="2">2 - Poor</option>
                                    <option value="3">3 - Average</option>
                                    <option value="4">4 - Good</option>
                                    <option value="5">5 - Excellent</option>
                                </select>
                            </div>

                            @if (!$isEmployee)
                                <!-- Only show submit button if user is not the employee -->
                                <button type="submit" class="btn btn-primary">Submit</button>
                            @else
                                <div class="alert alert-info">You have already submitted your evaluation.</div>
                            @endif
                        </form>
                    </div>



                </div>
            </div>

            <!-- /Page Content -->




            <!-- /Page Wrapper -->

        </div>
        <!-- /Main Wrapper -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function() {
                $("#searchInput").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#applicantTableBody tr").filter(function() {
                        $(this).toggle(
                            $(this).text().toLowerCase().indexOf(value) > -1
                        );
                    });
                });
            });
        </script>

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
