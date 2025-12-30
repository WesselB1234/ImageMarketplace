<div class="mt-4 row row-cols-xs-1 row-cols-sm-2 row-cols-md-4">
    <?php foreach ($viewModel as $image) { ?>
        <div class="col mb-4">
            <div class="card h-100 image-card">
                <img class="card-img-top card-image-top" src="/assets/img/UserUploadedImages/<?php echo $image->imageId ?>.png" alt="<?php echo $image->altText ?>">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo $image->name; ?></h5>
                    <p>
                        <?php if ($image->isOnSale && $image->price !== null) { ?> 
                            <span class="font-weight-bold">Price:</span> <?php echo $image->price ?> image tokens
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
