<?php 
    use App\Models\Helpers\StringFormatter;
    use App\Models\Enums\UserRole;

    $title = "Image details";
    $partialsDir = __DIR__."../../Partials";
    require $partialsDir."/navbarHeader.php"; 
?>

<main class="container">

    <h1 class="mb-4">Image details</h1>

    <?php 
        include $partialsDir."/successAlert.php"; 
        include $partialsDir."/errorAlert.php";
    ?>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <img src="/assets/img/UserUploadedImages/<?php echo $viewModel->getImage()->getImageId(); ?>.png" class="card-img-top" alt="<?php echo htmlspecialchars($viewModel->getImage()->getAltText(), ENT_QUOTES, "UTF-8"); ?>">
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title mb-3"><?php echo htmlspecialchars($viewModel->getImage()->getName(), ENT_QUOTES, "UTF-8"); ?></h3>
                    <p class="card-text text-muted"><?php echo nl2br(htmlspecialchars($viewModel->getImage()->getDescription(), ENT_QUOTES, "UTF-8")); ?></p>

                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item">
                            <span class="font-weight-bold">Image ID:</span> <?php echo $viewModel->getImage()->getImageId(); ?>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Owned by:</span> <?php echo ($viewModel->getOwnerUser() !== null ? $viewModel->getOwnerUser()->getUsername() : "Unknown") ?> <?php echo ($viewModel->getImage()->getOwnerId() !== null ? "(User ID: ".$viewModel->getImage()->getOwnerId().")" : ""); ?>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Created by:</span> <?php echo ($viewModel->getCreatorUser() !== null ? $viewModel->getCreatorUser()->getUsername() : "Unknown") ?> <?php echo ($viewModel->getImage()->getCreatorId() !== null ? "(User ID: ".$viewModel->getImage()->getCreatorId().")" : ""); ?>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Time created:</span> <?php echo $viewModel->getImage()->getTimeCreated()->format('Y-m-d H:i:s'); ?>
                        </li>
                        <li class="list-group-item">
                            <?php if ($viewModel->getImage()->getIsModerated()) { ?> 
                                <span class="text-danger">This image has been moderated</span>
                            <?php } 
                            else if ($viewModel->getImage()->getIsOnSale() && $viewModel->getImage()->getPrice() !== null) { ?> 
                                <span class="font-weight-bold">Price:</span> <?php echo StringFormatter::getDottedNumberStringFromNumber($viewModel->getImage()->getPrice()); ?> image tokens
                            <?php } 
                            else{ ?>
                                <span class="text-danger">This image is not for sale</span>
                            <?php } ?>
                        </li>
                    </ul>
                    
                    <?php if ($viewModel->getImage()->getIsModerated() === false && $viewModel->getImage()->getIsOnSale() && $viewModel->getImage()->getOwnerId() !== $_SESSION["user"]->getUserId()){ ?>
                        <a href="/images/buy/<?php echo $viewModel->getImage()->getImageId(); ?>" class="btn btn-success w-100 mb-2">Buy</a>
                    <?php }
                    if ($viewModel->getImage()->getIsModerated() === false && ($_SESSION["user"]->getRole() === UserRole::Admin || $viewModel->getImage()->getOwnerId() === $_SESSION["user"]->getUserId())){
                        if ($viewModel->getImage()->getIsOnSale() === false){?>
                            <a href="/images/sell/<?php echo $viewModel->getImage()->getImageId(); ?>" class="btn btn-danger w-100 mb-2">Sell</a>
                        <?php }
                        else{ ?>
                            <a href="/images/takeoffsale/<?php echo $viewModel->getImage()->getImageId(); ?>" class="btn btn-danger w-100 mb-2">Take off sale</a>
                        <?php }
                    }
                    if ($_SESSION["user"]->getRole() === UserRole::Admin || $viewModel->getImage()->getOwnerId() === $_SESSION["user"]->getUserId()){?>
                        <a href="/images/delete/<?php echo $viewModel->getImage()->getImageId(); ?>" class="btn btn-danger w-100 mb-2">Delete</a>
                    <?php } 
                    if ($_SESSION["user"]->getRole() === UserRole::Admin) {
                        if ($viewModel->getImage()->getIsModerated() === false){ ?>
                            <a href="/images/moderate/<?php echo $viewModel->getImage()->getImageId(); ?>/true" class="btn btn-warning w-100 mb-2">Moderate</a>
                        <?php } 
                        else{ ?>
                            <a href="/images/moderate/<?php echo $viewModel->getImage()->getImageId(); ?>/false" class="btn btn-warning w-100 mb-2">Unmoderate</a>
                       <?php } 
                    }?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require $partialsDir."/footer.php"; ?>
