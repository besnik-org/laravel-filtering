<template>
  <div>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <p class="text-2xl">Besnik Inertia Crud️</p>
          </div>

          <form class="pb-6" @submit.prevent="submit">
            <div class="form-container p-5">
              <div class="flex">
                <div class="w-3/5 p-2 mt-4">
                  <InputLabel value="Crud Name"/>

                  <TextInput
                      v-model="crud.name"
                      autocomplete="current-password"
                      class="mt-1 block w-full"
                      required
                      type="text"
                  />

                </div>
                <div class="w-1/5 p-2 mt-4">
                  <InputLabel value="Route Name"/>

                  <TextInput
                      v-model="crud.route"
                      autocomplete="current-password"
                      class="mt-1 block w-full"
                      required
                      type="text"
                  />

                </div>
              </div>

              <div class="flex flex-wrap mb-6">

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox v-model:checked="crud.model" name="model"/>
                    <span class="ml-2 text-sm text-gray-600">Create Modal</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox v-model:checked="crud.migration" name="migration"/>
                    <span class="ml-2 text-sm text-gray-600">Create Migration</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox v-model:checked="crud.validator" name="validator"/>
                    <span class="ml-2 text-sm text-gray-600">Create Validator</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox v-model:checked="crud.dto" name="dto"/>
                    <span class="ml-2 text-sm text-gray-600">Create DTO</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox v-model:checked="crud.controller" name="controller"/>
                    <span class="ml-2 text-sm text-gray-600">Create Controller</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox v-model:checked="crud.action" name="action"/>
                    <span class="ml-2 text-sm text-gray-600">Create Action</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox v-model:checked="crud.table" name="table"/>
                    <span class="ml-2 text-sm text-gray-600">Create Table List (Inertia)</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox v-model:checked="crud.create_modal" name="create_modal"/>
                    <span class="ml-2 text-sm text-gray-600">Create Modal Form Create (Inertia)</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox v-model:checked="crud.update_modal" name="update_modal"/>
                    <span class="ml-2 text-sm text-gray-600">Create Modal Form Update (Inertia)</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox v-model:checked="crud.delete_prompt" name="delete_prompt"/>
                    <span class="ml-2 text-sm text-gray-600">Create Prompt for Delete (Inertia)</span>
                  </label>
                </div>

              </div>

              <PrimaryButton type="button" @click="addField">Add +</PrimaryButton>
              <div v-for="(field, i) in crud.fields" class="flex">
                <div class="p-2 pt-12">
                  <PrimaryButton type="button" @click="removeField(i)">-</PrimaryButton>
                </div>
                <div class="w-1/3 p-2 mt-4">
                  <InputLabel for="name" value="Name"/>

                  <TextInput
                      id="name"
                      v-model="field.name"
                      autocomplete="current-password"
                      class="mt-1 block w-full"
                      required
                      type="text"
                  />
                </div>

                <div class="w-1/3 p-2 mt-4">
                  <InputLabel for="password" value="Type"/>
                  <VueMultiselect
                      v-model="field.type"
                      :closeOnSelect="true"
                      :optionHeight="45"
                      :options="options"
                      class="mt-1 block w-full"
                      deselectLabel=""
                      label="name"
                      placeholder="Select one"
                      selectLabel=""
                      track-by="name"
                  />
                </div>

                <div class="w-1/6 p-2 mt-8">
                  <div class="block mt-4">
                    <label class="flex items-center">
                      <Checkbox v-model:checked="field.required" name="required"/>
                      <span class="ml-2 text-sm text-gray-600">is Required</span>
                    </label>
                    <label class="flex items-center">
                      <Checkbox v-model:checked="field.fillable" name="fillable"/>
                      <span class="ml-2 text-sm text-gray-600">is Fillable</span>
                    </label>
                  </div>
                </div>

                <div class="w-1/4 p-2 mt-4">
                  <InputLabel for="validationRules" value="Validation Rules"/>

                  <TextInput
                      id="validationRules"
                      v-model="field.validationRules"
                      autocomplete="current-password"
                      class="mt-1 block w-full"
                      type="text"
                  />
                </div>

              </div>
            </div>

            <div class="text-right pr-6 mt-3">
              <PrimaryButton :class="{ 'opacity-25': crud.processing }" :disabled="crud.processing" class="ml-4">
                Submit
              </PrimaryButton>
            </div>
          </form>

        </div>

      </div>
    </div>
  </div>
</template>


<script setup>

import Checkbox from './Components/Checkbox.vue';
import InputLabel from './Components/InputLabel.vue';
import PrimaryButton from './Components/PrimaryButton.vue';
import TextInput from './Components/TextInput.vue';
import VueMultiselect from 'vue-multiselect'
import {onMounted, ref} from "vue";

const crud = ref({
  name: '',
  model: true,
  migration: true,
  seeder: true,
  controller: true,
  dto: true,
  validator: true,
  action: true,
  table: true,
  create_modal: true,
  update_modal: true,
  delete_prompt: true,
  fields: [{
    name: '',
    type: '',
    required: false,
    fillable: true,
    validationRules: ''
  }]
});

const submit = async () => {

  let request = await axios.post('/inertia-crud-generator', crud.value)
  if (request.status === 200) {
    alert("Success")
  }
};

const options = ref([]);


onMounted(() => {
  let fieldTypes = document.getElementById('vueApp').dataset
  options.value = JSON.parse(fieldTypes.fieldTypes)
})


const addField = () => {
  crud.value.fields.push({
    name: '',
    type: '',
    required: false,
    fillable: true,
    validationRules: ''
  })
}

const removeField = (index) => {
  delete crud.value.fields.splice(index, 1)
}

</script>
