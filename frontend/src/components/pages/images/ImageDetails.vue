<script setup>
    import axios from '@/utils/axios.js'
    import { getImageUrl, getPriceFormatted } from '@/utils/stringFormatter'
    import { onMounted, ref } from 'vue'
    import { useRoute } from 'vue-router'
    import { useAuthStore } from '@/stores/authStore'
    import { useErrorHandlingStore } from '@/stores/errorHandlingStore'
    import { getImageById } from '@/utils/imageLoader'

    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    import ReturnBtn from '@/components/atoms/buttons/ReturnBtn.vue'
    import router from '@/router'

    const authStore = useAuthStore()
    const errorHandlingStore = useErrorHandlingStore()

    const route = useRoute()
    const routeImageId = route.params.id
    const image = ref(null)
    const loggedInUser = authStore.decodedAuthToken.data
    const errorAlertRef = ref(null)
    const successAlertRef = ref(null)

    async function handleBuy() {
        try {
            const response = await axios.patch('/images/buy/' + image.value.imageId)
            //image.value.ownerId = response.data.ownerId
            errorHandlingStore.successMessage = 'Successfully bought this image.'
        }
        catch (ex){
            if (ex.response){
                errorHandlingStore.errorMessage = ex.response.data.message
            }
            else {
                useErrorHandlingStore.errorMessage = ex.message
            }
        }
    }

    async function handleTakeOffSale() {
        try {
            const response = await axios.patch('/images/take-off-sale/' + image.value.imageId)
            image.value.isOnSale = response.data.isOnSale
            image.value.price = response.data.price
            errorHandlingStore.successMessage = 'Successfully taken this image offsale.'
        }
        catch (ex){
            if (ex.response){
                errorHandlingStore.errorMessage = ex.response.data.message
            }
            else {
                useErrorHandlingStore.errorMessage = ex.message
            }
        }
    }

    async function handleDelete() {
        console.log('/images/delete/' + image.value.imageId)

        try {
            await axios.delete('/images/take-off-sale/' + image.value.imageId)
            //image.value.ownerId = response.data.ownerId
            successAlertRef.value.shutdown()
            errorHandlingStore.successMessage = 'Successfully deleted this image'
            router.push('/')
        }
        catch (ex){
            if (ex.response){
                errorHandlingStore.errorMessage = ex.response.data.message
            }
            else {
                useErrorHandlingStore.errorMessage = ex.message
            }
        }
    }

    async function handleModerateRequest(isModerate) {
        try {
            const response = await axios.patch('/images/moderate/' + image.value.imageId, {
                "isModerate": isModerate 
            })
            image.value.isModerated = response.data.isModerated
            errorHandlingStore.successMessage = 'Successfully ' + (isModerate ? 'moderated' : 'unmoderated') + ' this image.'
        }
        catch (ex){
            if (ex.response){
                errorHandlingStore.errorMessage = ex.response.data.message
            }
            else {
                useErrorHandlingStore.errorMessage = ex.message
            }
        }
    }

    async function handleModerate() {
        handleModerateRequest(true)
    }

    async function handleUnModerate() {
        handleModerateRequest(false)
    }

    onMounted(async () => {
        image.value = await getImageById(routeImageId, errorAlertRef)
    })
</script>

<template>
    <h1 class="mb-4">Image details</h1>
    <ReturnBtn to="/portfolio" text="Return back to portfolio" />
    <ErrorAlert ref="errorAlertRef"/>
    <SuccessAlert ref="successAlertRef" />

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
                                {{' ' +  image?.owner.username + ' (User ID: ' + image?.ownerId + ')' }}
                            </span>
                            <span v-else>
                                Unknown
                            </span>
                        </li>
                        <li class="list-group-item">
                            <span class="font-weight-bold">Created by:</span>
                            <span v-if="image?.creator !== null">
                                {{' ' + image?.creator.username + ' (User ID: ' + image?.creatorId + ')' }}
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
                    
                    <button v-if="image?.isModerated === false && image?.isOnSale === true && image?.ownerId !== loggedInUser.userId" @click="handleBuy()" class="btn btn-success w-100 mb-2">Buy</button>
                    
                    <template v-if="image?.isModerated === false && loggedInUser.role === 'Admin' || image?.ownerId === loggedInUser.userId">
                        <RouterLink v-if="image?.isOnSale === false" :to="'/images/sell/' + image?.imageId" class="btn btn-danger w-100 mb-2">Sell</RouterLink> 
                        <button v-else @click="handleTakeOffSale()" class="btn btn-danger w-100 mb-2">Take off sale</button>
                    </template>

                    <button v-if="loggedInUser.role === 'Admin' || image?.ownerId === loggedInUser.userId" @click="handleDelete()" class="btn btn-danger w-100 mb-2">Delete</button>
                     
                    <template v-if="loggedInUser.role === 'Admin'">
                        <button v-if="image?.isModerated === false" @click="handleModerate()" class="btn btn-warning w-100 mb-2">Moderate</button>
                        <button v-else @click="handleUnModerate()" class="btn btn-warning w-100 mb-2">Unmoderate</button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>