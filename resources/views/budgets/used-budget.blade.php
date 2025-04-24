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
                            <h3 class="page-title">Used Budget</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Accounts</li>
                            </ul>
                        </div>
                        <div class="col-auto float-right ml-auto">
                            <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_categories"><i
                                    class="fa fa-plus"></i>Add</a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="row">
                    <div class="col-md-12">
                        <!-- Budget Summary Section -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h4 class="card-title">Budget Summary</h4>
                                <p><strong>Total Approved Budget:</strong> ₱{{ number_format($totalApprovedBudget, 2) }}
                                </p>
                                <p><strong>Remaining Budget:</strong> ₱{{ number_format($remainingBudget, 2) }}</p>
                            </div>
                        </div>

                        <div class="search-container mb-4">

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Amount</th>
                                        <th>Used For</th>
                                        <th>Date Used</th>
                                        <th>Attachment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usedBudgets as $budget)
                                        <tr>
                                            <td>{{ $budget->id }}</td>
                                            <td>₱{{ number_format($budget->amount, 2) }}</td>
                                            <td>{{ $budget->used_for }}</td>
                                            <td>{{ \Carbon\Carbon::parse($budget->date_used)->format('F d, Y') }}</td>
                                            <td>
                                                @if ($budget->attachment)
                                                    <a href="{{ asset('storage/' . $budget->attachment) }}"
                                                        target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->

            <!-- Add Modal -->
            <div class="modal custom-modal fade" id="add_categories" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Budget Usage</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('budget.usage.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <!-- Amount -->
                                <div class="form-group form-row">
                                    <label class="col-lg-12 control-label">Amount <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <input type="number" class="form-control" placeholder="800.00" name="amount"
                                            required>
                                    </div>
                                </div>

                                <!-- Purpose (used_for) -->
                                <div class="form-group form-row">
                                    <label class="col-lg-12 control-label">Used For <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <textarea class="form-control" name="used_for" placeholder="Explain what the budget was used for" required></textarea>
                                    </div>
                                </div>

                                <!-- Date Used -->
                                <div class="form-group form-row">
                                    <label class="col-lg-12 control-label">Date Used <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <input type="date" class="form-control" name="date_used" required>
                                    </div>
                                </div>

                                <!-- Attachment -->
                                <div class="form-group form-row position-relative">
                                    <label class="col-lg-12 control-label">Attach File <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <input type="file" class="form-control" name="attachment"
                                            accept=".pdf,.jpg,.png,.doc,.docx" required>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="submit-section">
                                    <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /Add Modal -->


            <!-- Delete Holiday Modal -->
            <!-- Delete Confirmation Modal -->
            <div class="modal custom-modal fade" id="deleteBudgetModal" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete Budget Request</h3>
                                <p>Are you sure you want to delete this budget request?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <form id="deleteBudgetForm" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-primary continue-btn">Delete</button>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <a href="javascript:void(0);" data-dismiss="modal"
                                            class="btn btn-primary cancel-btn">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- /Delete Holiday Modal -->

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

    <!-- Datetimepicker JS -->
    <script src="assets/js/moment.min.js"></script>
    <script src="assets/js/bootstrap-datetimepicker.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/js/app.js"></script>
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
</body>

</html>
