@extends('admin.layouts.app')

@section('content')
    <!-- Survey Form Header -->
    <div class="flex flex-wrap -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                <div class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                    <div class="flex items-center justify-between mb-4">
                        <h6 class="dark:text-white">
                            @if(isset($survey))
                                Edit Survey - {{ $survey->nama }}
                            @else
                                Form Builder - Buat Survey Baru
                            @endif
                        </h6>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.survey.index') }}"
                               class="inline-block px-4 py-2 font-bold leading-normal text-center text-gray-600 align-middle transition-all ease-in bg-gray-100 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                            <button type="button" id="saveSurvey"
                                class="inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-green-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                <i class="fas fa-save mr-2"></i> Simpan Survey
                            </button>
                        </div>
                    </div>
                    
                    <!-- Survey Basic Info -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Survey</label>
                            <input type="text" id="surveyName" name="survey_name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   value="{{ $survey->nama ?? '' }}" placeholder="Masukkan nama survey" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Survey</label>
                            <textarea id="surveyDescription" name="survey_description" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      rows="3" placeholder="Deskripsi atau tujuan survey">{{ $survey->deskripsi ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                    

                </div>
            </div>
        </div>

        <!-- start form -->
        <div class="flex flex-wrap -mx-3">
            <div class="flex-none w-full max-w-full px-3">
                <div
                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                    <div
                        class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                        <div class="flex items-center justify-between mb-4">
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                <i class="fas fa-layer-group mr-2 text-blue-600"></i>
                                <span>Section 1</span>
                            </div>

                            <div class="flex space-x-2">
                                <button type="button"
                                    class="add-block-btn inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                    <i class="fas fa-plus mr-2"></i> Tambah Blok
                                </button>
                                <button type="button"
                                    class="duplicate-block-btn inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-green-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                    <i class="fas fa-copy mr-2"></i> Duplicate Blok
                                </button>
                                <button type="button"
                                    class="delete-block-btn inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-red-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                    <i class="fas fa-trash mr-2"></i> Hapus Blok
                                </button>
                            </div>
                        </div>

                        <!-- Block Name and Description -->
                        <div class="mb-4 space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Blok</label>
                                <textarea class="dark:text-white w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                          rows="2" 
                                          placeholder="Tulis nama blok disini..."
                                          style="min-height: 50px;"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Blok</label>
                                <textarea class="dark:text-white w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                          rows="2" 
                                          placeholder="Tulis deskripsi blok disini..."
                                          style="min-height: 50px;"></textarea>
                            </div>
                        </div>

                        <div class="mb-4 space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pertanyaan</label>
                                
                            </div>
                        </div>

                        <!-- Question Container with Outline -->
                        <div class="border-2 border-gray-300 rounded-lg p-4 mb-4 bg-white shadow-sm">
                            <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <!-- Question and Type Selector Row -->
                                <div class="flex items-start justify-between mb-3 gap-4">
                                    <textarea class="dark:text-white flex-1 text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                              rows="3" 
                                              placeholder="Tulis pertanyaan disini..."
                                              style="min-height: 60px;"></textarea>
                                    <select class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white flex-shrink-0 input-type" 
                                            name="tipe" 
                                            onchange="changeInputTypeFormBuilder(this)">
                                        <option value="">Tipe Jawaban</option>
                                        <option value="text">Short Answer</option>
                                        <option value="textarea">Paragraph</option>
                                        <option value="checkbox">Checkboxes</option>
                                        <option value="radio">Radio</option>
                                        <option value="select">Dropdown</option>
                                        <option value="file">File</option>
                                        <option value="date">Datepicker</option> 
                                    </select>
                                </div>
                                
                                <!-- Description Row -->
                                <div>
                                    <textarea class="dark:text-white w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                              rows="2" 
                                              placeholder="Tulis deskripsi disini..."
                                              style="min-height: 50px;"></textarea>
                                </div>
                            </div>

                            <!-- Hidden input for required state -->
                            <input type="hidden" name="required" value="0" class="question-required">
                            
                            <!-- Answer Options Column -->
                            <div class="mb-4">
                                <div class="flex items-start gap-4">
                                    <!-- Answer Options Section -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Jawaban</label>
                                        <div class="personal-column">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">Pilih tipe jawaban terlebih dahulu</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Visualization Column -->
                                    <div class="w-48">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Visualisasi</label>
                                        <select name="visualisasi" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white w-full">
                                            <option value="">Pilih Visualisasi</option>
                                            <option value="bar">Bar Chart</option>
                                            <option value="pie">Pie Chart</option>
                                            <option value="lineChart">Tidak ada</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Icons -->
                            <div class="flex justify-end mt-4">
                                <div class="flex space-x-2">
                                    <button type="button" class="icon-link duplicate-row p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors" data-tooltip="Duplicate" title="Duplicate">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                   
                                    <button type="button" class="icon-link delete-row p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" data-tooltip="Delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                     <button type="button" class="icon-link required-toggle flex items-center px-3 py-2 text-gray-600 hover:text-orange-600 border border-gray-300 rounded-lg transition-colors cursor-pointer" data-tooltip="Required" title="Toggle Required">
                                        <!-- Toggle Switch -->
                                        <div class="relative inline-block w-10 h-5 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out required-switch mr-2">
                                            <!-- Toggle Dot -->
                                            <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200 ease-in-out required-dot"></div>
                                        </div>
                                        <span class="text-xs font-medium">Required</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Add Question Button -->
                        <div class="flex justify-center mt-4">
                            <button type="button" class="add-question-btn inline-flex items-center px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-sm tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85 hover:bg-blue-600">
                                <i class="fas fa-plus mr-2"></i> Tambah Pertanyaan
                            </button>
                        </div>
                    </div>
                    

                </div>

                <!-- Section Navigation Settings -->
                <div class="mt-4 mb-6">
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-route text-purple-600 mr-2"></i>
                            <h4 class="text-sm font-medium text-purple-800">Pengaturan Navigasi Section</h4>
                        </div>
                        <div class="flex items-center gap-4">
                            <label class="text-sm text-purple-700">Setelah Section 1 selesai, lanjut ke:</label>
                            <select class="section-navigation-select text-sm border border-purple-300 rounded px-3 py-2 bg-white" data-current-section="Section 1">
                                <option value="">Lanjut ke section berikutnya</option>
                                <option value="end">Akhiri survey</option>
                            </select>
                        </div>
                        <p class="text-xs text-purple-600 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>
                            Atur kemana responden akan diarahkan setelah menyelesaikan section ini
                        </p>
                    </div>
                </div>



                
            </div>
        </div>

        <!-- end form -->


        <div class="flex justify-end p-4">
            <button type="submit"
                class="px-8 py-2 font-bold text-white bg-green-500 rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
        </div>
    </form>
    <table style="display: none;">
        <tbody>
            <tr id="templateRow">
                <td
                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                    <div class="flex flex-col px-2 py-1">
                        <textarea type="text" name="pertanyaan[]"
                            class="text-sm leading-normal dark:text-white bg-transparent outline-none question-input"
                            placeholder="Tulis Pertanyaan disini"
                            rows="3"
                            style="resize: none; width: 100%;"
                            oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px';"></textarea>
                    </div>
                </td>
                <td
                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                    <div class="flex flex-col px-2 py-1">
                        <textarea type="text" name="deskripsi[]"
                            class="text-sm leading-normal dark:text-white bg-transparent outline-none description-input"
                            placeholder="Tulis Deskripsi disini"
                            rows="3"
                            style="resize: none; width: 100%;"
                            oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px';"></textarea>
                    </div>
                </td>
                <td
                    class="p-2 text-center align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                    <div class="flex flex-col px-2 py-1">
                        <textarea type="text" name="blok[]"
                            class="text-sm leading-normal dark:text-white bg-transparent outline-none question-input"
                            placeholder="Blok Pertanyaan"
                            rows="3"
                            style="resize: none; width: 100%;"
                            oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px';"></textarea>
                    </div>
                </td>
                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                    <select name="tipe[]"
                        class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 input-type"
                        onchange="changeInputType(this)">
                        <option value="">Pilih Tipe</option>
                        <option value="text">Short Answer</option>
                        <option value="textarea">Paragraph</option>
                        <option value="checkbox">Checkboxes</option>
                        <option value="radio">Radio</option>
                        <option value="select">Dropdown</option>
                        <option value="file">File</option>
                        <option value="date">Datepicker</option>
                    </select>
                </td>
                <td
                    class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent personal-column">
                    <span class="text-xs font-semibold leading-tight text-slate-400">Tipe Jawaban</span>
                </td>

                <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                    <select name="visualisasi[]"
                        class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 input-type">
                        <option value="">Pilih Visualisasi</option>
                        <option value="bar">Bar Chart</option>
                        <option value="pie">Pie Chart</option>
                        <option value="lineChart">Tidak ada</option>
                    </select>
                </td>

                <td
                    class="p-2 align-middle bg-transparent border-b dark:border-white/40 whitespace-nowrap shadow-transparent">
                    <div class="icon-container">
                        <a class="icon-link duplicate-row" data-tooltip="Duplicate">
                            <i class="fas fa-copy"></i>
                        </a>
                        <a class="icon-link delete-row" data-tooltip="Delete">
                            <i class="fas fa-trash"></i>
                        </a>
                        <a class="icon-link move-up" data-tooltip="Move Up">
                            <i class="fas fa-arrow-up"></i>
                        </a>
                        <a class="icon-link move-down" data-tooltip="Move Down">
                            <i class="fas fa-arrow-down"></i>
                        </a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    <script src="{{ asset('assets/js/tipejawaban.js') }}"></script>
    <script>
        // Define the form builder change function globally before DOM loads
        window.changeInputTypeFormBuilder = function(selectEl) {
            const value = selectEl.value;
            const valueText = selectEl.options[selectEl.selectedIndex].text;
            const personalColumn = selectEl.closest('.border-2').querySelector('.personal-column');
            
            if (!personalColumn) return;
            
            if (["checkbox", "radio", "select"].includes(value)) {
                personalColumn.innerHTML = `
                    <div id="optionContainer" class="space-y-2"></div>
                    <button type="button" onclick="addOptionWithBranching(this, '${value}')"
                      class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 mt-2 bg-blue-100 hover:bg-blue-200 transition-colors">
                      <i class="fas fa-plus mr-2"></i>Tambah Pilihan
                    </button>
                `;
            } else {
                personalColumn.innerHTML = `<span class="text-xs font-semibold leading-tight text-slate-400">${valueText}</span>`;
            }
        };

        // Function to get available sections for branching
        window.getAvailableSections = function() {
            const allBlocks = document.querySelectorAll('.flex.flex-wrap.-mx-3');
            const formBlocks = Array.from(allBlocks).filter(block => 
                block.querySelector('.inline-flex.items-center.px-3.py-1.rounded-full')
            );
            
            const sectionOptions = [];
            formBlocks.forEach((block, index) => {
                const sectionNumber = index + 1;
                sectionOptions.push(`Section ${sectionNumber}`);
            });
            return sectionOptions;
        };

        // Function to update section numbering dynamically
        window.updateSectionNumbering = function() {
            const allBlocks = document.querySelectorAll('.flex.flex-wrap.-mx-3');
            const formBlocks = Array.from(allBlocks).filter(block => 
                block.querySelector('.inline-flex.items-center.px-3.py-1.rounded-full')
            );
            
            formBlocks.forEach((block, index) => {
                const sectionNumber = index + 1;
                const sectionSpan = block.querySelector('.inline-flex.items-center.px-3.py-1.rounded-full span');
                if (sectionSpan) {
                    sectionSpan.textContent = `Section ${sectionNumber}`;
                }
                
                // Update navigation section data
                const navigationSelect = block.querySelector('.section-navigation-select');
                if (navigationSelect) {
                    navigationSelect.setAttribute('data-current-section', `Section ${sectionNumber}`);
                }
                
                // Update navigation label
                const navigationLabel = block.querySelector('.text-sm.text-purple-700');
                if (navigationLabel) {
                    navigationLabel.textContent = `Setelah Section ${sectionNumber} selesai, lanjut ke:`;
                }
            });
            
            // Update navigation dropdowns after numbering update
            setTimeout(() => {
                updateSectionNavigationDropdowns();
            }, 50);
        };

        // Function to update section navigation dropdowns
        window.updateSectionNavigationDropdowns = function() {
            const availableSections = getAvailableSections();
            const navigationSelects = document.querySelectorAll('.section-navigation-select');
            
            navigationSelects.forEach(select => {
                const currentSection = select.getAttribute('data-current-section');
                const currentValue = select.value;
                
                // Clear existing options and rebuild
                select.innerHTML = '';
                
                // Add default options
                const defaultNextOption = document.createElement('option');
                defaultNextOption.value = '';
                defaultNextOption.textContent = 'Lanjut ke section berikutnya';
                select.appendChild(defaultNextOption);
                
                const endOption = document.createElement('option');
                endOption.value = 'end';
                endOption.textContent = 'Akhiri survey';
                select.appendChild(endOption);
                
                // Add all available sections as jump options (excluding current section)
                availableSections.forEach(section => {
                    if (section !== currentSection) {
                        const option = document.createElement('option');
                        option.value = section.toLowerCase().replace(' ', '_'); // e.g., "section_2"
                        option.textContent = `Loncat ke ${section}`;
                        select.appendChild(option);
                    }
                });
                
                // Restore previous value if still valid
                const validOptions = Array.from(select.options).map(opt => opt.value);
                if (currentValue && validOptions.includes(currentValue)) {
                    select.value = currentValue;
                } else {
                    // Reset to default if previous value is no longer valid
                    select.value = '';
                }
            });
        };

        // Function to handle duplicate block
        window.duplicateBlock = function(button) {
            const blockContainer = button.closest('.flex.flex-wrap.-mx-3');
            if (!blockContainer) return;
            
            // Clone the entire block
            const clonedBlock = blockContainer.cloneNode(true);
            
            // Insert the cloned block after the current block
            blockContainer.parentNode.insertBefore(clonedBlock, blockContainer.nextSibling);
            
            // Update section numbering for all sections
            updateSectionNumbering();
            
            // Add event listeners to the cloned block
            addEventListenersToSection(clonedBlock);
            
            // Update all section navigation dropdowns
            setTimeout(() => {
                updateSectionNavigationDropdowns();
                addNavigationSelectListeners();
            }, 100);
            
            // Scroll to the cloned block
            clonedBlock.scrollIntoView({ behavior: 'smooth', block: 'center' });
        };

        // Function to handle delete block
        window.deleteBlock = function(button) {
            const blockContainer = button.closest('.flex.flex-wrap.-mx-3');
            if (!blockContainer) return;
            
            // Count total blocks (sections)
            const allBlocks = document.querySelectorAll('.flex.flex-wrap.-mx-3');
            const formBlocks = Array.from(allBlocks).filter(block => 
                block.querySelector('.inline-flex.items-center.px-3.py-1.rounded-full')
            );
            
            if (formBlocks.length <= 1) {
                // Show custom modal for minimum section warning
                showCustomAlert('Peringatan', 'Tidak dapat menghapus blok. Minimal harus ada satu blok dalam survey.');
                return;
            }
            
            const sectionSpan = blockContainer.querySelector('.inline-flex.items-center.px-3.py-1.rounded-full span');
            const sectionName = sectionSpan ? sectionSpan.textContent : 'Blok ini';
            
            // Show custom confirmation modal
            showCustomConfirm(
                'Konfirmasi Hapus',
                `Apakah Anda yakin ingin menghapus ${sectionName}? Semua pertanyaan dalam blok ini akan ikut terhapus.`,
                function() {
                    // User clicked "Ya" - proceed with deletion
                    blockContainer.remove();
                    
                    // Update section numbering for all remaining sections
                    updateSectionNumbering();
                    
                    // Update all section navigation dropdowns
                    setTimeout(() => {
                        updateSectionNavigationDropdowns();
                        addNavigationSelectListeners();
                    }, 100);
                },
                function() {
                    // User clicked "Batal" - do nothing, modal will close
                    console.log('Delete cancelled by user');
                }
            );
        };

        // Function to show custom alert modal
        window.showCustomAlert = function(title, message) {
            const modalHTML = `
                <div id="customAlertModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex items-center justify-center mb-4">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="text-center">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">${title}</h3>
                                <p class="text-sm text-gray-500 mb-4">${message}</p>
                            </div>
                            <div class="flex justify-center">
                                <button type="button" onclick="closeCustomAlert()" class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        };

        // Function to show custom confirmation modal
        window.showCustomConfirm = function(title, message, onConfirm, onCancel) {
            const modalHTML = `
                <div id="customConfirmModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex items-center justify-center mb-4">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100">
                                    <i class="fas fa-question-circle text-yellow-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="text-center">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">${title}</h3>
                                <p class="text-sm text-gray-500 mb-4">${message}</p>
                            </div>
                            <div class="flex justify-center space-x-3">
                                <button type="button" id="confirmYes" class="px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-md shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-300">
                                    Ya
                                </button>
                                <button type="button" id="confirmCancel" class="px-4 py-2 bg-gray-300 text-gray-900 text-sm font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            
            // Add event listeners
            document.getElementById('confirmYes').addEventListener('click', function() {
                closeCustomConfirm();
                if (onConfirm) onConfirm();
            });
            
            document.getElementById('confirmCancel').addEventListener('click', function() {
                closeCustomConfirm();
                if (onCancel) onCancel();
            });
        };

        // Function to close custom alert
        window.closeCustomAlert = function() {
            const modal = document.getElementById('customAlertModal');
            if (modal) {
                modal.remove();
            }
        };

        // Function to close custom confirmation
        window.closeCustomConfirm = function() {
            const modal = document.getElementById('customConfirmModal');
            if (modal) {
                modal.remove();
            }
        };

        // Function to renumber sections after deletion
        window.renumberSections = function() {
            updateSectionNumbering();
        };

        // Function to add option with branching capability
        window.addOptionWithBranching = function(button, questionType) {
            const container = button.previousElementSibling;
            const optionCount = container.children.length + 1;
            const availableSections = getAvailableSections();
            
            let sectionOptions = '<option value="">Lanjut ke pertanyaan berikutnya</option>';
            availableSections.forEach(section => {
                sectionOptions += `<option value="${section}">${section}</option>`;
            });

            let branchingHTML = '';
            if (questionType === 'radio') {
                branchingHTML = `
                    <div class="ml-4 mt-2">
                        <label class="text-xs text-gray-600">Arahkan ke:</label>
                        <select class="text-xs border border-gray-300 rounded px-2 py-1 ml-2 branching-select" name="branching_${optionCount}">
                            ${sectionOptions}
                        </select>
                    </div>
                `;
            }

            const optionHTML = `
                <div class="flex items-center gap-2 option-item">
                    <input type="text" 
                           class="flex-1 text-xs border border-gray-300 rounded px-2 py-1" 
                           placeholder="Pilihan ${optionCount}" 
                           name="option_${optionCount}">
                    <button type="button" 
                            onclick="moveOptionUp(this)" 
                            class="text-blue-500 hover:text-blue-700 text-xs p-1" 
                            title="Move Up">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button" 
                            onclick="moveOptionDown(this)" 
                            class="text-blue-500 hover:text-blue-700 text-xs p-1" 
                            title="Move Down">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                    <button type="button" 
                            onclick="removeOption(this)" 
                            class="text-red-500 hover:text-red-700 text-xs p-1">
                        <i class="fas fa-times"></i>
                    </button>
                    ${branchingHTML}
                </div>
            `;

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = optionHTML;
            container.appendChild(tempDiv.firstElementChild);
        };

        // Function to remove option
        window.removeOption = function(button) {
            button.closest('.option-item').remove();
        };

        // Function to move option up
        window.moveOptionUp = function(button) {
            const optionItem = button.closest('.option-item');
            const previousSibling = optionItem.previousElementSibling;
            if (previousSibling) {
                optionItem.parentNode.insertBefore(optionItem, previousSibling);
            }
        };

        // Function to move option down
        window.moveOptionDown = function(button) {
            const optionItem = button.closest('.option-item');
            const nextSibling = optionItem.nextElementSibling;
            if (nextSibling) {
                optionItem.parentNode.insertBefore(nextSibling, optionItem);
            }
        };

        // Function to show branching modal
        window.showBranchingModal = function(questionContainer) {
            const availableSections = getAvailableSections();
            const personalColumn = questionContainer.querySelector('.personal-column');
            const optionContainer = personalColumn.querySelector('#optionContainer');
            
            if (!optionContainer || optionContainer.children.length === 0) {
                alert('Tambahkan pilihan jawaban terlebih dahulu untuk mengatur percabangan');
                return;
            }
            
            // Create modal HTML
            const modalHTML = `
                <div id="branchingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
                    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Atur Percabangan Survey</h3>
                                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeBranchingModal()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="mt-2 px-2 py-3">
                                <p class="text-sm text-gray-500 mb-4">Atur kemana responden akan diarahkan berdasarkan pilihan jawaban mereka:</p>
                                <div id="branchingOptions" class="space-y-3">
                                    <!-- Options will be populated here -->
                                </div>
                            </div>
                            <div class="items-center px-4 py-3">
                                <div class="flex justify-end space-x-2">
                                    <button id="saveBranching" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                        Simpan
                                    </button>
                                    <button type="button" class="px-4 py-2 bg-gray-300 text-gray-900 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300" onclick="closeBranchingModal()">
                                        Batal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            
            // Populate options
            const branchingOptions = document.getElementById('branchingOptions');
            const options = optionContainer.children;
            let sectionSelectOptions = '<option value="">Lanjut ke pertanyaan berikutnya</option>';
            availableSections.forEach(section => {
                sectionSelectOptions += `<option value="${section}">Loncat ke ${section}</option>`;
            });
            
            for (let i = 0; i < options.length; i++) {
                const optionText = options[i].querySelector('input[type="text"]').value || `Pilihan ${i + 1}`;
                const existingBranching = options[i].querySelector('.branching-select')?.value || '';
                
                branchingOptions.innerHTML += `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <span class="w-4 h-4 bg-blue-500 rounded-full mr-3"></span>
                            <span class="text-sm font-medium">${optionText}</span>
                        </div>
                        <select class="branching-rule-select text-sm border border-gray-300 rounded px-2 py-1" data-option-index="${i}">
                            ${sectionSelectOptions}
                        </select>
                    </div>
                `;
            }
            
            // Set existing values
            const ruleSelects = document.querySelectorAll('.branching-rule-select');
            ruleSelects.forEach((select, index) => {
                const existingSelect = options[index].querySelector('.branching-select');
                if (existingSelect) {
                    select.value = existingSelect.value;
                }
            });
            
            // Save branching rules
            document.getElementById('saveBranching').addEventListener('click', function() {
                ruleSelects.forEach((select, index) => {
                    const optionDiv = options[index];
                    let existingBranchingDiv = optionDiv.querySelector('.branching-rule');
                    
                    if (select.value) {
                        if (!existingBranchingDiv) {
                            const branchingHTML = `
                                <div class="branching-rule ml-4 mt-2 p-2 bg-purple-50 border border-purple-200 rounded text-xs">
                                    <i class="fas fa-code-branch text-purple-600 mr-1"></i>
                                    <span class="text-purple-700">Loncat ke: ${select.value}</span>
                                    <input type="hidden" class="branching-target" value="${select.value}">
                                </div>
                            `;
                            optionDiv.insertAdjacentHTML('beforeend', branchingHTML);
                        } else {
                            existingBranchingDiv.innerHTML = `
                                <i class="fas fa-code-branch text-purple-600 mr-1"></i>
                                <span class="text-purple-700">Loncat ke: ${select.value}</span>
                                <input type="hidden" class="branching-target" value="${select.value}">
                            `;
                        }
                    } else {
                        if (existingBranchingDiv) {
                            existingBranchingDiv.remove();
                        }
                    }
                });
                
                closeBranchingModal();
                alert('Aturan percabangan berhasil disimpan!');
            });
        };

        // Function to close branching modal
        window.closeBranchingModal = function() {
            const modal = document.getElementById('branchingModal');
            if (modal) {
                modal.remove();
            }
        };

        // Function to add event listeners to navigation selects
        window.addNavigationSelectListeners = function() {
            const navigationSelects = document.querySelectorAll('.section-navigation-select');
            navigationSelects.forEach(select => {
                // Remove existing listeners to prevent duplicates
                select.removeEventListener('change', handleNavigationChange);
                // Add new listener
                select.addEventListener('change', handleNavigationChange);
            });
        };

        // Function to handle navigation select changes
        window.handleNavigationChange = function(e) {
            const select = e.target;
            const selectedValue = select.value;
            const currentSection = select.getAttribute('data-current-section');
            
            // You can add validation or additional logic here
            console.log(`Section ${currentSection} navigation changed to: ${selectedValue}`);
            
            // Example: Show confirmation for important changes
            if (selectedValue === 'end') {
                console.log(`${currentSection} will end the survey`);
            } else if (selectedValue === '') {
                console.log(`${currentSection} will continue to next section`);
            } else {
                console.log(`${currentSection} will jump to ${selectedValue}`);
            }
        };

        // Function to duplicate a question
        window.duplicateQuestion = function(questionContainer) {
            // Clone the current question container
            const clonedContainer = questionContainer.cloneNode(true);
            
            // Do NOT clear values - keep all the form data from the original question
            // This includes: pertanyaan, deskripsi, tipe jawaban, jawaban, dan visualisasi
            
            // Only reset the required state to default (off)
            const hiddenInput = clonedContainer.querySelector('.question-required');
            if (hiddenInput) {
                hiddenInput.value = '0';
            }
            
            // Reset required toggle button appearance to default (off)
            const requiredToggle = clonedContainer.querySelector('.required-toggle');
            const toggleSwitch = clonedContainer.querySelector('.required-switch');
            const toggleDot = clonedContainer.querySelector('.required-dot');
            if (requiredToggle && toggleSwitch && toggleDot) {
                toggleSwitch.classList.remove('bg-orange-500');
                toggleSwitch.classList.add('bg-gray-200');
                toggleDot.classList.remove('translate-x-5');
                toggleDot.classList.add('translate-x-0');
                requiredToggle.classList.remove('text-orange-600', 'border-orange-300');
                requiredToggle.classList.add('text-gray-600', 'border-gray-300');
                requiredToggle.title = 'Toggle Required';
            }
            
            // Insert after the current question container
            questionContainer.parentNode.insertBefore(clonedContainer, questionContainer.nextSibling);
            clonedContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        };

        // Function to delete a question
        window.deleteQuestion = function(questionContainer) {
            const allQuestionContainers = questionContainer.parentNode.querySelectorAll('.border-2.border-gray-300.rounded-lg.p-4.mb-4.bg-white.shadow-sm');
            
            if (allQuestionContainers.length > 1) {
                showCustomConfirm(
                    'Konfirmasi Hapus',
                    'Apakah Anda yakin ingin menghapus pertanyaan ini?',
                    function() {
                        questionContainer.remove();
                    },
                    function() {
                        console.log('Delete question cancelled');
                    }
                );
            } else {
                showCustomAlert('Peringatan', 'Minimal harus ada satu pertanyaan dalam setiap section.');
            }
        };

        // Function to toggle required state
        window.toggleRequired = function(questionContainer, toggleButton) {
            const hiddenInput = questionContainer.querySelector('.question-required');
            const toggleSwitch = toggleButton.querySelector('.required-switch');
            const toggleDot = toggleButton.querySelector('.required-dot');
            const isRequired = hiddenInput.value === '1';
            
            if (isRequired) {
                // Turn off required
                hiddenInput.value = '0';
                toggleSwitch.classList.remove('bg-orange-500');
                toggleSwitch.classList.add('bg-gray-200');
                toggleDot.classList.remove('translate-x-5');
                toggleDot.classList.add('translate-x-0');
                toggleButton.classList.remove('text-orange-600', 'border-orange-300');
                toggleButton.classList.add('text-gray-600', 'border-gray-300');
                toggleButton.title = 'Toggle Required';
            } else {
                // Turn on required
                hiddenInput.value = '1';
                toggleSwitch.classList.remove('bg-gray-200');
                toggleSwitch.classList.add('bg-orange-500');
                toggleDot.classList.remove('translate-x-0');
                toggleDot.classList.add('translate-x-5');
                toggleButton.classList.remove('text-gray-600', 'border-gray-300');
                toggleButton.classList.add('text-orange-600', 'border-orange-300');
                toggleButton.title = 'Question is Required';
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize event listeners for existing question container
            const existingQuestionContainer = document.querySelector('.border-2.border-gray-300.rounded-lg.p-4.mb-4.bg-white.shadow-sm');
            if (existingQuestionContainer) {
                addEventListenersToQuestionContainer(existingQuestionContainer);
            }
            
            // Initialize event listener for existing add-block button
            const existingAddBlockBtn = document.querySelector('.add-block-btn');
            if (existingAddBlockBtn) {
                addAddBlockListener(existingAddBlockBtn);
            }
            
            // Initialize event listeners for duplicate and delete block buttons
            const existingDuplicateBlockBtns = document.querySelectorAll('.duplicate-block-btn');
            existingDuplicateBlockBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    duplicateBlock(this);
                });
            });
            
            const existingDeleteBlockBtns = document.querySelectorAll('.delete-block-btn');
            existingDeleteBlockBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    deleteBlock(this);
                });
            });
            
            // Use event delegation for dynamically created buttons
            document.addEventListener('click', function(e) {
                if (e.target.closest('.duplicate-block-btn')) {
                    e.preventDefault();
                    duplicateBlock(e.target.closest('.duplicate-block-btn'));
                } else if (e.target.closest('.delete-block-btn')) {
                    e.preventDefault();
                    deleteBlock(e.target.closest('.delete-block-btn'));
                }
                // Handle question-level buttons with event delegation
                else if (e.target.closest('.duplicate-row')) {
                    e.preventDefault();
                    const questionContainer = e.target.closest('.border-2.border-gray-300.rounded-lg.p-4.mb-4.bg-white.shadow-sm');
                    if (questionContainer) {
                        duplicateQuestion(questionContainer);
                    }
                } else if (e.target.closest('.delete-row')) {
                    e.preventDefault();
                    const questionContainer = e.target.closest('.border-2.border-gray-300.rounded-lg.p-4.mb-4.bg-white.shadow-sm');
                    if (questionContainer) {
                        deleteQuestion(questionContainer);
                    }
                } else if (e.target.closest('.required-toggle')) {
                    e.preventDefault();
                    const questionContainer = e.target.closest('.border-2.border-gray-300.rounded-lg.p-4.mb-4.bg-white.shadow-sm');
                    if (questionContainer) {
                        toggleRequired(questionContainer, e.target.closest('.required-toggle'));
                    }
                } else if (e.target.closest('.branching-btn')) {
                    e.preventDefault();
                    const questionContainer = e.target.closest('.border-2.border-gray-300.rounded-lg.p-4.mb-4.bg-white.shadow-sm');
                    if (questionContainer) {
                        const questionType = questionContainer.querySelector('select[name="tipe"]').value;
                        if (questionType === 'radio') {
                            showBranchingModal(questionContainer);
                        } else {
                            alert('Percabangan hanya tersedia untuk tipe Radio Button');
                        }
                    }
                }
            });
            
            // Use event delegation for select type changes
            document.addEventListener('change', function(e) {
                if (e.target.matches('select[name="tipe"]')) {
                    changeInputTypeFormBuilder(e.target);
                }
            });
            
            // Initialize section navigation dropdowns
            setTimeout(() => {
                updateSectionNavigationDropdowns();
                // Add event listeners for navigation selects
                addNavigationSelectListeners();
            }, 100);
            
            // NOTE: All question-level events (duplicate, delete, required, type change) 
            // are now handled via global event delegation above
            
            // Handle add question button
            const addQuestionBtn = document.querySelector('.add-question-btn');
            if (addQuestionBtn) {
                addQuestionBtn.addEventListener('click', function() {
                    // Create new question outline container
                    const newQuestionHTML = `
                        <!-- Question Container with Outline -->
                        <div class="border-2 border-gray-300 rounded-lg p-4 mb-4 bg-white shadow-sm">
                            <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <!-- Question and Type Selector Row -->
                                <div class="flex items-start justify-between mb-3 gap-4">
                                    <textarea class="dark:text-white flex-1 text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                              rows="3" 
                                              placeholder="Tulis pertanyaan disini..."
                                              style="min-height: 60px;"></textarea>
                                    <select class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white flex-shrink-0 input-type" 
                                            name="tipe">
                                        <option value="">Tipe Jawaban</option>
                                        <option value="text">Short Answer</option>
                                        <option value="textarea">Paragraph</option>
                                        <option value="checkbox">Checkboxes</option>
                                        <option value="radio">Radio</option>
                                        <option value="select">Dropdown</option>
                                        <option value="file">File</option>
                                        <option value="date">Datepicker</option> 
                                    </select>
                                </div>
                                
                                <!-- Description Row -->
                                <div>
                                    <textarea class="dark:text-white w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                              rows="2" 
                                              placeholder="Tulis deskripsi disini..."
                                              style="min-height: 50px;"></textarea>
                                </div>
                            </div>

                            <!-- Hidden input for required state -->
                            <input type="hidden" name="required" value="0" class="question-required">
                            
                            <!-- Answer Options Column -->
                            <div class="mb-4">
                                <div class="flex items-start gap-4">
                                    <!-- Answer Options Section -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Jawaban</label>
                                        <div class="personal-column">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">Pilih tipe jawaban terlebih dahulu</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Visualization Column -->
                                    <div class="w-48">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Visualisasi</label>
                                        <select name="visualisasi" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white w-full">
                                            <option value="">Pilih Visualisasi</option>
                                            <option value="bar">Bar Chart</option>
                                            <option value="pie">Pie Chart</option>
                                            <option value="">Tidak ada</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Icons -->
                            <div class="flex justify-end mt-4">
                                <div class="flex space-x-2">
                                    <button type="button" class="icon-link duplicate-row p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors" data-tooltip="Duplicate" title="Duplicate">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                   
                                    <button type="button" class="icon-link delete-row p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" data-tooltip="Delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                     <button type="button" class="icon-link required-toggle flex items-center px-3 py-2 text-gray-600 hover:text-orange-600 border border-gray-300 rounded-lg transition-colors cursor-pointer" data-tooltip="Required" title="Toggle Required">
                                        <!-- Toggle Switch -->
                                        <div class="relative inline-block w-10 h-5 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out required-switch mr-2">
                                            <!-- Toggle Dot -->
                                            <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200 ease-in-out required-dot"></div>
                                        </div>
                                        <span class="text-xs font-medium">Required</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Find the add button container and insert the new question before it
                    const addButtonContainer = this.closest('.flex.justify-center.mt-4');
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = newQuestionHTML;
                    const newQuestionElement = tempDiv.firstElementChild;
                    
                    // Insert the new question container before the add button
                    addButtonContainer.parentNode.insertBefore(newQuestionElement, addButtonContainer);
                    
                    // Add event listeners to the new question container
                    addEventListenersToQuestionContainer(newQuestionElement);
                    
                    // Scroll to the new question
                    newQuestionElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }
            
            // Handle add question button with event delegation
            document.addEventListener('click', function(e) {
                if (e.target.closest('.add-question-btn')) {
                    const addQuestionBtn = e.target.closest('.add-question-btn');
                    
                    // Find the add button container and insert the new question before it
                    const addButtonContainer = addQuestionBtn.closest('.flex.justify-center.mt-4');
                    
                    // Create new question outline container with all required elements
                    const newQuestionHTML = `
                        <!-- Question Container with Outline -->
                        <div class="border-2 border-gray-300 rounded-lg p-4 mb-4 bg-white shadow-sm">
                            <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <!-- Question and Type Selector Row -->
                                <div class="flex items-start justify-between mb-3 gap-4">
                                    <textarea class="dark:text-white flex-1 text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                              rows="3" 
                                              placeholder="Tulis pertanyaan disini..."
                                              style="min-height: 60px;"></textarea>
                                    <select class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white flex-shrink-0 input-type" 
                                            name="tipe">
                                        <option value="">Tipe Jawaban</option>
                                        <option value="text">Short Answer</option>
                                        <option value="textarea">Paragraph</option>
                                        <option value="checkbox">Checkboxes</option>
                                        <option value="radio">Radio</option>
                                        <option value="select">Dropdown</option>
                                        <option value="file">File</option>
                                        <option value="date">Datepicker</option> 
                                    </select>
                                </div>
                                
                                <!-- Description Row -->
                                <div>
                                    <textarea class="dark:text-white w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                              rows="2" 
                                              placeholder="Tulis deskripsi disini..."
                                              style="min-height: 50px;"></textarea>
                                </div>
                            </div>

                            <!-- Hidden input for required state -->
                            <input type="hidden" name="required" value="0" class="question-required">
                            
                            <!-- Answer Options Column -->
                            <div class="mb-4">
                                <div class="flex items-start gap-4">
                                    <!-- Answer Options Section -->
                                    <div class="flex-1">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Jawaban</label>
                                        <div class="personal-column">
                                            <span class="text-xs font-semibold leading-tight text-slate-400">Pilih tipe jawaban terlebih dahulu</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Visualization Column -->
                                    <div class="w-48">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Visualisasi</label>
                                        <select name="visualisasi" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white w-full">
                                            <option value="">Pilih Visualisasi</option>
                                            <option value="bar">Bar Chart</option>
                                            <option value="pie">Pie Chart</option>
                                            <option value="">Tidak Ada</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Icons -->
                            <div class="flex justify-end mt-4">
                                <div class="flex space-x-2">
                                    <button type="button" class="icon-link duplicate-row p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors" data-tooltip="Duplicate" title="Duplicate">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                   
                                    <button type="button" class="icon-link delete-row p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" data-tooltip="Delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                     <button type="button" class="icon-link required-toggle flex items-center px-3 py-2 text-gray-600 hover:text-orange-600 border border-gray-300 rounded-lg transition-colors cursor-pointer" data-tooltip="Required" title="Toggle Required">
                                        <!-- Toggle Switch -->
                                        <div class="relative inline-block w-10 h-5 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out required-switch mr-2">
                                            <!-- Toggle Dot -->
                                            <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200 ease-in-out required-dot"></div>
                                        </div>
                                        <span class="text-xs font-medium">Required</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = newQuestionHTML;
                    const newQuestionElement = tempDiv.firstElementChild;
                    
                    // Insert the new question container before the add button
                    addButtonContainer.parentNode.insertBefore(newQuestionElement, addButtonContainer);
                    
                    // Add event listeners to the new question container
                    addEventListenersToQuestionContainer(newQuestionElement);
                    
                    // Scroll to the new question
                    newQuestionElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
            
            // Handle add block button
            const addBlockBtn = document.querySelector('.add-row');
            if (addBlockBtn && addBlockBtn.textContent.includes('Tambah Blok')) {
                addBlockBtn.addEventListener('click', function() {
                    // Get the current section number
                    const allSections = document.querySelectorAll('.inline-flex.items-center.px-3.py-1.rounded-full');
                    const sectionCount = allSections.length + 1;
                    
                    // Create new section HTML
                    const newSectionHTML = `
                        <!-- start form -->
                        <div class="flex flex-wrap -mx-3">
                            <div class="flex-none w-full max-w-full px-3">
                                <div
                                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                                    <div
                                        class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                <i class="fas fa-layer-group mr-2 text-blue-600"></i>
                                                <span>Section ${sectionCount}</span>
                                            </div>

                                            <div class="flex space-x-2">
                                                <button type="button"
                                                    class="duplicate-block-btn inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-green-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                                    <i class="fas fa-copy mr-2"></i> Duplicate Blok
                                                </button>
                                                <button type="button"
                                                    class="delete-block-btn inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-red-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                                    <i class="fas fa-trash mr-2"></i> Hapus Blok
                                                </button>
                                                <button type="button"
                                                    class="add-block-btn inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                                    <i class="fas fa-plus mr-2"></i> Tambah Blok
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Question Container with Outline -->
                                        <div class="border-2 border-gray-300 rounded-lg p-4 mb-4 bg-white shadow-sm">
                                            <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                                <!-- Question and Type Selector Row -->
                                                <div class="flex items-start justify-between mb-3 gap-4">
                                                    <textarea class="dark:text-white flex-1 text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                                              rows="3" 
                                                              placeholder="Tulis pertanyaan disini..."
                                                              style="min-height: 60px;"></textarea>
                                                    <select class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white flex-shrink-0 input-type" 
                                                            name="tipe">
                                                        <option value="">Tipe Jawaban</option>
                                                        <option value="text">Short Answer</option>
                                                        <option value="textarea">Paragraph</option>
                                                        <option value="checkbox">Checkboxes</option>
                                                        <option value="radio">Radio</option>
                                                        <option value="select">Dropdown</option>
                                                        <option value="file">File</option>
                                                        <option value="date">Datepicker</option> 
                                                    </select>
                                                </div>
                                                
                                                <!-- Description Row -->
                                                <div>
                                                    <textarea class="dark:text-white w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                                              rows="2" 
                                                              placeholder="Tulis deskripsi disini..."
                                                              style="min-height: 50px;"></textarea>
                                                </div>
                                            </div>

                                            <!-- Hidden input for required state -->
                                            <input type="hidden" name="required" value="0" class="question-required">
                                            
                                            <!-- Answer Options Column -->
                                            <div class="mb-4">
                                                <div class="flex items-start gap-4">
                                                    <!-- Answer Options Section -->
                                                    <div class="flex-1">
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Jawaban</label>
                                                        <div class="personal-column">
                                                            <span class="text-xs font-semibold leading-tight text-slate-400">Pilih tipe jawaban terlebih dahulu</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Visualization Column -->
                                                    <div class="w-48">
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Visualisasi</label>
                                                        <select name="visualisasi" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white w-full">
                                                            <option value="">Pilih Visualisasi</option>
                                                            <option value="bar">Bar Chart</option>
                                                            <option value="pie">Pie Chart</option>
                                                            <option value="">Tidak Ada</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Action Icons -->
                                            <div class="flex justify-end mt-4">
                                                <div class="flex space-x-2">
                                                    <button type="button" class="icon-link duplicate-row p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors" data-tooltip="Duplicate" title="Duplicate">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                   
                                                    <button type="button" class="icon-link delete-row p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" data-tooltip="Delete" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                     <button type="button" class="icon-link required-toggle flex items-center px-3 py-2 text-gray-600 hover:text-orange-600 border border-gray-300 rounded-lg transition-colors cursor-pointer" data-tooltip="Required" title="Toggle Required">
                                                        <!-- Toggle Switch -->
                                                        <div class="relative inline-block w-10 h-5 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out required-switch mr-2">
                                                            <!-- Toggle Dot -->
                                                            <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200 ease-in-out required-dot"></div>
                                                        </div>
                                                        <span class="text-xs font-medium">Required</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Add Question Button -->
                                        <div class="flex justify-center mt-4">
                                            <button type="button" class="add-question-btn inline-flex items-center px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-sm tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85 hover:bg-blue-600">
                                                <i class="fas fa-plus mr-2"></i> Tambah Pertanyaan
                                            </button>
                                        </div>
                                    </div>
                                    
                                </div>

                                <!-- Section Navigation Settings -->
                                <div class="mt-4 mb-6">
                                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                        <div class="flex items-center mb-3">
                                            <i class="fas fa-route text-purple-600 mr-2"></i>
                                            <h4 class="text-sm font-medium text-purple-800">Pengaturan Navigasi Section</h4>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <label class="text-sm text-purple-700">Setelah Section ${sectionCount} selesai, lanjut ke:</label>
                                            <select class="section-navigation-select text-sm border border-purple-300 rounded px-3 py-2 bg-white" data-current-section="Section ${sectionCount}">
                                                <option value="">Lanjut ke section berikutnya</option>
                                                <option value="end">Akhiri survey</option>
                                            </select>
                                        </div>
                                        <p class="text-xs text-purple-600 mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Atur kemana responden akan diarahkan setelah menyelesaikan section ini
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end form -->
                    `;
                    
                    // Find the last form section and insert the new section after it
                    const lastFormSection = document.querySelector('.flex.flex-wrap.-mx-3:last-of-type');
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = newSectionHTML;
                    const newSectionElement = tempDiv.firstElementChild;
                    
                    // Insert the new section after the last form section
                    lastFormSection.parentNode.insertBefore(newSectionElement, lastFormSection.nextSibling);
                    
                    // Add event listeners to the new section
                    addEventListenersToSection(newSectionElement);
                    
                    // Add event listeners to question container in the new section
                    const questionContainer = newSectionElement.querySelector('.border-2.border-gray-300.rounded-lg.p-4.mb-4.bg-white.shadow-sm');
                    if (questionContainer) {
                        addEventListenersToQuestionContainer(questionContainer);
                    }
                    
                    // Add event listener to the new add-question button in the new section
                    const newAddQuestionBtn = newSectionElement.querySelector('.add-question-btn');
                    if (newAddQuestionBtn) {
                        newAddQuestionBtn.addEventListener('click', function() {
                            // Create new question outline container
                            const newQuestionHTML = `
                                <!-- Question Container with Outline -->
                                <div class="border-2 border-gray-300 rounded-lg p-4 mb-4 bg-white shadow-sm">
                                    <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <!-- Question and Type Selector Row -->
                                        <div class="flex items-start justify-between mb-3 gap-4">
                                            <textarea class="dark:text-white flex-1 text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                                      rows="3" 
                                                      placeholder="Tulis pertanyaan disini..."
                                                      style="min-height: 60px;"></textarea>
                                            <select class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white flex-shrink-0 input-type" 
                                                    name="tipe">
                                                <option value="">Tipe Jawaban</option>
                                                <option value="text">Short Answer</option>
                                                <option value="textarea">Paragraph</option>
                                                <option value="checkbox">Checkboxes</option>
                                                <option value="radio">Radio</option>
                                                <option value="select">Dropdown</option>
                                                <option value="file">File</option>
                                                <option value="date">Datepicker</option> 
                                            </select>
                                        </div>
                                        
                                        <!-- Description Row -->
                                        <div>
                                            <textarea class="dark:text-white w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                                      rows="2" 
                                                      placeholder="Tulis deskripsi disini..."
                                                      style="min-height: 50px;"></textarea>
                                        </div>
                                    </div>

                                    <!-- Hidden input for required state -->
                                    <input type="hidden" name="required" value="0" class="question-required">
                                    
                                    <!-- Answer Options Column -->
                                    <div class="mb-4">
                                        <div class="flex items-start gap-4">
                                            <!-- Answer Options Section -->
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Jawaban</label>
                                                <div class="personal-column">
                                                    <span class="text-xs font-semibold leading-tight text-slate-400">Pilih tipe jawaban terlebih dahulu</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Visualization Column -->
                                            <div class="w-48">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Visualisasi</label>
                                                <select name="visualisasi" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white w-full">
                                                    <option value="">Pilih Visualisasi</option>
                                                    <option value="bar">Bar Chart</option>
                                                    <option value="pie">Pie Chart</option>
                                                    <option value="">Tidak Ada</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Icons -->
                                    <div class="flex justify-end mt-4">
                                        <div class="flex space-x-2">
                                            <button type="button" class="icon-link duplicate-row p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors" data-tooltip="Duplicate" title="Duplicate">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                           
                                            <button type="button" class="icon-link delete-row p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" data-tooltip="Delete" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                             <button type="button" class="icon-link required-toggle flex items-center px-3 py-2 text-gray-600 hover:text-orange-600 border border-gray-300 rounded-lg transition-colors cursor-pointer" data-tooltip="Required" title="Toggle Required">
                                                <!-- Toggle Switch -->
                                                <div class="relative inline-block w-10 h-5 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out required-switch mr-2">
                                                    <!-- Toggle Dot -->
                                                    <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200 ease-in-out required-dot"></div>
                                                </div>
                                                <span class="text-xs font-medium">Required</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            // Find the add button container and insert the new question before it
                            const addButtonContainer = this.closest('.flex.justify-center.mt-4');
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = newQuestionHTML;
                            const newQuestionElement = tempDiv.firstElementChild;
                            
                            // Insert the new question container before the add button
                            addButtonContainer.parentNode.insertBefore(newQuestionElement, addButtonContainer);
                            
                            // Add event listeners to the new question container
                            addEventListenersToQuestionContainer(newQuestionElement);
                            
                            // Scroll to the new question
                            newQuestionElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        });
                    }
                    
                    // Add event listener to the new add-block button in the new section
                    const newAddBlockBtn = newSectionElement.querySelector('.add-block-btn');
                    if (newAddBlockBtn) {
                        addAddBlockListener(newAddBlockBtn);
                    }
                    
                    // Update section navigation dropdowns
                    setTimeout(() => {
                        updateSectionNavigationDropdowns();
                    }, 100);
                    
                    // Scroll to the new section
                    newSectionElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }
            
            // Function to add event listener to add-block buttons
            function addAddBlockListener(button) {
                button.addEventListener('click', function() {
                    // Get the current section number
                    const allSections = document.querySelectorAll('.inline-flex.items-center.px-3.py-1.rounded-full');
                    const sectionCount = allSections.length + 1;
                    
                    // Create new section HTML (same as above)
                    const newSectionHTML = `
                        <!-- start form -->
                        <div class="flex flex-wrap -mx-3">
                            <div class="flex-none w-full max-w-full px-3">
                                <div
                                    class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-transparent border-solid shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl bg-clip-border">
                                    <div
                                        class="p-6 pb-0 mb-0 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                                <i class="fas fa-layer-group mr-2 text-blue-600"></i>
                                                <span>Section ${sectionCount}</span>
                                            </div>

                                            <div class="flex space-x-2">
                                                <button type="button"
                                                    class="duplicate-block-btn inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-green-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                                    <i class="fas fa-copy mr-2"></i> Duplicate Blok
                                                </button>
                                                <button type="button"
                                                    class="delete-block-btn inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-red-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                                    <i class="fas fa-trash mr-2"></i> Hapus Blok
                                                </button>
                                                <button type="button"
                                                    class="add-block-btn inline-block px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85">
                                                    <i class="fas fa-plus mr-2"></i> Tambah Blok
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Block Name and Description -->
                                        <div class="mb-4 space-y-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Blok</label>
                                                <textarea class="dark:text-white w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                                          rows="2" 
                                                          placeholder="Tulis nama blok disini..."
                                                          style="min-height: 50px;"></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Blok</label>
                                                <textarea class="dark:text-white w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                                          rows="2" 
                                                          placeholder="Tulis deskripsi blok disini..."
                                                          style="min-height: 50px;"></textarea>
                                            </div>
                                        </div>

                                        <div class="mb-4 space-y-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Pertanyaan</label>
                                                
                                            </div>
                                        </div>

                                        <!-- Question Container with Outline -->
                                        <div class="border-2 border-gray-300 rounded-lg p-4 mb-4 bg-white shadow-sm">
                                            <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                                <!-- Question and Type Selector Row -->
                                                <div class="flex items-start justify-between mb-3 gap-4">
                                                    <textarea class="dark:text-white flex-1 text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                                              rows="3" 
                                                              placeholder="Tulis pertanyaan disini..."
                                                              style="min-height: 60px;"></textarea>
                                                    <select class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white flex-shrink-0 input-type" 
                                                            name="tipe">
                                                        <option value="">Tipe Jawaban</option>
                                                        <option value="text">Short Answer</option>
                                                        <option value="textarea">Paragraph</option>
                                                        <option value="checkbox">Checkboxes</option>
                                                        <option value="radio">Radio</option>
                                                        <option value="select">Dropdown</option>
                                                        <option value="file">File</option>
                                                        <option value="date">Datepicker</option> 
                                                    </select>
                                                </div>
                                                
                                                <!-- Description Row -->
                                                <div>
                                                    <textarea class="dark:text-white w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                                              rows="2" 
                                                              placeholder="Tulis deskripsi disini..."
                                                              style="min-height: 50px;"></textarea>
                                                </div>
                                            </div>

                                            <!-- Hidden input for required state -->
                                            <input type="hidden" name="required" value="0" class="question-required">
                                            
                                            <!-- Answer Options Column -->
                                            <div class="mb-4">
                                                <div class="flex items-start gap-4">
                                                    <!-- Answer Options Section -->
                                                    <div class="flex-1">
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Jawaban</label>
                                                        <div class="personal-column">
                                                            <span class="text-xs font-semibold leading-tight text-slate-400">Pilih tipe jawaban terlebih dahulu</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Visualization Column -->
                                                    <div class="w-48">
                                                        <label class="block text-sm font-medium text-gray-700 mb-2">Visualisasi</label>
                                                        <select name="visualisasi" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white w-full">
                                                            <option value="">Pilih Visualisasi</option>
                                                            <option value="bar">Bar Chart</option>
                                                            <option value="pie">Pie Chart</option>
                                                            <option value="">Tidak Ada</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Action Icons -->
                                            <div class="flex justify-end mt-4">
                                                <div class="flex space-x-2">
                                                    <button type="button" class="icon-link duplicate-row p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors" data-tooltip="Duplicate" title="Duplicate">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                   
                                                    <button type="button" class="icon-link delete-row p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" data-tooltip="Delete" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>

                                                     <button type="button" class="icon-link required-toggle flex items-center px-3 py-2 text-gray-600 hover:text-orange-600 border border-gray-300 rounded-lg transition-colors cursor-pointer" data-tooltip="Required" title="Toggle Required">
                                                        <!-- Toggle Switch -->
                                                        <div class="relative inline-block w-10 h-5 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out required-switch mr-2">
                                                            <!-- Toggle Dot -->
                                                            <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200 ease-in-out required-dot"></div>
                                                        </div>
                                                        <span class="text-xs font-medium">Required</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Add Question Button -->
                                        <div class="flex justify-center mt-4">
                                            <button type="button" class="add-question-btn inline-flex items-center px-4 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-sm tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85 hover:bg-blue-600">
                                                <i class="fas fa-plus mr-2"></i> Tambah Pertanyaan
                                            </button>
                                        </div>
                                    </div>
                                    

                                </div>

                                <!-- Section Navigation Settings -->
                                <div class="mt-4 mb-6">
                                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                        <div class="flex items-center mb-3">
                                            <i class="fas fa-route text-purple-600 mr-2"></i>
                                            <h4 class="text-sm font-medium text-purple-800">Pengaturan Navigasi Section</h4>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <label class="text-sm text-purple-700">Setelah Section \${sectionCount} selesai, lanjut ke:</label>
                                            <select class="section-navigation-select text-sm border border-purple-300 rounded px-3 py-2 bg-white" data-current-section="Section \${sectionCount}">
                                                <option value="">Lanjut ke section berikutnya</option>
                                                <option value="end">Akhiri survey</option>
                                            </select>
                                        </div>
                                        <p class="text-xs text-purple-600 mt-2">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Atur kemana responden akan diarahkan setelah menyelesaikan section ini
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end form -->
                    `;
                    
                    // Find the last form section and insert the new section after it
                    const lastFormSection = document.querySelector('.flex.flex-wrap.-mx-3:last-of-type');
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = newSectionHTML;
                    const newSectionElement = tempDiv.firstElementChild;
                    
                    // Insert the new section after the last form section
                    lastFormSection.parentNode.insertBefore(newSectionElement, lastFormSection.nextSibling);
                    
                    // Add event listeners to the new section
                    addEventListenersToSection(newSectionElement);
                    
                    // Add event listeners to question container in the new section
                    const questionContainer = newSectionElement.querySelector('.border-2.border-gray-300.rounded-lg.p-4.mb-4.bg-white.shadow-sm');
                    if (questionContainer) {
                        addEventListenersToQuestionContainer(questionContainer);
                    }
                    
                    // Add event listener to the new add-question button in the new section
                    const newAddQuestionBtn = newSectionElement.querySelector('.add-question-btn');
                    if (newAddQuestionBtn) {
                        newAddQuestionBtn.addEventListener('click', function() {
                            // Same add question functionality as above
                            const newQuestionHTML = `
                                <!-- Question Container with Outline -->
                                <div class="border-2 border-gray-300 rounded-lg p-4 mb-4 bg-white shadow-sm">
                                    <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg p-4">
                                        <!-- Question and Type Selector Row -->
                                        <div class="flex items-start justify-between mb-3 gap-4">
                                            <textarea class="dark:text-white flex-1 text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                                      rows="3" 
                                                      placeholder="Tulis pertanyaan disini..."
                                                      style="min-height: 60px;"></textarea>
                                            <select class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white flex-shrink-0 input-type" 
                                                    name="tipe">
                                                <option value="">Tipe Jawaban</option>
                                                <option value="text">Short Answer</option>
                                                <option value="textarea">Paragraph</option>
                                                <option value="checkbox">Checkboxes</option>
                                                <option value="radio">Radio</option>
                                                <option value="select">Dropdown</option>
                                                <option value="file">File</option>
                                                <option value="date">Datepicker</option> 
                                            </select>
                                        </div>
                                        
                                        <!-- Description Row -->
                                        <div>
                                            <textarea class="dark:text-white w-full text-sm leading-normal bg-white outline-none border border-gray-300 rounded px-3 py-2 resize-none" 
                                                      rows="2" 
                                                      placeholder="Tulis deskripsi disini..."
                                                      style="min-height: 50px;"></textarea>
                                        </div>
                                    </div>

                                    <!-- Hidden input for required state -->
                                    <input type="hidden" name="required" value="0" class="question-required">
                                    
                                    <!-- Answer Options Column -->
                                    <div class="mb-4">
                                        <div class="flex items-start gap-4">
                                            <!-- Answer Options Section -->
                                            <div class="flex-1">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan Jawaban</label>
                                                <div class="personal-column">
                                                    <span class="text-xs font-semibold leading-tight text-slate-400">Pilih tipe jawaban terlebih dahulu</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Visualization Column -->
                                            <div class="w-48">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Visualisasi</label>
                                                <select name="visualisasi" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-3 py-2 bg-white w-full">
                                                    <option value="">Pilih Visualisasi</option>
                                                    <option value="bar">Bar Chart</option>
                                                    <option value="pie">Pie Chart</option>
                                                    <option value="">Tidak Ada</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Action Icons -->
                                    <div class="flex justify-end mt-4">
                                        <div class="flex space-x-2">
                                            <button type="button" class="icon-link duplicate-row p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors" data-tooltip="Duplicate" title="Duplicate">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                           
                                            <button type="button" class="icon-link delete-row p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors" data-tooltip="Delete" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                             <button type="button" class="icon-link required-toggle flex items-center px-3 py-2 text-gray-600 hover:text-orange-600 border border-gray-300 rounded-lg transition-colors cursor-pointer" data-tooltip="Required" title="Toggle Required">
                                                <!-- Toggle Switch -->
                                                <div class="relative inline-block w-10 h-5 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out required-switch mr-2">
                                                    <!-- Toggle Dot -->
                                                    <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transform transition-transform duration-200 ease-in-out required-dot"></div>
                                                </div>
                                                <span class="text-xs font-medium">Required</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            const addButtonContainer = this.closest('.flex.justify-center.mt-4');
                            const tempDiv = document.createElement('div');
                            tempDiv.innerHTML = newQuestionHTML;
                            const newQuestionElement = tempDiv.firstElementChild;
                            
                            addButtonContainer.parentNode.insertBefore(newQuestionElement, addButtonContainer);
                            addEventListenersToQuestionContainer(newQuestionElement);
                            newQuestionElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        });
                    }
                    
                    // Add event listener to the new add-block button in the new section
                    const newAddBlockBtn = newSectionElement.querySelector('.add-block-btn');
                    if (newAddBlockBtn) {
                        addAddBlockListener(newAddBlockBtn);
                    }
                    
                    // Update all section navigation dropdowns after creating new section
                    setTimeout(() => {
                        updateSectionNumbering();
                        updateSectionNavigationDropdowns();
                        addNavigationSelectListeners();
                    }, 100);
                    
                    // Scroll to the new section
                    newSectionElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }
            
            // Handle delete button
            const deleteBtn = document.querySelector('.delete-row');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function() {
                    const allSections = document.querySelectorAll('.flex.flex-wrap.-mx-3');
                    const formSections = Array.from(allSections).filter(section => 
                        section.querySelector('textarea[placeholder*="pertanyaan"]')
                    );
                    
                    if (formSections.length > 1) {
                        const currentSection = this.closest('.flex.flex-wrap.-mx-3');
                        if (confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')) {
                            currentSection.remove();
                        }
                    } else {
                        alert('Minimal harus ada satu pertanyaan');
                    }
                });
            }
            
            // Function to add event listeners to a question container
            function addEventListenersToQuestionContainer(questionContainer) {
                // All event listeners (duplicate, delete, required, type change) are now 
                // handled by global event delegation - no individual listeners needed
                // This function is kept for backward compatibility but can be empty
            }
            
            // Function to add event listeners to a section (for backward compatibility)
            function addEventListenersToSection(section) {
                const selectElement = section.querySelector('select[name="tipe"]');
                if (selectElement) {
                    selectElement.addEventListener('change', function() {
                        const personalColumn = section.querySelector('.personal-column');
                        const value = this.value;
                        const valueText = this.options[this.selectedIndex].text;
                        
                        if (!personalColumn) return;
                        
                        if (["checkbox", "radio", "select"].includes(value)) {
                            personalColumn.innerHTML = `
                                <div id="optionContainer" class="space-y-2"></div>
                                <button type="button" onclick="addOptionWithBranching(this, '${value}')"
                                  class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 mt-2 bg-blue-100 hover:bg-blue-200 transition-colors">
                                  <i class="fas fa-plus mr-2"></i>Tambah Pilihan
                                </button>
                            `;
                        } else {
                            personalColumn.innerHTML = `<span class="text-xs font-semibold leading-tight text-slate-400">${valueText}</span>`;
                        }
                    });
                }
                
                // Add duplicate functionality
                const duplicateBtn = section.querySelector('.duplicate-row');
                if (duplicateBtn) {
                    duplicateBtn.addEventListener('click', function() {
                        const currentSection = this.closest('.flex.flex-wrap.-mx-3');
                        const clonedSection = currentSection.cloneNode(true);
                        
                        // Clear values and reset
                        const textareas = clonedSection.querySelectorAll('textarea');
                        textareas.forEach(textarea => {
                            if (textarea.placeholder.includes('pertanyaan')) {
                                textarea.value = '';
                            } else if (textarea.placeholder.includes('deskripsi')) {
                                textarea.value = '';
                            }
                        });
                        
                        const selects = clonedSection.querySelectorAll('select');
                        selects.forEach(select => select.selectedIndex = 0);
                        
                        const personalColumn = clonedSection.querySelector('.personal-column');
                        if (personalColumn) {
                            personalColumn.innerHTML = '<span class="text-xs font-semibold leading-tight text-slate-400">Pilih tipe jawaban terlebih dahulu</span>';
                        }
                        
                        addEventListenersToSection(clonedSection);
                        currentSection.parentNode.insertBefore(clonedSection, currentSection.nextSibling);
                        clonedSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    });
                }
                
                // Add required toggle functionality
                const requiredBtn = section.querySelector('.required-toggle');
                if (requiredBtn) {
                    requiredBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const hiddenInput = section.querySelector('.question-required');
                        const toggleSwitch = this.querySelector('.required-switch');
                        const toggleDot = this.querySelector('.required-dot');
                        const isRequired = hiddenInput.value === '1';
                        
                        if (isRequired) {
                            // Turn off required
                            hiddenInput.value = '0';
                            toggleSwitch.classList.remove('bg-orange-500');
                            toggleSwitch.classList.add('bg-gray-200');
                            toggleDot.classList.remove('translate-x-5');
                            toggleDot.classList.add('translate-x-0');
                            this.classList.remove('text-orange-600', 'border-orange-300');
                            this.classList.add('text-gray-600', 'border-gray-300');
                            this.title = 'Toggle Required';
                        } else {
                            // Turn on required
                            hiddenInput.value = '1';
                            toggleSwitch.classList.remove('bg-gray-200');
                            toggleSwitch.classList.add('bg-orange-500');
                            toggleDot.classList.remove('translate-x-0');
                            toggleDot.classList.add('translate-x-5');
                            this.classList.remove('text-gray-600', 'border-gray-300');
                            this.classList.add('text-orange-600', 'border-orange-300');
                            this.title = 'Question is Required';
                        }
                    });
                }
                
                // Add delete functionality
                const deleteBtn = section.querySelector('.delete-row');
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function() {
                        const allSections = document.querySelectorAll('.flex.flex-wrap.-mx-3');
                        const formSections = Array.from(allSections).filter(s => 
                            s.querySelector('textarea[placeholder*="pertanyaan"]')
                        );
                        
                        if (formSections.length > 1) {
                            if (confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')) {
                                section.remove();
                            }
                        } else {
                            alert('Minimal harus ada satu pertanyaan');
                        }
                    });
                }
                
                // Add duplicate block functionality
                const duplicateBlockBtn = section.querySelector('.duplicate-block-btn');
                if (duplicateBlockBtn) {
                    duplicateBlockBtn.addEventListener('click', function() {
                        duplicateBlock(this);
                    });
                }
                
                // Add delete block functionality
                const deleteBlockBtn = section.querySelector('.delete-block-btn');
                if (deleteBlockBtn) {
                    deleteBlockBtn.addEventListener('click', function() {
                        deleteBlock(this);
                    });
                }
            }
        });
        
        // Save Survey functionality
        document.getElementById('saveSurvey').addEventListener('click', function() {
            saveSurveyData();
        });
        
        function saveSurveyData() {
            const surveyName = document.getElementById('surveyName').value;
            const surveyDescription = document.getElementById('surveyDescription').value;
            
            if (!surveyName.trim()) {
                alert('Nama survey wajib diisi!');
                return;
            }
            
            // Collect all sections data
            const sections = [];
            const sectionBlocks = document.querySelectorAll('.section-block');
            
            sectionBlocks.forEach((block, blockIndex) => {
                const sectionName = block.querySelector('input[name="nama_blok"]').value || `Section ${blockIndex + 1}`;
                const sectionDescription = block.querySelector('textarea[name="deskripsi_blok"]').value || '';
                
                // Get navigation settings
                const navigationSelect = block.querySelector('select[name^="navigation_"]');
                const navigation = {
                    type: navigationSelect ? navigationSelect.value : 'next',
                    target_section: null
                };
                
                if (navigation.type === 'jump') {
                    const targetSelect = block.querySelector('select[name^="target_section_"]');
                    navigation.target_section = targetSelect ? parseInt(targetSelect.value) : null;
                }
                
                // Collect questions in this section
                const questions = [];
                const questionContainers = block.querySelectorAll('.border-2.border-gray-300.rounded-lg.p-4.mb-4.bg-white.shadow-sm');
                
                questionContainers.forEach((container, questionIndex) => {
                    const questionText = container.querySelector('textarea[name="pertanyaan"]').value;
                    if (!questionText.trim()) return; // Skip empty questions
                    
                    const questionData = {
                        question: questionText.trim(),
                        description: container.querySelector('textarea[name="deskripsi_pertanyaan"]').value || '',
                        type: container.querySelector('select[name="tipe"]').value || 'text',
                        required: container.querySelector('.question-required').value === '1',
                        visualization: container.querySelector('select[name="visualisasi"]').value || '',
                        options: []
                    };
                    
                    // Collect options for radio, checkbox, select
                    if (['radio', 'checkbox', 'select'].includes(questionData.type)) {
                        const optionInputs = container.querySelectorAll('.option-input');
                        optionInputs.forEach(input => {
                            if (input.value.trim()) {
                                questionData.options.push(input.value.trim());
                            }
                        });
                    }
                    
                    questions.push(questionData);
                });
                
                if (questions.length > 0) {
                    sections.push({
                        section_name: sectionName,
                        section_description: sectionDescription,
                        questions: questions,
                        navigation: navigation
                    });
                }
            });
            
            if (sections.length === 0) {
                alert('Minimal harus ada 1 section dengan 1 pertanyaan!');
                return;
            }
            
            // Prepare data for submission
            const formData = {
                survey_id: @if(isset($survey)) {{ $survey->id }} @else null @endif,
                survey_name: surveyName,
                survey_description: surveyDescription,
                sections: sections
            };
            
            // Show loading
            const saveBtn = document.getElementById('saveSurvey');
            const originalText = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';
            saveBtn.disabled = true;
            
            // Submit data
            fetch('{{ route("admin.survey.form_builder.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Survey berhasil disimpan!');
                    if (data.data && data.data.redirect_url) {
                        window.location.href = data.data.redirect_url;
                    } else {
                        window.location.href = '{{ route("admin.survey.index") }}';
                    }
                } else {
                    alert(data.message || 'Terjadi kesalahan saat menyimpan survey');
                    console.error('Save error:', data.errors || data.error);
                }
            })
            .catch(error => {
                console.error('Network error:', error);
                alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
            })
            .finally(() => {
                // Reset button
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            });
        }
        
        // Load existing data if in edit mode
        @if(isset($survey))
        function loadExistingData() {
            fetch('{{ route("admin.survey.form_builder.data", $survey->id) }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateFormBuilder(data.data);
                } else {
                    console.error('Failed to load survey data:', data.message);
                }
            })
            .catch(error => {
                console.error('Error loading survey data:', error);
            });
        }
        
        function populateFormBuilder(surveyData) {
            // Clear existing sections except first one
            const existingSections = document.querySelectorAll('.section-block');
            for (let i = 1; i < existingSections.length; i++) {
                existingSections[i].remove();
            }
            
            // Populate survey basic info is already filled from server-side
            
            // Populate sections
            surveyData.sections.forEach((section, sectionIndex) => {
                let sectionBlock;
                
                if (sectionIndex === 0) {
                    // Use first existing section
                    sectionBlock = document.querySelector('.section-block');
                } else {
                    // Add new section
                    addBlock();
                    sectionBlock = document.querySelectorAll('.section-block')[sectionIndex];
                }
                
                // Fill section info
                const sectionNameInput = sectionBlock.querySelector('input[name="nama_blok"]');
                const sectionDescInput = sectionBlock.querySelector('textarea[name="deskripsi_blok"]');
                
                if (sectionNameInput) sectionNameInput.value = section.name || '';
                if (sectionDescInput) sectionDescInput.value = section.description || '';
                
                // Clear existing questions except first one
                const existingQuestions = sectionBlock.querySelectorAll('.border-2.border-gray-300.rounded-lg.p-4.mb-4.bg-white.shadow-sm');
                for (let i = 1; i < existingQuestions.length; i++) {
                    existingQuestions[i].remove();
                }
                
                // Populate questions
                section.questions.forEach((question, questionIndex) => {
                    let questionContainer;
                    
                    if (questionIndex === 0) {
                        // Use first existing question
                        questionContainer = sectionBlock.querySelector('.border-2.border-gray-300.rounded-lg.p-4.mb-4.bg-white.shadow-sm');
                    } else {
                        // Add new question
                        const addQuestionBtn = sectionBlock.querySelector('.add-question-btn');
                        if (addQuestionBtn) {
                            addQuestionBtn.click();
                            const questions = sectionBlock.querySelectorAll('.border-2.border-gray-300.rounded-lg.p-4.mb-4.bg-white.shadow-sm');
                            questionContainer = questions[questionIndex];
                        }
                    }
                    
                    if (questionContainer) {
                        // Fill question data
                        const questionTextarea = questionContainer.querySelector('textarea[name="pertanyaan"]');
                        const descriptionTextarea = questionContainer.querySelector('textarea[name="deskripsi_pertanyaan"]');
                        const typeSelect = questionContainer.querySelector('select[name="tipe"]');
                        const visualizationSelect = questionContainer.querySelector('select[name="visualisasi"]');
                        const requiredInput = questionContainer.querySelector('.question-required');
                        
                        if (questionTextarea) questionTextarea.value = question.question || '';
                        if (descriptionTextarea) descriptionTextarea.value = question.description || '';
                        if (typeSelect) {
                            typeSelect.value = question.type || 'text';
                            // Trigger change event to show options if needed
                            typeSelect.dispatchEvent(new Event('change'));
                        }
                        if (visualizationSelect) visualizationSelect.value = question.visualization || '';
                        if (requiredInput) requiredInput.value = question.required ? '1' : '0';
                        
                        // Update required toggle UI
                        const requiredToggle = questionContainer.querySelector('.required-toggle');
                        if (requiredToggle && question.required) {
                            requiredToggle.click();
                        }
                        
                        // Add options for radio, checkbox, select
                        setTimeout(() => {
                            if (['radio', 'checkbox', 'select'].includes(question.type) && question.options) {
                                const optionContainer = questionContainer.querySelector('#optionContainer');
                                if (optionContainer) {
                                    // Clear existing options
                                    optionContainer.innerHTML = '';
                                    
                                    // Add saved options
                                    question.options.forEach((optionText, optionIndex) => {
                                        const optionDiv = document.createElement('div');
                                        optionDiv.className = 'flex items-center space-x-2';
                                        optionDiv.innerHTML = `
                                            <input type="text" class="option-input flex-1 px-2 py-1 border border-gray-300 rounded text-sm" 
                                                   placeholder="Pilihan ${optionIndex + 1}" value="${optionText}">
                                            <button type="button" onclick="removeOption(this)" 
                                                    class="text-red-500 hover:text-red-700 text-sm">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        `;
                                        optionContainer.appendChild(optionDiv);
                                    });
                                }
                            }
                        }, 100);
                    }
                });
                
                // Set navigation
                setTimeout(() => {
                    const navigationSelect = sectionBlock.querySelector('select[name^="navigation_"]');
                    if (navigationSelect && section.navigation) {
                        navigationSelect.value = section.navigation.type || 'next';
                        navigationSelect.dispatchEvent(new Event('change'));
                        
                        if (section.navigation.type === 'jump' && section.navigation.target_section_id) {
                            setTimeout(() => {
                                const targetSelect = sectionBlock.querySelector('select[name^="target_section_"]');
                                if (targetSelect) {
                                    targetSelect.value = section.navigation.target_section_id;
                                }
                            }, 100);
                        }
                    }
                }, 200);
            });
        }
        
        // Load data if in edit mode
        setTimeout(() => {
            loadExistingData();
        }, 500);
        @endif
    </script>
@endsection
