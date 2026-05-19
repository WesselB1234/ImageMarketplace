<script setup>
    const props = defineProps({
        image: {
            type: Object,
            required: true
        }
    });

    function getImageUrl(imageId) {
        return import.meta.env.VITE_BACK_END_URL + import.meta.env.VITE_USER_UPLOADED_IMAGES_URL + '/' + imageId + '.png'
    }

    function sanitize(str) {
        return str?.replace(/<[^>]*>?/gm, "") ?? "";
    }

    function formatPrice(price) {
        return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
</script>

<template>
  <div class="card h-100 image-card">
    <img class="card-img-top card-image-top" :src="getImageUrl(image.imageId)"
      :alt="sanitize(image.altText)"
    />

    <div class="card-body d-flex flex-column">
      <h5 class="card-title">
        {{ sanitize(image.name) }}
      </h5>

      <p>
        <span v-if="image.isModerated" class="text-danger">
          This image has been moderated
        </span>

        <template v-else-if="image.isOnSale && image.price !== null">
          <span class="font-weight-bold">Price:</span>
          {{ formatPrice(image.price) }} image tokens
        </template>

        <span v-else class="text-danger">
          This image is not for sale
        </span>
      </p>

      <RouterLink
        :to="`/images/details/${image.imageId}`"
        class="btn btn-primary w-100 mt-auto"
      >
        View details and actions
      </RouterLink>
    </div>
  </div>
</template>
