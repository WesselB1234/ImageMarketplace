<script setup>
    import { useRoute } from 'vue-router'

    // 1. Initialize the hook
    const route = useRoute()

    // 2. Access the specific parameter
    console.log(route.params.id) 
</script>

<template>

    <h1 class="mb-4">Image details</h1>

    <!-- < 
        include $partialsDir."/successAlert.php"; 
        include $partialsDir."/errorAlert.php";
    ?> -->

    <!-- <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <img src="/assets/img/UserUploadedImages/<?php echo $viewModel->getImage()->getImageId(); ?>.png" class="card-img-top" alt="<?php echo StringFormatter::getStringWithoutHtmlElements($viewModel->getImage()->getAltText()); ?>">
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title mb-3">echo StringFormatter::getStringWithoutHtmlElements($viewModel->getImage()->getName()); ?></h3>
                    <p class="card-text text-muted">echo nl2br(StringFormatter::getStringWithoutHtmlElements($viewModel->getImage()->getDescription())); ?></p>

                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item">
                            <span class="font-weight-bold">Image ID:</span>echo $viewModel->getImage()->getImageId(); ?>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Owned by:</span>echo ($viewModel->getOwnerUser() !== null ? 
                                StringFormatter::getStringWithoutHtmlElements($viewModel->getOwnerUser()->getUsername()) 
                                : "Unknown") ?> echo ($viewModel->getImage()->getOwnerId() !== null ? "(User ID: ".$viewModel->getImage()->getOwnerId().")" : ""); ?>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Created by:</span>echo ($viewModel->getOwnerUser() !== null ? 
                                StringFormatter::getStringWithoutHtmlElements($viewModel->getCreatorUser()->getUsername()) 
                                : "Unknown") ?>  echo ($viewModel->getImage()->getCreatorId() !== null ? "(User ID: ".$viewModel->getImage()->getCreatorId().")" : ""); ?>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Time created:</span> echo $viewModel->getImage()->getTimeCreated()->format('Y-m-d H:i:s'); ?>
                        </li>
                        <li class="list-group-item">
                             if ($viewModel->getImage()->getIsModerated()) { ?> 
                                <span class="text-danger">This image has been moderated</span>
                             } 
                            else if ($viewModel->getImage()->getIsOnSale() && $viewModel->getImage()->getPrice() !== null) { ?> 
                                <span class="font-weight-bold">Price:</span>  echo StringFormatter::getDottedNumberStringFromNumber($viewModel->getImage()->getPrice()); ?> image tokens
                            < } 
                            else{ ?>
                                <span class="text-danger">This image is not for sale</span>
                             } ?>
                        </li>
                    </ul>
                    
                     if ($viewModel->getImage()->getIsModerated() === false && $viewModel->getImage()->getIsOnSale() && $viewModel->getImage()->getOwnerId() !== $this->loggedInUser->getUserId()){ ?>
                        <a href="/images/buy/<?php echo $viewModel->getImage()->getImageId(); ?>" class="btn btn-success w-100 mb-2">Buy</a>
                     }
                    if ($viewModel->getImage()->getIsModerated() === false && ($this->loggedInUser->getRole() === UserRole::Admin || $viewModel->getImage()->getOwnerId() === $this->loggedInUser->getUserId())){
                        if ($viewModel->getImage()->getIsOnSale() === false){?>
                            <a href="/images/sell/<?php echo $viewModel->getImage()->getImageId(); ?>" class="btn btn-danger w-100 mb-2">Sell</a>
                        < }
                        else{ ?>
                            <a href="/images/takeoffsale/<?php echo $viewModel->getImage()->getImageId(); ?>" class="btn btn-danger w-100 mb-2">Take off sale</a>
                        < }
                    }
                    if ($this->loggedInUser->getRole() === UserRole::Admin || $viewModel->getImage()->getOwnerId() === $this->loggedInUser->getUserId()){?>
                        <a href="/images/delete/<?php echo $viewModel->getImage()->getImageId(); ?>" class="btn btn-danger w-100 mb-2">Delete</a>
                    } 
                    if ($this->loggedInUser->getRole() === UserRole::Admin) {
                        if ($viewModel->getImage()->getIsModerated() === false){ ?>
                            <a href="/images/moderate/<?php echo $viewModel->getImage()->getImageId(); ?>/true" class="btn btn-warning w-100 mb-2">Moderate</a>
                        } 
                        else{ ?>
                            <a href="/images/moderate/<?php echo $viewModel->getImage()->getImageId(); ?>/false" class="btn btn-warning w-100 mb-2">Unmoderate</a>
                        } 
                    }?>
                </div>
            </div>
        </div>
    </div> -->
</template>