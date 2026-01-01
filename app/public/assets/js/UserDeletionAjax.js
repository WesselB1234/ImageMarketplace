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
        .then(response => response.json())
        .then(message => { 
            console.log(message); 
        })
        .catch(err => {
            console.error(err);
        });
    }

    userDeletionBtns.forEach((userDeletionBtn) => {
        userDeletionBtn.addEventListener("click", function(){
            deleteUserAjax(userDeletionBtn);
        });
    });
});