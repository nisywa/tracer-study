<input type="number" 
       name="value" 
       value="{{ old('value', $existingAnswer ? $existingAnswer->jawaban : '') }}"
       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
       placeholder="Masukkan angka...">

@error('value')
    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
@enderror
