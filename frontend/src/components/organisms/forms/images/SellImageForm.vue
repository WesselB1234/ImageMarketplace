<script setup>
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
</script>

<template>
    <form class="mt-4" action="/images/sell/<?php echo $viewModel->getImage()->getImageId(); ?>" method="post">
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" placeholder="Enter selling price" required>
        </div>

        <button type="submit" class="btn btn-danger">Sell</button>
    </form>
</template>