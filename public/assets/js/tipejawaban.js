// Type change handler
function changeInputType(rowTable) {
  const selectedRow = rowTable.closest("tr");
  const select = selectedRow.querySelector(".input-type");
  const personalColumn = selectedRow.querySelector(".personal-column");

  if (!select || !personalColumn) return;

  const value = select.value;
  const valueText = select.options[select.selectedIndex].text;

  if (["checkbox", "radio", "select"].includes(value)) {
    personalColumn.innerHTML = `
      <div id="optionContainer" class="space-y-2"></div>
      <button type="button" onclick="addOption(this)"
        class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 mt-2">
        Add Option
      </button>
    `;
  } else {
    personalColumn.innerHTML = `<span class="text-xs font-semibold leading-tight text-slate-400">${valueText}</span>`;
  }
}

// Option management
function addOption(button) {
  const container = button.previousElementSibling;
  if (!container) return;

  const inputDiv = document.createElement("div");
  inputDiv.className = "flex items-center space-x-2 mb-2";

  const input = document.createElement("input");
  input.type = "text";
  input.placeholder = "Option";
  input.className =
    "text-xs border border-gray-400 rounded px-2 py-1 flex-grow";

  const controls = document.createElement("div");
  controls.className = "flex space-x-1";

  const moveUpBtn = createButton('<i class="fas fa-arrow-up"></i>', () => {
    const prev = inputDiv.previousElementSibling;
    if (prev) container.insertBefore(inputDiv, prev);
  });

  const moveDownBtn = createButton('<i class="fas fa-arrow-down"></i>', () => {
    const next = inputDiv.nextElementSibling;
    if (next) container.insertBefore(next, inputDiv);
  });

  const deleteBtn = createButton('<i class="fas fa-times"></i>', () =>
    inputDiv.remove()
  );
  deleteBtn.className += " text-red-500";

  controls.append(moveUpBtn, moveDownBtn, deleteBtn);
  inputDiv.append(input, controls);
  container.appendChild(inputDiv);
  input.focus();
}

// Create button element
function createButton(text, onClick) {
  const button = document.createElement("button");
  button.type = "button";
  button.innerHTML = text;
  button.className = "text-xs px-2 py-1 border rounded";
  button.onclick = onClick;
  return button;
}

