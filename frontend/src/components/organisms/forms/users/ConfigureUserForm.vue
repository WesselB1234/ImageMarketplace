<script setup>
    import SubmitBtn from '@/components/atoms/buttons/forms/SubmitBtn.vue'
    import FormLabel from '@/components/atoms/forms/FormLabel.vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'

    defineProps({
        vModel: {
            type: Object,
            required: true
        },
        isPasswordRequired: {
            type: Boolean,
            default: false
        }
    });
</script>

<template>
    <form>
        <BaseFormField labelName="Username" id="username" placeholder="Enter username" v-model="vModel.username" />
        <BaseFormField labelName="Image tokens" type="number" id="image_tokens" placeholder="Enter number of tokens" v-model="vModel.imageTokens" />

        <div class="mb-3">
            <FormLabel id="role" labelName="Role"/>
            <select v-model="vModel.role" class="custom-select" id="role" name="role" required>
                <option value="" disabled>Select a role</option>
                <option value="User">User</option> 
                <option value="Admin">Admin</option>
            </select>
        </div>

        <BaseFormField labelName="Password" type="password" id="password" placeholder="Enter password" v-model="vModel.password" :isRequired="isPasswordRequired" />

        <SubmitBtn text="Create" />
    </form>
</template>