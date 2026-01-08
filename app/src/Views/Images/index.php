<?php 
    $title = "Images on sale";
    $enabledNavLink = "Images";
    $partialsDir = __DIR__."../../Partials";
    
    require $partialsDir."/navbarHeader.php"; 
?>

<main class="container">

    <h1>Images on sale</h1>

    <?php require $partialsDir."/imagesDisplay.php" ?>
</main>

<?php require $partialsDir."/footer.php"; ?>
