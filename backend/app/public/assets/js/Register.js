window.addEventListener("load", function () {
    
    const passwordInput = document.getElementById("password");
    const repeatPasswordInput = document.getElementById("repeat_password");
    const registerForm = document.getElementById("registerForm");

    function getIsRepeatedPasswordCorrect(e){

        if (repeatPasswordInput.value !== passwordInput.value) {
            displayErrorAlert("Repeated password is not equal to password");
            e.preventDefault();
        }
    }

    registerForm.addEventListener("submit", getIsRepeatedPasswordCorrect);
});