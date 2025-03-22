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
                            <h3 class="page-title">Budget Request</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item active">Accounts</li>
                            </ul>
                        </div>
                        <div class="col-auto float-right ml-auto">
                            <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_categories"><i
                                    class="fa fa-plus"></i> Request</a>
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Purpose</th>
                                        <th>Amount</th>
                                        <th>Request Date</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requests as $request)
                                        <tr>

                                            <td>{{ $request->id }}</td>
                                            <td>{{ $request->purpose }}</td>
                                            <td>{{ $request->amount }}</td>
                                            <td>{{ $request->created_at }}</td>
                                            <td>
                                                @if ($request->status == 'Pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif ($request->status == 'Approved')
                                                    <span class="badge badge-success">Approved</span>
                                                @elseif ($request->status == 'Rejected')
                                                    <span class="badge badge-danger">Rejected</span>
                                                @else
                                                    <span class="badge badge-secondary">{{ $request->status }}</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false"><i
                                                            class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#edit_categories"><i
                                                                class="fa fa-pencil m-r-5"></i> Edit</a>
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#delete"><i class="fa fa-trash-o m-r-5"></i>
                                                            Delete</a>
                                                    </div>
                                                </div>
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
                            <h5 class="modal-title">Request Budget</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('budget.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group form-row">
                                    <label class="col-lg-12 control-label">Amount <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <input type="number" class="form-control" placeholder="800.00" name="amount"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group form-row">
                                    <label class="col-lg-12 control-label">Purpose <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <textarea class="form-control ta" name="purpose" required></textarea>
                                    </div>
                                </div>

                                <div class="form-group form-row position-relative">
                                    <label class="col-lg-12 control-label">Attach File <span
                                            class="text-danger">*</span></label>
                                    <div class="col-lg-12">
                                        <input type="file" class="form-control" name="file"
                                            accept=".pdf,.jpg,.png,.doc,.docx" required>
                                    </div>
                                </div>

                                <div class="submit-section">
                                    <button type="submit" class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /Add Modal -->

            <!-- Edit Modal -->
            <div class="modal custom-modal fade" id="edit_categories" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Expenses</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group form-row">
                                <label class="col-lg-12 control-label">Amount <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" placeholder="800.00" name="amount">
                                </div>
                                <div class="col-lg-6">
                                    <select name="currency_symbol" class="form-control">
                                        <option value="$ - AUD">$ - Australian Dollar</option>
                                        <option value="Bs. - VEF">Bs. - Bolívar Fuerte</option>
                                        <option value="R$ - BRL">R$ - Brazilian Real</option>
                                        <option value="£ - GBP">£ - British Pound</option>
                                        <option value="$ - CAD">$ - Canadian Dollar</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-lg-12 control-label">Notes <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-12">
                                    <textarea class="form-control ta" name="notes"></textarea>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-lg-12 control-label">Expense Date <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-12">
                                    <input class="datepicker-input form-control" type="text" value="07-05-2021"
                                        name="expense_date" data-date-format="dd-mm-yyyy">
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-lg-12 control-label">Category <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-12">
                                    <select name="category" class="form-control m-b" id="main_category1">
                                        <option value="" disabled="" selected="">Choose Category</option>
                                        <option value="1">project1</option>
                                        <option value="3">test category</option>
                                        <option value="4">Hardware</option>
                                        <option value="5">Material</option>
                                        <option value="6">Vehicle</option>
                                        <option value="8">TestctrE</option>
                                        <option value="9">Twocatr</option>
                                        <option value="10">fesferwf</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row">
                                <label class="col-lg-12 control-label">Sub Category <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-12">
                                    <select name="sub_category" class="form-control m-b" id="sub_category1">
                                        <option value="">Choose Sub-Category</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-row  position-relative">
                                <label class="col-lg-12 control-label">Attach File</label>
                                <div class="col-lg-12">

                                    <input type="file" class="form-control" data-buttontext="Choose File"
                                        data-icon="false" data-classbutton="btn btn-default"
                                        data-classinput="form-control inline input-s" name="receipt">
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Edit Modal -->

            <!-- Delete Holiday Modal -->
            <div class="modal custom-modal fade" id="delete" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header">
                                <h3>Delete </h3>
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
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

</body>

</html>
