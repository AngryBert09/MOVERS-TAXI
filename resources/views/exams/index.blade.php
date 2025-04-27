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
                            <h3 class="page-title">Exam Questions</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                <li class="breadcrumb-item">Jobs</li>
                                <li class="breadcrumb-item active">Exam Questions</li>
                            </ul>
                        </div>
                        <div class="col-auto float-right ml-auto">
                            <a href="#" class="btn add-btn mb-1" data-toggle="modal"
                                data-target="#add_question"><i class="fa fa-plus"></i> Add Question</a>
                            {{-- <a href="#" class="btn add-btn mr-1 mb-1" data-toggle="modal"
                                data-target="#add_category"><i class="fa fa-plus"></i> Add Category</a> --}}
                        </div>
                    </div>
                </div>
                <!-- /Page Header -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-striped custom-table mb-0 datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Questions</th>
                                        <th>Option A</th>
                                        <th>Option B</th>
                                        <th>Option C</th>
                                        <th>Option D</th>
                                        <th class="text-center">Correct Answer</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($questions as $index => $question)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $question->category }}</td>
                                            <td>{{ $question->question }}</td>
                                            <td>{{ $question->option_a }}</td>
                                            <td>{{ $question->option_b }}</td>
                                            <td>{{ $question->option_c }}</td>
                                            <td>{{ $question->option_d }}</td>
                                            <td class="text-center">{{ $question->correct_answer }}</td>
                                            <td class="text-center">
                                                <div class="dropdown dropdown-action">
                                                    <a href="#" class="action-icon dropdown-toggle"
                                                        data-toggle="dropdown" aria-expanded="false"><i
                                                            class="material-icons">more_vert</i></a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#edit_question_{{ $question->id }}"><i
                                                                class="fa fa-pencil m-r-5"></i>
                                                            Edit</a>
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#delete_job"><i
                                                                class="fa fa-trash-o m-r-5"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Edit Question Modal -->
                                        <div id="edit_question_{{ $question->id }}" class="modal custom-modal fade"
                                            role="dialog">
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Question</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form method="POST"
                                                            action="{{ route('questions.update', $question->id) }}">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Category</label>
                                                                        <input class="form-control" type="text"
                                                                            name="category"
                                                                            value="{{ $question->category ?? '' }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Question</label>
                                                                        <textarea class="form-control" name="question">{{ $question->question ?? '' }}</textarea>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Option A</label>
                                                                        <input class="form-control" type="text"
                                                                            name="option_a"
                                                                            value="{{ $question->option_a ?? '' }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Option B</label>
                                                                        <input class="form-control" type="text"
                                                                            name="option_b"
                                                                            value="{{ $question->option_b ?? '' }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Option C</label>
                                                                        <input class="form-control" type="text"
                                                                            name="option_c"
                                                                            value="{{ $question->option_c ?? '' }}">
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Option D</label>
                                                                        <input class="form-control" type="text"
                                                                            name="option_d"
                                                                            value="{{ $question->option_d ?? '' }}">
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <label>Correct Answer</label>
                                                                        <select name="correct_answer"
                                                                            class="form-control">
                                                                            <option value="">Select Correct
                                                                                Answer</option>
                                                                            <option value="A"
                                                                                {{ $question->correct_answer == 'A' ? 'selected' : '' }}>
                                                                                Option A</option>
                                                                            <option value="B"
                                                                                {{ $question->correct_answer == 'B' ? 'selected' : '' }}>
                                                                                Option B</option>
                                                                            <option value="C"
                                                                                {{ $question->correct_answer == 'C' ? 'selected' : '' }}>
                                                                                Option C</option>
                                                                            <option value="D"
                                                                                {{ $question->correct_answer == 'D' ? 'selected' : '' }}>
                                                                                Option D</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>



                                                            <div class="submit-section">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Page Content -->


            <!-- Add Questions Modal -->
            <div id="add_question" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Questions</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('questions.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Add Category</label>
                                            <input class="form-control" type="text" name="category">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Add Questions</label>
                                            <textarea class="form-control" name="question"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Option A</label>
                                            <input class="form-control" type="text" name="option_a">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Option B</label>
                                            <input class="form-control" type="text" name="option_b">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Option C</label>
                                            <input class="form-control" type="text" name="option_c">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Option D</label>
                                            <input class="form-control" type="text" name="option_d">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Correct Answer</label>
                                            <select name="correct_answer" class="select form-control">
                                                <option value="">Select Correct Answer</option>
                                                <option value="A">Option A</option>
                                                <option value="B">Option B</option>
                                                <option value="C">Option C</option>
                                                <option value="D">Option D</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="submit-section">
                                    <button type="button" class="btn btn-secondary submit-btn"
                                        data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary submit-btn">Save</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /Add Questions Modal -->



            <!-- Edit question Modal -->
            {{-- <div id="edit_question" class="modal custom-modal fade" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Questions</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Category</label>
                                            <select class="select">
                                                <option>-</option>
                                                <option selected>HTML</option>
                                                <option>CSS</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Add Questions</label>
                                            <textarea class="form-control">
													IS management has decided to rewrite a legacy customer relations system using fourth generation languages (4GLs). Which of the following risks is MOST often associated with system development using 4GLs?
												</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Option A</label>
                                            <input class="form-control" type="text" value="Design facilities">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Option B</label>
                                            <input class="form-control" type="text" value="language subsets">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Option C</label>
                                            <input class="form-control" type="text" value="Lack of portability">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Option D</label>
                                            <input class="form-control" type="text"
                                                value="Inability to perform data">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Correct Answer</label>
                                            <select class="select">
                                                <option>-</option>
                                                <option selected>Option A</option>
                                                <option>Option B</option>
                                                <option>Option C</option>
                                                <option>Option D</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Cancel</button>
                                    <button class="btn btn-primary submit-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> --}}
            <!-- /Edit Job Modal -->

            <!-- Delete Job Modal -->
            <div class="modal custom-modal fade" id="delete_job" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="form-header text-center">
                                <h3>Delete</h3>
                                <p>Are you sure you want to delete?</p>
                            </div>
                            <div class="modal-btn delete-action">
                                <div class="row">
                                    <div class="col-6">
                                        <form method="POST"
                                            action="{{ route('questions.destroy', $question->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-block">Delete</button>
                                        </form>
                                    </div>
                                    <div class="col-6">
                                        <button type="button" data-dismiss="modal"
                                            class="btn btn-secondary btn-block">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /Delete Job Modal -->

        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Example usage of SweetAlert2
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Show a success alert when a question is added
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            // Example: Confirmation before deleting a question
            document.querySelectorAll('.delete-action .continue-btn').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Perform delete action here
                            window.location.href = button.getAttribute('href');
                        }
                    });
                });
            });
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
