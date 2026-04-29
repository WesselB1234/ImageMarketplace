<script setup>
    import { ref } from 'vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'
    import SuccessAlert from '@/components/atoms/errorHandling/SuccessAlert.vue'
    import ErrorAlert from '@/components/atoms/errorHandling/ErrorAlert.vue'
    import SubmitBtn from '@/components/atoms/buttons/forms/SubmitBtn.vue'

    const username = ref('')
    const currentErrorAlert = ref(null)
    const currentSuccessAlert = ref(null)
</script>

<template>
    <form @submit="handleLogin" enctype="multipart/form-data">
        <ErrorAlert ref="currentErrorAlert" />
        <SuccessAlert ref="currentSuccessAlert" />

        <BaseFormField labelName="Image name" id="name" name="name" placeholder="Enter image name" v-model="username"/>
        <BaseFormField labelName="Description" id="description" name="description" placeholder="Enter description" v-model="username"/>
        <BaseFormField labelName="Image" id="username" name="username" placeholder="Enter your username" v-model="username"/>
        <BaseFormField labelName="Alt text (shows if image is not able to load on the screen)" id="alt_text" name="alt_text" placeholder="Alt text" v-model="username"/>
            
        <!-- <div class="mb-3">
            <label for="name" class="form-label">Image name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter image name">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control" id="description" name="description" placeholder="Enter description">
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image file (extension must be .png)</label>
            <input type="file" id="image" class="form-control" name="image" accept="image/*" required>
        </div>

        <div class="mb-3">
            <label for="alt_text" class="form-label">Alt text (shows if image is not able to load on the screen)</label>
            <textarea class="form-control" id="alt_text" name="alt_text" rows="2"></textarea>
        </div> -->

        <SubmitBtn buttonText="Upload" />
  </form>
</template>