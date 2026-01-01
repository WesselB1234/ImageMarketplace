window.addEventListener("load", function () {
    
    const userDeletionBtns = document.querySelectorAll(".user-deletion-btn");

    function deleteUserAjax(userDeletionBtn) {

        const userId = userDeletionBtn.dataset.userId;

        fetch("/users/api/delete", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ 
                id: userId 
            })
        })
        .then(async response => {

            const responseJson = await response.json();

            if (!response.ok) {
                throw new Error(responseJson.message);
            }

            return responseJson;
        })
        .then(responseJson => { 
            console.log(responseJson.userId);
            displaySuccessAlert("Successfully deleted user."); 
        })
        .catch(error => {
            displayErrorAlert(error.message);
        });
    }

    userDeletionBtns.forEach((userDeletionBtn) => {
        userDeletionBtn.addEventListener("click", function(){
            deleteUserAjax(userDeletionBtn);
        });
    });
});