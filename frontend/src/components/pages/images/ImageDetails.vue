<script setup>
    import axios from '@/utils/axios.js'
    import { getImageUrl, getPriceFormatted } from '@/utils/stringFormatter'
    import { onMounted, ref } from 'vue'
    import { useRoute } from 'vue-router'
    
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    import { useAuthStore } from '@/stores/authStore'

    const authStore = useAuthStore()

    const route = useRoute()
    const image = ref(null)
    const loggedInUser = authStore.decodedAuthToken.data

    onMounted(async () => {
        try {
            image.value = (await axios.get('/images/' + route.params.id)).data
            console.log(image.value)
        }
        catch (ex){
            if (ex.response){
                errorHandlingStore.errorMessage = ex.response.data.message
            }
        }
    })
</script>

<template>
    <h1 class="mb-4">Image details</h1>

    <ErrorAlert />
    <SuccessAlert />

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card">
                <img :src="getImageUrl(image?.imageId)" class="card-img-top" :alt="image?.altText">
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title mb-3">{{ image?.name }}</h3>
                    <p class="card-text text-muted">{{ image?.description }}</p>

                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item">
                            <span class="font-weight-bold">Image ID:</span> {{ image?.imageId }}
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Owned by:</span>
                            <span v-if="image?.owner !== null">
                                {{ image?.owner.username + ' (User ID: ' + image?.ownerId + ')' }}
                            </span>
                            <span v-else>
                                Unknown
                            </span>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Created by:</span>
                            <span v-if="image?.creator !== null">
                                {{ image?.creator.username + ' (User ID: ' + image?.creatorId + ')' }}
                            </span>
                            <span v-else>
                                Unknown
                            </span>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Time created:</span> {{ image?.timeCreated }}
                        </li>
                        <li class="list-group-item">
                            <span v-if="image?.isModerated === true" class="text-danger">This image has been moderated</span>
                            <template v-else-if="image?.isOnSale && image?.price !== null">
                                <span class="font-weight-bold">Price:</span> {{ getPriceFormatted(image?.price) }}
                            </template>
                            <span v-else class="text-danger">This image is not for sale</span>
                        </li>
                    </ul>
                    
                    <a v-if="image?.isModerated === false && image?.isOnSale === true && image?.ownerId !== loggedInUser.userId" :href="'/images/buy/' + image?.imageId" class="btn btn-success w-100 mb-2">Buy</a>
                    
                    <template v-if="image?.isModerated === false && loggedInUser.role === 'Admin' || image?.ownerId === loggedInUser.userId">
                        <a v-if="image?.isOnSale === false" :href="'/images/sell/' + image?.imageId" class="btn btn-danger w-100 mb-2">Sell</a> 
                        <a v-else :href="'/images/takeoffsale/' + image?.imageId" class="btn btn-danger w-100 mb-2">Take off sale</a>
                    </template>

                    <a v-if="loggedInUser.role === 'Admin' || image?.ownerId === loggedInUser.userId" :href="'/images/delete/' + image?.imageId" class="btn btn-danger w-100 mb-2">Delete</a>
                     
                    <template v-if="loggedInUser.role === 'Admin'">
                        <a v-if="image?.isModerated === false" :href="'/images/moderate/' + image?.imageId + '/true'" class="btn btn-warning w-100 mb-2">Moderate</a>
                        <a v-else :href="'/images/moderate/' + image?.imageId + '/false'" class="btn btn-warning w-100 mb-2">Unmoderate</a>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>