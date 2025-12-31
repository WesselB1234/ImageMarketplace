<?php 
    use App\Models\Helpers\StringFormatter;
    use App\Models\Enums\UserRole;

    $title = "Image details";
    $partialsDir = __DIR__."../../Partials";
    require $partialsDir."/navbarHeader.php"; 
?>

<main class="container">

    <h1 class="mb-4">Image details</h1>

    <?php include $partialsDir."/successAlert.php"; ?>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <img src="/assets/img/UserUploadedImages/<?php echo $viewModel->image->imageId; ?>.png" class="card-img-top" alt="<?php echo htmlspecialchars($viewModel->image->altText, ENT_QUOTES, "UTF-8"); ?>">
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title mb-3"><?php echo htmlspecialchars($viewModel->image->name, ENT_QUOTES, "UTF-8"); ?></h3>
                    <p class="card-text text-muted"><?php echo nl2br(htmlspecialchars($viewModel->image->description, ENT_QUOTES, "UTF-8")); ?></p>

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
                            <span class="font-weight-bold">Time created:</span> <?php echo $viewModel->image->timeCreated->format('Y-m-d H:i:s'); ?>
                        </li>
                        <li class="list-group-item">
                            <?php if ($viewModel->image->isModerated) { ?> 
                                <span class="text-danger">This image has been moderated</span>
                            <?php } 
                            else if ($viewModel->image->isOnSale && $viewModel->image->price !== null) { ?> 
                                <span class="font-weight-bold">Price:</span> <?php echo StringFormatter::getDottedNumberStringFromNumber($viewModel->image->price); ?> image tokens
                            <?php } 
                            else{ ?>
                                <span class="text-danger">This image is not for sale</span>
                            <?php } ?>
                        </li>
                    </ul>
                    
                    <?php if ($viewModel->image->isModerated === false && $viewModel->image->isOnSale && $viewModel->image->ownerId !== $_SESSION["user"]->userId){ ?>
                        <a href="/images/buy/<?php echo $viewModel->image->imageId; ?>" class="btn btn-success w-100 mb-2">Buy</a>
                    <?php }
                    if ($viewModel->image->isModerated === false && ($_SESSION["user"]->role === UserRole::Admin || $viewModel->image->ownerId === $_SESSION["user"]->userId)){
                        if ($viewModel->image->isOnSale === false){?>
                            <a href="/images/sell/<?php echo $viewModel->image->imageId; ?>" class="btn btn-danger w-100 mb-2">Sell</a>
                        <?php }
                        else{ ?>
                            <a href="images/takeoffsale/<?php echo $viewModel->image->imageId; ?>" class="btn btn-danger w-100 mb-2">Take off sale</a>
                        <?php }
                    }
                    if ($_SESSION["user"]->role === UserRole::Admin || $viewModel->image->ownerId === $_SESSION["user"]->userId){?>
                        <a href="/images/delete/<?php echo $viewModel->image->imageId; ?>" class="btn btn-danger w-100 mb-2">Delete</a>
                    <?php } 
                    if ($_SESSION["user"]->role === UserRole::Admin) {
                        if ($viewModel->image->isModerated === false){ ?>
                            <a href="/images/moderate/<?php echo $viewModel->image->imageId; ?>/true" class="btn btn-warning w-100 mb-2">Moderate</a>
                        <?php } 
                        else{ ?>
                            <a href="/images/moderate/<?php echo $viewModel->image->imageId; ?>/false" class="btn btn-warning w-100 mb-2">Unmoderate</a>
                       <?php } 
                    }?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require $partialsDir."/footer.php"; ?>