<script setup>
    import SubmitBtn from '@/components/atoms/buttons/forms/SubmitBtn.vue'
    import FormLabel from '@/components/atoms/forms/FormLabel.vue'
    import BaseFormField from '@/components/molecules/forms/BaseFormField.vue'

    defineProps({
        vModel: {
            type: Object,
            required: true
        }
    });
</script>

<template>
    <form>
        <BaseFormField labelName="Username" id="username" placeholder="Enter username" v-model="vModel.username" />
        <BaseFormField labelName="Image tokens" type="number" id="image_tokens" placeholder="Enter number of tokens" v-model="vModel.imageTokens" />

        <div class="mb-3">
            <FormLabel id="role" labelName="Role"/>
            <select class="form-select" id="role" name="role" required>
                <!-- foreach (UserRole::cases() as $case){ ?>
                    <option value="<?php echo $case->name; ?>"  echo (isset($viewModel) && $case->name == $viewModel->getRole()->value ? "selected" : "") ?>>
                         echo $case->name; ?>
                    </option>
                } ?>   -->
            </select>
        </div>

        <BaseFormField labelName="Password" type="password" id="password" placeholder="Enter password" v-model="vModel.password" />

        <SubmitBtn text="Create" />
    </form>
</template>