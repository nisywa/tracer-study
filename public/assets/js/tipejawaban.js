// first trigger add row button
document.querySelector(".add-row").click();

document.querySelectorAll(".add-row").forEach(button => {
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
        clonedRow.querySelectorAll("input, select, textarea").forEach((input, index) => {
            input.value = row.querySelectorAll("input, select, textarea")[index].value;
        });

        // Ensure checkboxes and radio buttons keep their checked state
        clonedRow.querySelectorAll("input[type='checkbox'], input[type='radio']").forEach((input, index) => {
            input.checked = row.querySelectorAll("input[type='checkbox'], input[type='radio']")[index].checked;
        });

        row.parentNode.insertBefore(clonedRow, row.nextElementSibling);
        addRowEvents(clonedRow);
    });
}

// Initialize event listeners for existing and future rows
document.querySelectorAll("tbody tr").forEach(addRowEvents);

function changeInputType(rowTable) {
    const selectedRow = rowTable.closest("tr");
    const select = selectedRow.querySelector(".input-type");
    const personalColumn = selectedRow.querySelector(".personal-column");

    const value = select.value;
    let inputElement = '';

    switch(value) {
        case 'option1': // Short Answer
            inputElement = '<input type="text" maxlength="200" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1" placeholder="Your Answer">';
            break;
        case 'option2': // Paragraph
            inputElement = '<textarea maxlength="10000" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 resize-none" placeholder="Your Answer" oninput="autoExpand(this)"></textarea>';
            break;
        case 'option3': // Rating
            inputElement = `
                <select id="starCount" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1" onchange="updateStars()">
                    ${Array.from({length: 8}, (_, i) => `<option value="${i+3}">${i+3}</option>`).join('')}
                </select>
                <div id="starsContainer" class="flex justify-center space-x-2 text-gray-500 mt-2"></div>
                <input type="hidden" id="ratingValue" value="0">
            `;
            setTimeout(updateStars, 100);
            break;
        case 'option5': // Checkboxes with dynamic input
            inputElement = `
                <div id="checkboxContainer"></div>
                <button type="button" onclick="addCheckboxOption(this)" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 mt-2">Add Option</button>
            `;
            setTimeout(addCheckboxOption, 100);
            break;
        case 'option6': // Multiple Choice with dynamic input
            inputElement = `
                <div id="radioContainer"></div>
                <button type="button" onclick="addRadioOption()" class="text-xs font-semibold leading-tight border border-gray-400 rounded px-2 py-1 mt-2">Add Option</button>
            `;
            setTimeout(addRadioOption, 100);
            break;
        default:
            inputElement = '<span class="text-xs font-semibold leading-tight text-slate-400">Pribadi</span>';
    }
    personalColumn.innerHTML = inputElement;
}

function updateStars() {
    const starCount = document.getElementById("starCount")?.value || 3;
    const starsContainer = document.getElementById("starsContainer");
    if (starsContainer) {
        starsContainer.innerHTML = '';
        for (let i = 1; i <= starCount; i++) {
            let star = document.createElement("span");
            star.innerHTML = "☆";
            star.classList.add("cursor-pointer", "text-2xl");
            star.onclick = function() { setRating(i); };
            starsContainer.appendChild(star);
        }
    }
}

function setRating(rating) {
    const stars = document.getElementById("starsContainer").children;
    for (let i = 0; i < stars.length; i++) {
        stars[i].innerHTML = i < rating ? "★" : "☆";
        stars[i].classList.toggle("text-yellow-500", i < rating);
    }
    document.getElementById("ratingValue").value = rating;
}

function addCheckboxOption(thisButton) {

    const container = thisButton?.closest("div").querySelector("#checkboxContainer");
    if (container) {
        const inputDiv = document.createElement("div");
        inputDiv.classList.add("flex", "items-center", "space-x-2", "mt-1");

        const checkbox = document.createElement("input");
        checkbox.type = "checkbox";

        const input = document.createElement("input");
        input.type = "text";
        input.placeholder = "Option";
        input.classList.add("text-xs", "border", "border-gray-400", "rounded", "px-2", "py-1");

        inputDiv.appendChild(checkbox);
        inputDiv.appendChild(input);
        container.appendChild(inputDiv);
    }
}

function addRadioOption() {
    const container = document.getElementById("radioContainer");
    if (container) {
        const inputDiv = document.createElement("div");
        inputDiv.classList.add("flex", "items-center", "space-x-2", "mt-1");

        const radio = document.createElement("input");
        radio.type = "radio";
        radio.name = "dynamicRadio";

        const input = document.createElement("input");
        input.type = "text";
        input.placeholder = "Option";
        input.classList.add("text-xs", "border", "border-gray-400", "rounded", "px-2", "py-1");

        inputDiv.appendChild(radio);
        inputDiv.appendChild(input);
        container.appendChild(inputDiv);
    }
}

function autoExpand(element) {
    element.style.height = "auto";
    element.style.height = (element.scrollHeight) + "px";
}


