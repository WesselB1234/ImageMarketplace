<?php
    use App\Models\Helpers\StringFormatter;
?>

<div class="mt-4 row row-cols-sm-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4">
    <?php if (count($viewModel) === 0) {?>
        <span class="font-weight-bold">No images found</span>
    <?php }
    foreach ($viewModel as $image) { ?>
        <div class="col mb-4">
            <div class="card h-100 image-card">
                <img class="card-img-top card-image-top" src="/assets/img/UserUploadedImages/<?php echo $image->getImageId() ?>.png" 
                    alt="<?php echo htmlspecialchars($image->getAltText(), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo htmlspecialchars($image->getName(), ENT_QUOTES, 'UTF-8');?></h5>
                    <p>
                        <?php if ($image->getIsModerated()){ ?>
                            <span class="text-danger">This image has been moderated</span>
                        <?php }
                        else if ($image->getIsOnSale() && $image->getPrice() !== null) { ?> 
                            <span class="font-weight-bold">Price:</span> <?php echo StringFormatter::getDottedNumberStringFromNumber($image->getPrice()); ?> image tokens
                        <?php } 
                        else{ ?>
                            <span class="text-danger">This image is not for sale</span>
                        <?php } ?>
                    </p>
                    <a href="/images/details/<?php echo $image->getImageId(); ?>" class="btn btn-primary w-100 mt-auto">View details and actions</a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
