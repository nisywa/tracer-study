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
  inputDiv.className = "flex items-center space-x-2";

  const input = document.createElement("input");
  input.type = "text";
  input.placeholder = "Option";
  input.className =
    "text-xs border border-gray-400 rounded px-2 py-1 flex-grow";

  const controls = document.createElement("div");
  controls.className = "flex space-x-1";

  const moveUpBtn = createButton("↑", () => {
    const prev = inputDiv.previousElementSibling;
    if (prev) container.insertBefore(inputDiv, prev);
  });

  const moveDownBtn = createButton("↓", () => {
    const next = inputDiv.nextElementSibling;
    if (next) container.insertBefore(next, inputDiv);
  });

  const deleteBtn = createButton("×", () => inputDiv.remove());
  deleteBtn.className += " text-red-500";

  controls.append(moveUpBtn, moveDownBtn, deleteBtn);
  inputDiv.append(input, controls);
  container.appendChild(inputDiv);
  input.focus();
}

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

  // Initialize existing rows
  document.querySelectorAll("tbody tr:not(#templateRow)").forEach((row) => {
    addRowEvents(row);
    initializeExistingRow(row);
  });

  surveyForm.onsubmit = function (e) {
    e.preventDefault();
    debugger;
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
    const rows = this.querySelectorAll("tbody tr");

    rows.forEach((row, index) => {
      const question = {
        question: row.querySelector('input[name="pertanyaan[]"]')?.value,
        description:
          row.querySelector('input[name="deskripsi[]"]')?.value || "",
        blok: row.querySelector('input[name="blok[]"]')?.value || "",
        type: row.querySelector('select[name="tipe[]"]')?.value,
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
    console.log(questions);

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

    // Duplicate row
    const duplicateBtn = row.querySelector(".duplicate-row");
    if (duplicateBtn) {
      duplicateBtn.addEventListener("click", function () {
        const clonedRow = row.cloneNode(true);

        // Preserve the selected type and options if any
        const originalType = row.querySelector('select[name="tipe[]"]').value;
        const clonedSelect = clonedRow.querySelector('select[name="tipe[]"]');
        clonedSelect.value = originalType;

        if (["checkbox", "radio", "select"].includes(originalType)) {
          const originalOptions = row.querySelectorAll(
            '.option-item input[type="text"]'
          );
          const clonedOptions = clonedRow.querySelectorAll(
            '.option-item input[type="text"]'
          );

          originalOptions.forEach((original, index) => {
            if (clonedOptions[index]) {
              clonedOptions[index].value = original.value;
            }
          });
        }

        row.parentNode.insertBefore(clonedRow, row.nextElementSibling);
        addRowEvents(clonedRow);
        initializeExistingRow(clonedRow);
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
  }
});
