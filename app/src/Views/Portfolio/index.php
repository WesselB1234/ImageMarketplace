<?php 
    $title = "Portfolio";
    
    require __DIR__."../../Partials/header.php"; 
?>

<main>
    <?php

        // Read the image file into a string
        $imagePath = "../public/assets/images/Core1NoBackground.png";
        $imageData = file_get_contents($imagePath);

        // Encode the binary data as Base64
        $imageString = base64_encode($imageData);

        // Output or use the string
        echo "<img src='data:image/jpeg;base64,". $imageString . "' />";
    ?>
</main>

<?php require __DIR__."../../Partials/footer.php"; ?>
