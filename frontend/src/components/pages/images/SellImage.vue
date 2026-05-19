<template>

    <!-- <h1>Sell image: echo StringFormatter::getStringWithoutHtmlElements($viewModel->getImage()->getName())?> (Image ID: echo $viewModel->getImage()->getImageId(); ?>)</h1> -->

    <a href="/images/details/<?php echo $viewModel->getImage()->getImageId(); ?>" class="btn btn-secondary">Return back to image details page</a>

    <form class="mt-4" action="/images/sell/<?php echo $viewModel->getImage()->getImageId(); ?>" method="post">
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="Enter selling price" required>
        </div>

        <button type="submit" class="btn btn-danger">Sell</button>
    </form>
</template>