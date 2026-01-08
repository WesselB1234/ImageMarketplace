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
                <img class="card-img-top card-image-top" src="/assets/img/UserUploadedImages/<?php echo $image->imageId ?>.png" alt="<?php echo htmlspecialchars($image->altText, ENT_QUOTES, 'UTF-8'); ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo htmlspecialchars($image->name, ENT_QUOTES, 'UTF-8');?></h5>
                    <p>
                        <?php if ($image->isModerated){ ?>
                            <span class="text-danger">This image has been moderated</span>
                        <?php }
                        else if ($image->isOnSale && $image->price !== null) { ?> 
                            <span class="font-weight-bold">Price:</span> <?php echo StringFormatter::getDottedNumberStringFromNumber($image->price); ?> image tokens
                        <?php } else{ ?>
                            <span class="text-danger">This image is not for sale</span>
                        <?php } ?>
                    </p>
                    <a href="/images/details/<?php echo $image->imageId; ?>" class="btn btn-primary w-100 mt-auto">View details and actions</a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
