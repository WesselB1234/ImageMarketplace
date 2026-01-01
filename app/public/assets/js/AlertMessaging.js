// WHY do it like this? Its because of public function scopes
var successAlert = null;
var errorAlert = null;
var errorMessageHolder = null;

function displayErrorAlert(messageStr){

    if (errorAlert === null){
        throw new Error("Error alert container doesn't exist yet.");
    }
        
    if (errorMessageHolder === null){
        throw new Error("error message holder doesn't exist yet.");
    }

    errorAlert.classList.remove("d-none");
    errorMessageHolder.innerText = messageStr;
}

function displaySuccessAlert(messageStr){

    if (successAlert === null){
        throw new Error("Success alert container doesn't exist yet.");
    }

    successAlert.classList.remove("d-none");
    successAlert.innerText = messageStr;
}

window.addEventListener("load", function () {
    successAlert = document.getElementById("successAlert");
    errorAlert = document.getElementById("errorAlert");
    errorMessageHolder = document.getElementById("errorMessageHolder");
});