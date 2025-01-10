<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container mt-5">
        <h1>Edit Question</h1>

        <form method="POST" action="{{ route('questions.update', ['quizId' => $quiz->id, 'id' => $question->id]) }}">
            @csrf
            @method('PUT')
            <div class="card p-3 mb-3">
                <h5>Question</h5>
                <div class="mb-3">
                    <label for="question_text" class="form-label">Write Your Question</label>
                    <input type="text" name="question_text" class="form-control" id="question_text"
                        value="{{ old('question_text', $question->question_text) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">How many options do you want to create?</label>
                    <div>
                        <input type="radio" name="option_count" value="1"
                            {{ count($question->options) == 1 ? 'checked' : '' }}> Only one option<br>
                        <input type="radio" name="option_count" value="2"
                            {{ count($question->options) == 2 ? 'checked' : '' }}> Two options<br>
                        <input type="radio" name="option_count" value="3"
                            {{ count($question->options) == 3 ? 'checked' : '' }}> Three options<br>
                        <input type="radio" name="option_count" value="4"
                            {{ count($question->options) == 4 ? 'checked' : '' }}> Four options<br>
                        <input type="radio" name="option_count" value="5"
                            {{ count($question->options) > 4 ? 'checked' : '' }}> Others<br>
                    </div>
                </div>

                <div id="optionsContainer">
                    @foreach ($question->options as $index => $option)
                        <div class="mb-3">
                            <label class="form-label">Option {{ $index + 1 }}</label>
                            <input type="text" name="options[{{ $index }}]" class="form-control"
                                value="{{ $option }}" required>
                        </div>
                    @endforeach
                </div>

                <div class="mb-3">
                    <label for="correct_option" class="form-label">Choose the Correct Option</label>
                    <select name="correct_option" id="correct_option" class="form-select" required>
                        @foreach ($question->options as $index => $option)
                            <option value="{{ $index + 1 }}"
                                {{ $question->correct_option == $index + 1 ? 'selected' : '' }}>Option
                                {{ $index + 1 }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update Question</button>
                <a href="{{ route('questions.index', $quiz->id) }}" class="btn btn-danger">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        // Handle radio button changes for options count
        document.querySelectorAll('input[name="option_count"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const count = parseInt(this.value);
                const optionsContainer = document.getElementById('optionsContainer');
                const correctOptionSelect = document.getElementById('correct_option');

                // Clear existing options and correct_option dropdown
                optionsContainer.innerHTML = '';
                correctOptionSelect.innerHTML = '';

                // Add new options based on the selected count
                for (let i = 0; i < count; i++) {
                    // Add input fields for options
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = `options[${i}]`;
                    input.className = 'form-control mb-3';
                    input.placeholder = `Option ${i + 1}`;
                    optionsContainer.appendChild(input);

                    // Add corresponding options to correct_option dropdown
                    const option = document.createElement('option');
                    option.value = i + 1; // Correct option value starts from 1
                    option.textContent = `Option ${i + 1}`;
                    correctOptionSelect.appendChild(option);
                }
            });
        });
    </script>

</body>

</html>