document.addEventListener("DOMContentLoaded", function () {
  const surveyForm = document.getElementById("surveyForm");
  if (!surveyForm) return;
  // Add event listener for "Tambah Pertanyaan" button
  // Inside your DOMContentLoaded event listener, update the addRowBtn click handler:
  const addRowBtn = document.querySelector(".add-row");
  if (addRowBtn) {
    addRowBtn.addEventListener("click", addNewRow);
  }

  // Add bottom add button after the table
  const tableContainer = document.querySelector("#dynamicTable").parentElement;
  const buttonContainer = document.createElement("div");
  buttonContainer.className = "p-6 pt-0 text-center mt-8";
  const bottomAddBtn = document.createElement("button");
  bottomAddBtn.type = "button";
  bottomAddBtn.className =
    "add-row inline-block px-8 py-2 font-bold leading-normal text-center text-white align-middle transition-all ease-in bg-blue-500 border-0 rounded-lg shadow-md cursor-pointer text-xs tracking-tight-rem hover:shadow-xs hover:-translate-y-px active:opacity-85";
  bottomAddBtn.innerHTML = '<i class="fas fa-plus mr-2"></i> Tambah Pertanyaan';
  bottomAddBtn.addEventListener("click", addNewRow);
  buttonContainer.appendChild(bottomAddBtn);
  tableContainer.appendChild(buttonContainer);

  // Add the new row function
  function addNewRow() {
    const tbody = document.querySelector("#dynamicTable tbody");
    const templateRow = document.querySelector("#templateRow");

    if (tbody && templateRow) {
      const newRow = templateRow.cloneNode(true);
      newRow.removeAttribute("id");
      newRow.style.display = "";

      // Initialize select element
      const select = newRow.querySelector('select[name="tipe[]"]');
      if (select) {
        select.value = "";
        select.onchange = function () {
          changeInputType(this);
        };
      }

      tbody.appendChild(newRow);
      addRowEvents(newRow);

      // Scroll to the new row with smooth animation
      newRow.scrollIntoView({ behavior: "smooth", block: "center" });

      // Focus on the first input of the new row
      const firstInput = newRow.querySelector('input[name="pertanyaan[]"]');
      if (firstInput) {
        firstInput.focus();
      }
    }
  }
  // Initialize existing rows
  document.querySelectorAll("tbody tr:not(#templateRow)").forEach((row) => {
    addRowEvents(row);
    initializeExistingRow(row);
  });

  surveyForm.onsubmit = function (e) {
    e.preventDefault();

    // Validate required fields
    const invalidInputs = this.querySelectorAll(
      "input[required]:invalid, select[required]:invalid"
    );
    if (invalidInputs.length > 0) {
      invalidInputs[0].focus();
      invalidInputs[0].scrollIntoView({
        behavior: "smooth",
        block: "center",
      });
      return false;
    }

    // Collect form data
    const questions = [];
    const rows = this.querySelectorAll("tbody tr:not(#templateRow)");

    rows.forEach((row, index) => {
      // Check if this is a header row or actual question row
      if (row.classList.contains("bg-gray-100") || row.children.length <= 2) {
        return; // Skip header rows
      }

      // Get field values, supporting both input and textarea elements
      const questionInput = row.querySelector('textarea[name="pertanyaan[]"]');
      const descriptionInput = row.querySelector(
        'textarea[name="deskripsi[]"]'
      );
      const blokInput = row.querySelector('textarea[name="blok[]"]');

      const question = {
        question: questionInput?.value,
        description: descriptionInput?.value || "",
        blok: blokInput?.value || "",
        type: row.querySelector('select[name="tipe[]"]')?.value,
        visualisasi:
          row.querySelector('select[name="visualisasi[]"]')?.value || "",
        options: [],
        order: index + 1,
      };

      // Only collect valid questions
      if (question.question && question.type) {
        // Collect options for multiple choice questions
        if (["radio", "checkbox", "select"].includes(question.type)) {
          const optionContainer = row.querySelector("#optionContainer");
          if (optionContainer) {
            const optionInputs = optionContainer.querySelectorAll("input");
            optionInputs.forEach((input, optionIndex) => {
              if (input.value.trim()) {
                question.options.push({
                  text: input.value.trim(),
                  order: optionIndex + 1,
                });
              }
            });
          }
        }
        questions.push(question);
      }
    });

    console.log("Collected questions:", questions);

    // Get the CSRF token
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!token) {
      console.error("CSRF token not found");
      return false;
    }

    // Submit data using Fetch API
    fetch(this.action, {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": token,
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        questions: questions,
        _token: token,
        survey_id: document.querySelector('input[name="survey_id"]')?.value,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Data berhasil disimpan");
          window.location.reload();
        } else {
          alert(data.message || "Terjadi kesalahan saat menyimpan data");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Terjadi kesalahan saat menyimpan data");
      });

    return false;
  };

  function initializeExistingRow(row) {
    const type = row.querySelector('select[name="tipe[]"]').value;
    if (["checkbox", "radio", "select"].includes(type)) {
      const optionContainer = row.querySelector("#optionContainer");
      if (optionContainer) {
        // Add option controls
        optionContainer.querySelectorAll(".option-item").forEach((item) => {
          const controls = item.querySelector(".flex.space-x-1");

          // Move up option
          controls
            .querySelector(".move-option-up")
            .addEventListener("click", function () {
              const prev = item.previousElementSibling;
              if (prev) optionContainer.insertBefore(item, prev);
            });

          // Move down option
          controls
            .querySelector(".move-option-down")
            .addEventListener("click", function () {
              const next = item.nextElementSibling;
              if (next) optionContainer.insertBefore(next, item);
            });

          // Delete option
          controls
            .querySelector(".delete-option")
            .addEventListener("click", function () {
              item.remove();
            });
        });

        // Add new option button
        const addOptionBtn = document.createElement("button");
        addOptionBtn.type = "button";
        addOptionBtn.className =
          "text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 mt-2";
        addOptionBtn.textContent = "Add Option";
        addOptionBtn.onclick = () => addOption(addOptionBtn);
        optionContainer.parentNode.appendChild(addOptionBtn);
      }
    }
  }

  function addRowEvents(row) {
    // Delete row
    const deleteBtn = row.querySelector(".delete-row");
    if (deleteBtn) {
      deleteBtn.addEventListener("click", function () {
        const tbody = row.closest("tbody");
        if (tbody.querySelectorAll("tr").length > 1) {
          row.remove();
        } else {
          alert("Minimal harus ada satu pertanyaan");
        }
      });
    }

    // Move row up
    const moveUpBtn = row.querySelector(".move-up");
    if (moveUpBtn) {
      moveUpBtn.addEventListener("click", function () {
        const previousRow = row.previousElementSibling;
        if (previousRow && previousRow.id !== "templateRow") {
          row.parentNode.insertBefore(row, previousRow);
        }
      });
    }

    // Move row down
    const moveDownBtn = row.querySelector(".move-down");
    if (moveDownBtn) {
      moveDownBtn.addEventListener("click", function () {
        const nextRow = row.nextElementSibling;
        if (nextRow) {
          row.parentNode.insertBefore(nextRow, row);
        }
      });
    }
    // Duplicate row
    // Duplicate row
    const duplicateBtn = row.querySelector(".duplicate-row");
    if (duplicateBtn) {
      duplicateBtn.addEventListener("click", function () {
        // Clone the entire row
        const clonedRow = row.cloneNode(true);

        // Get the original type and options from the current state of the row
        const originalType = row.querySelector('select[name="tipe[]"]').value;
        const originalPersonalColumn = row.querySelector(".personal-column");
        const originalOptionsContainer =
          originalPersonalColumn.querySelector("#optionContainer");

        // Get ALL options, including newly added ones
        const originalOptions = originalOptionsContainer
          ? Array.from(
              originalOptionsContainer.querySelectorAll(
                '.flex.items-center.space-x-2.mb-2 input[type="text"]'
              )
            ).map((input) => input.value)
          : [];

        // Reset select change event
        const clonedSelect = clonedRow.querySelector('select[name="tipe[]"]');
        clonedSelect.value = originalType;
        clonedSelect.onchange = function () {
          changeInputType(this);
        };

        // Handle options for multiple choice types
        if (["checkbox", "radio", "select"].includes(originalType)) {
          const personalColumn = clonedRow.querySelector(".personal-column");

          // Reset the personal column
          personalColumn.innerHTML = `
        <div id="optionContainer" class="space-y-2"></div>
        <button type="button" onclick="addOption(this)"
          class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 mt-2">
          Add Option
        </button>
      `;

          const newOptionContainer =
            personalColumn.querySelector("#optionContainer");

          // Add all original options to the new container, including newly added ones
          originalOptions.forEach((optionValue) => {
            const inputDiv = document.createElement("div");
            inputDiv.className = "flex items-center space-x-2 mb-2 option-item";

            const input = document.createElement("input");
            input.type = "text";
            input.value = optionValue;
            input.className =
              "text-xs border border-gray-400 rounded px-2 py-1 flex-grow";

            const controls = document.createElement("div");
            controls.className = "flex space-x-1";

            // Add control buttons
            const moveUpBtn = createButton(
              '<i class="fas fa-arrow-up"></i>',
              () => {
                const prev = inputDiv.previousElementSibling;
                if (prev) newOptionContainer.insertBefore(inputDiv, prev);
              }
            );

            const moveDownBtn = createButton(
              '<i class="fas fa-arrow-down"></i>',
              () => {
                const next = inputDiv.nextElementSibling;
                if (next) newOptionContainer.insertBefore(next, inputDiv);
              }
            );

            const deleteBtn = createButton('<i class="fas fa-times"></i>', () =>
              inputDiv.remove()
            );
            deleteBtn.className += " text-red-500";

            controls.append(moveUpBtn, moveDownBtn, deleteBtn);
            inputDiv.append(input, controls);
            newOptionContainer.appendChild(inputDiv);
          });
        }

        // Insert cloned row after the current row
        row.parentNode.insertBefore(clonedRow, row.nextElementSibling);

        // Initialize events for the new row
        addRowEvents(clonedRow);

        // Scroll to the cloned row
        clonedRow.scrollIntoView({ behavior: "smooth", block: "center" });
      });
    }
  }
});
