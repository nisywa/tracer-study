<select name="answer_option_id" 
        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
    <option value="">-- Pilih jawaban --</option>
    @foreach($options as $option)
        <option value="{{ $option->id }}"
                @if($existingAnswer && $existingAnswer->jawaban == $option->id) selected @endif>
            {{ $option->pilihan_jawaban }}
        </option>
    @endforeach
</select>

@error('answer_option_id')
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror
