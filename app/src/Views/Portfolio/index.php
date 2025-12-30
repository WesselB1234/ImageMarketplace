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

    <div class="mt-4">
        <?php foreach ($viewModel as $image){ ?>
            <div class="card image-card">
                <img class="card-img-top" src="/assets/img/UserUploadedImages/<?php echo $image->imageId ?>.png" alt="<?php echo $image->altText?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $image->name;?></h5>
                    <?php if ($image->isOnSale == true && isset($image->price)){ ?> 
                        <p>Price: <?php echo $image->price ?> image tokens</p>
                    <?php } ?>
                    <a href="#" class="btn btn-primary w-100">View details and actions <?php echo $image->timeCreated->format('Y-m-d H:i:s');?></a>
                </div>
            </div>
        <?php }?>
    </div>
</main>

<?php require $partialsDir."/footer.php"; ?>
