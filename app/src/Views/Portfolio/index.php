<?php 
    $title = "Portfolio";
    $enabledNavLink = "Portfolio";
    $partialsDir = __DIR__."../../Partials";
    
    require $partialsDir."/navbarHeader.php"; 
?>

<main class="container">

    <h1>Portfolio</h1>

    <?php 
        include $partialsDir."/errorAlert.php";
        include $partialsDir."/successAlert.php"; 
    ?>

    <a class="btn btn-success" href="images/upload">Upload an image</a>
</main>

<?php require $partialsDir."/footer.php"; ?>
