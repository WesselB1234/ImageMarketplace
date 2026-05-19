<template>

    <h1>Portfolio</h1>

    <?php 
        include $partialsDir."/errorAlert.php";
        include $partialsDir."/successAlert.php"; 
    ?>

    <a class="btn btn-success" href="images/upload">Upload an image</a>

    <?php require $partialsDir."/imagesDisplay.php" ?>
</template>
