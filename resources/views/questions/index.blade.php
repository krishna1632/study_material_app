@extends('layouts.admin')

@section('title', 'Questions List')

@section('content')
    <h1 class="mt-4">Questions List</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('quizzes.index') }}">Quizzes</a></li>
        <li class="breadcrumb-item active">Questions List</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-question-circle me-1"></i>
            Questions for Quiz: {{ $quiz->subject_name }}
            <a href="{{ route('questions.create', $quiz->id) }}" class="btn btn-primary btn-sm float-end">Add Question</a>
            <a href="{{ route('quizzes.index') }}" class="btn btn-secondary btn-sm float-end me-2">Add Questions Later</a>
        </div>
        <div class="card-body">
            @if ($questions->isEmpty())
                <div class="alert alert-info">
                    No questions available. Please add a new question.
                </div>
            @else
                <table id="datatablesSimple" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sl No.</th>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Correct Option</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($questions as $index => $question)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $question->question_text }}</td>
                                <td>
                                    <ul>
                                        @foreach (json_decode($question->options, true) as $value)
                                            <li>{{ $value }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ json_decode($question->options, true)[$question->correct_option - 1] ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('questions.edit', ['quizId' => $quiz->id, 'id' => $question->id]) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST"
                                        class="d-inline" id="deleteForm_{{ $question->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm delete-btn"
                                            data-id="{{ $question->id }}">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Checkbox and Final Submit Button -->
                <div class="mt-4">
                    <input type="checkbox" id="confirmFinalize">
                    <label for="confirmFinalize">I confirm that all questions are correct and finalized &  add instructions.</label>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <form method="POST" action="{{ route('questions.submit', $quiz->id) }}">
                        @csrf
                        <button id="finalSubmit" class="btn btn-success mt-2" disabled>Final Submit</button>
                    </form>
                    <button id="toggleInstructionForm" class="btn btn-info mt-2">Add Instructions</button>
                </div>


                <!-- Add Instructions Form -->
                <div id="instructionForm" class="mt-4" style="display: none;">
                    <form method="POST" action="{{ route('quizzes.storeInstructions', $quiz->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="total_no_of_question" class="form-label">Total Number of Questions</label>
                            <input type="number" class="form-control" id="total_no_of_question" name="total_no_of_question"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="attempt_no" class="form-label">Attempt Number</label>
                            <input type="number" class="form-control" id="attempt_no" name="attempt_no" required>
                        </div>
                        <div class="mb-3">
                            <label for="weightage" class="form-label">Weightage</label>
                            <input type="text" class="form-control" id="weightage" name="weightage" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Instructions</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Include SweetAlert CSS and JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert Confirmation for Delete -->

    <!-- Toggle Instructions Form -->
    <script>
        document.getElementById('toggleInstructionForm').addEventListener('click', function() {
            const instructionForm = document.getElementById('instructionForm');
            instructionForm.style.display = instructionForm.style.display === 'none' ? 'block' : 'none';
        });
    </script>
    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const questionId = this.dataset.id;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm_' + questionId)
                            .submit(); // Submit the delete form
                    }
                });
            });
        });

        // Enable Final Submit button when checkbox is checked
        document.getElementById('confirmFinalize')?.addEventListener('change', function() {
            const finalSubmitButton = document.getElementById('finalSubmit');
            finalSubmitButton.disabled = !this.checked;
        });
    </script>

    <!-- SweetAlert Success Popup -->
    @if (session('success'))
        <script>
            window.onload = function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        </script>
    @endif
@endsection
