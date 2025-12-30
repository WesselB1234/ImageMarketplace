<?php 
    $title = "Image details";
    
    require __DIR__."../../Partials/navbarHeader.php"; 
?>

<main class="container">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <img src="/assets/img/UserUploadedImages/<?php echo $viewModel->image->imageId; ?>.png" class="card-img-top" alt="<?php echo htmlspecialchars($viewModel->image->altText); ?>">
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title mb-3"><?php echo htmlspecialchars($viewModel->image->name); ?></h3>
                    <p class="card-text text-muted"><?php echo nl2br(htmlspecialchars($viewModel->image->description)); ?></p>

                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item">
                            <span class="font-weight-bold">Image ID:</span> <?php echo $viewModel->image->imageId; ?>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Owned by:</span> <?php echo ($viewModel->ownerUser !== null ? $viewModel->ownerUser->username : "Unknown") ?> <?php echo ($viewModel->image->ownerId !== null ? "(User ID: ".$viewModel->image->ownerId.")" : ""); ?>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Created by:</span> <?php echo ($viewModel->creatorUser !== null ? $viewModel->creatorUser->username : "Unknown") ?> <?php echo ($viewModel->image->creatorId !== null ? "(User ID: ".$viewModel->image->creatorId.")" : ""); ?>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Uploaded:</span> <?php echo $viewModel->image->timeCreated->format('Y-m-d H:i:s'); ?>
                        </li>
                        <li class="list-group-item">
                            <?php if ($viewModel->image->isOnSale && $viewModel->image->price !== null) { ?> 
                                <span class="font-weight-bold">Price:</span> <?php echo $viewModel->image->price ?> image tokens
                            <?php } else{ ?>
                                <span class="text-danger">This image is not for sale</span>
                            <?php } ?>
                        </li>
                    </ul>

                    <a href="#" class="btn btn-success w-100 mb-2">Buy</a>
                    <a href="#" class="btn btn-danger w-100 mb-2">Sell</a>
                    <a href="#" class="btn btn-danger w-100 mb-2">Delete</a>
                    <a href="#" class="btn btn-warning w-100 mb-2">Moderate</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require __DIR__."../../Partials/footer.php"; ?>