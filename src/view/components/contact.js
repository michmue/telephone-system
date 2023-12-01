/** @type {HTMLDialogElement} */
const contactDialog = document.getElementById('editContactDialog');
const resetBtn = document.getElementById('resetBtn');
const submitBtn = document.getElementById('submitBtn');
const phoneNumberInput = document.getElementById('phoneNumberInput');
const contactNameInput = document.getElementById('contactNameInput');

resetBtn.onclick = function () {
    contactDialog.close()
};

/**
 * @param {string} phoneNumber
 * @param {string} name
 */
async function sendEditedContactToServer(phoneNumber, name) {
    fetch("/api/contact/edit",  {
        headers: { "Content-Type": "application/json" },
        method: "POST",
        body: JSON.stringify({"phoneNumber": phoneNumber, "name": name})
    }).then(() => location.reload());
}

/**
 * @param {string} phoneNumber
 * @param {string} name
 */
async function sendNewContactToServer(phoneNumber, name) {
    fetch("/api/contact/new",  {
        headers: { "Content-Type": "application/json" },
        method: "PATCH",
        body: JSON.stringify({"phoneNumber": phoneNumber, "name": name})
    }).then(() => location.reload());
}

/**
 * @param {string} phoneNumber
 * @param {string} name
 */
async function sendDeleteContactToServer(phoneNumber, name) {
    fetch("/api/contact/delete",  {
        headers: { "Content-Type": "application/json" },
        method: "PATCH",
        body: JSON.stringify({"phoneNumber": phoneNumber, "name": name})
    }).then( () => location.reload());
}

contactDialog.onsubmit = function () {
    let phoneNumber = phoneNumberInput.value;
    let name = contactNameInput.value;

    if (contactDialog.method === "POST")
        sendNewContactToServer(phoneNumber, name);

    if (contactDialog.method === "DELETE")
        sendDeleteContactToServer(phoneNumber, name);

    if (contactDialog.method === "PATCH")
        sendEditedContactToServer(phoneNumber, name);

    //clean form for next "addContact"
    contactNameInput.value = ""
}

/** @param {int} phoneNumber  */
function showAddContactDialog(phoneNumber) {
    phoneNumberInput.value = phoneNumber

    submitBtn.value = 'Speichern';
    submitBtn.style.color = 'black';
    contactNameInput.disabled = false;

    contactDialog.method = "POST";
    contactDialog.showModal();
}

/**
 * @param {string} phoneNumber
 * @param {string} name
 */
function showEditContactDialog(phoneNumber, name) {
    phoneNumberInput.value = phoneNumber;
    contactNameInput.value = name;

    submitBtn.value = 'Speichern';
    submitBtn.style.color = 'black';
    contactNameInput.disabled = false;

    contactDialog.method = "PATCH";
    contactDialog.showModal();
}

/**
 * @param {string} phoneNumber
 * @param {string} name
 */
function showDeleteContactDialog(phoneNumber, name) {
    phoneNumberInput.value = phoneNumber;
    contactNameInput.value = name;

    submitBtn.value = 'Löschen';
    submitBtn.style.color = 'red';
    contactNameInput.disabled = true;

    contactDialog.method = "DELETE";
    contactDialog.showModal();
}