<div class="space-y-3">
    @foreach($options as $option)
        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
            <input type="radio" 
                   name="answer_option_id" 
                   value="{{ $option->id }}"
                   @if($existingAnswer && $existingAnswer->jawaban == $option->id) checked @endif
                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
            <span class="ml-3 text-gray-900">{{ $option->pilihan_jawaban }}</span>
        </label>
    @endforeach
</div>

@error('answer_option_id')
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror
