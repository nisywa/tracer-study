document.addEventListener("DOMContentLoaded", function () {
  document.querySelector(".add-row").click();

  document.querySelectorAll(".add-row").forEach((button) => {
    button.addEventListener("click", addRow);
  });

  function addRow() {
    const tableBody = document.querySelector("tbody");
    const newRow = createRow();
    tableBody.appendChild(newRow);
    addRowEvents(newRow);
  }

  function createRow() {
    const newRow = document.createElement("tr");
    newRow.innerHTML = document.getElementById("templateRow").innerHTML;
    return newRow;
  }

  function addRowEvents(row) {
    row.querySelector(".delete-row").addEventListener("click", function () {
      row.remove();
    });

    row.querySelector(".duplicate-row").addEventListener("click", function () {
      const clonedRow = row.cloneNode(true);

      // Copy values from inputs in original row to cloned row
      clonedRow
        .querySelectorAll("input, select, textarea")
        .forEach((input, index) => {
          input.value = row.querySelectorAll("input, select, textarea")[
            index
          ].value;
        });

      // Ensure checkboxes and radio buttons keep their checked state
      clonedRow
        .querySelectorAll("input[type='checkbox'], input[type='radio']")
        .forEach((input, index) => {
          input.checked = row.querySelectorAll(
            "input[type='checkbox'], input[type='radio']"
          )[index].checked;
        });

      row.parentNode.insertBefore(clonedRow, row.nextElementSibling);
      addRowEvents(clonedRow);
    });

    row.querySelector(".move-up").addEventListener("click", function () {
      const previousRow = row.previousElementSibling;
      if (previousRow) {
        row.parentNode.insertBefore(row, previousRow);
      }
    });

    row.querySelector(".move-down").addEventListener("click", function () {
      const nextRow = row.nextElementSibling;
      if (nextRow) {
        row.parentNode.insertBefore(nextRow, row);
      }
    });
  }

  // Initialize event listeners for existing and future rows
  document.querySelectorAll("tbody tr").forEach(addRowEvents);

  function changeInputType(rowTable) {
    const selectedRow = rowTable.closest("tr");
    const select = selectedRow.querySelector(".input-type");
    const personalColumn = selectedRow.querySelector(".personal-column");

    const value = select.value;
    const valueText = select.options[select.selectedIndex].text;
    let inputElement = "";

    switch (value) {
      case "checkbox":
      case "radio":
      case "select":
        inputElement = `
                    <div id="optionContainer"></div>
                    <button type="button" onclick="addOption(this)"
                        class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 mt-2">
                        Add Option
                    </button>
                `;
        break;
      default:
        inputElement =
          '<span class="text-xs font-semibold leading-tight text-slate-400">' +
          valueText +
          "</span>";
    }
    personalColumn.innerHTML = inputElement;
  }

  function addOption(button) {
    const row = button.closest("tr");
    const container = row.querySelector("#optionContainer");
    if (container) {
      const inputDiv = document.createElement("div");
      inputDiv.classList.add("flex", "items-center", "space-x-2", "mt-1");

      const input = document.createElement("input");
      input.type = "text";
      input.placeholder = "Option";
      input.classList.add(
        "text-xs",
        "border",
        "border-gray-400",
        "rounded",
        "px-2",
        "py-1"
      );

      const moveUpBtn = document.createElement("button");
      moveUpBtn.innerHTML = "↑";
      moveUpBtn.classList.add("text-xs", "px-2", "py-1", "border", "rounded");
      moveUpBtn.onclick = function () {
        const prev = inputDiv.previousElementSibling;
        if (prev) container.insertBefore(inputDiv, prev);
      };

      const moveDownBtn = document.createElement("button");
      moveDownBtn.innerHTML = "↓";
      moveDownBtn.classList.add("text-xs", "px-2", "py-1", "border", "rounded");
      moveDownBtn.onclick = function () {
        const next = inputDiv.nextElementSibling;
        if (next) container.insertBefore(next, inputDiv);
      };

      inputDiv.appendChild(input);
      inputDiv.appendChild(moveUpBtn);
      inputDiv.appendChild(moveDownBtn);

      inputDiv.appendChild(input);
      container.appendChild(inputDiv);
    }
  }

  function autoExpand(element) {
    element.style.height = "auto";
    element.style.height = element.scrollHeight + "px";
  }
});
