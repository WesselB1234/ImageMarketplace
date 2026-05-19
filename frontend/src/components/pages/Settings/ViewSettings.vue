<template>
<main class="container">

    <h1 class="mb-4">Settings</h1>

    <?php 
        include $partialsDir."/errorAlert.php";
        include $partialsDir."/successAlert.php"; 
    ?>

    <h4>Account actions</h4>
    <ul class="list-unstyled">
        <li>
            <a class="btn btn-danger" href="/settings/deleteaccount">Delete account</a>
        </li>
    </ul>
</template>