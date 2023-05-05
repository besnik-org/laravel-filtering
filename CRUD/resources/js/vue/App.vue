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
                <div class="w-1/2 p-2 mt-4">
                  <InputLabel value="Crud Name"/>

                  <TextInput
                      type="text"
                      class="mt-1 block w-full"
                      v-model="crud.name"
                      required
                      autocomplete="current-password"
                  />

                </div>
              </div>

              <div class="flex flex-wrap mb-6">

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox name="model" v-model:checked="crud.model"/>
                    <span class="ml-2 text-sm text-gray-600">Create Modal</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox name="migration" v-model:checked="crud.migration"/>
                    <span class="ml-2 text-sm text-gray-600">Create Migration</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox name="validator" v-model:checked="crud.validator"/>
                    <span class="ml-2 text-sm text-gray-600">Create Validator</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox name="dto" v-model:checked="crud.dto"/>
                    <span class="ml-2 text-sm text-gray-600">Create DTO</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox name="controller" v-model:checked="crud.controller"/>
                    <span class="ml-2 text-sm text-gray-600">Create Controller</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox name="action" v-model:checked="crud.action"/>
                    <span class="ml-2 text-sm text-gray-600">Create Action</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox name="table" v-model:checked="crud.table"/>
                    <span class="ml-2 text-sm text-gray-600">Create Table List (Inertia)</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox name="create_modal" v-model:checked="crud.create_modal"/>
                    <span class="ml-2 text-sm text-gray-600">Create Modal Form Create (Inertia)</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox name="update_modal" v-model:checked="crud.update_modal"/>
                    <span class="ml-2 text-sm text-gray-600">Create Modal Form Update (Inertia)</span>
                  </label>
                </div>

                <div class="block p-2 mt-1">
                  <label class="flex items-center">
                    <Checkbox name="delete_prompt" v-model:checked="crud.delete_prompt"/>
                    <span class="ml-2 text-sm text-gray-600">Create Prompt for Delete (Inertia)</span>
                  </label>
                </div>

              </div>

              <PrimaryButton @click="addField" type="button">Add +</PrimaryButton>
              <div class="flex" v-for="(field, i) in crud.fields">
                <div class="p-2 pt-12">
                  <PrimaryButton type="button" @click="removeField(i)">-</PrimaryButton>
                </div>
                <div class="w-1/3 p-2 mt-4">
                  <InputLabel for="name" value="Name"/>

                  <TextInput
                      id="name"
                      type="text"
                      class="mt-1 block w-full"
                      v-model="field.name"
                      required
                      autocomplete="current-password"
                  />
                </div>

                <div class="w-1/3 p-2 mt-4">
                  <InputLabel for="password" value="Type"/>
                  <VueMultiselect
                      class="mt-1 block w-full"
                      :optionHeight="45"
                      deselectLabel=""
                      selectLabel=""
                      v-model="field.type"
                      :options="options"
                      :closeOnSelect="true"
                      label="name"
                      placeholder="Select one"
                      track-by="name"
                  />
                </div>

                <div class="w-1/6 p-2 mt-8">
                  <div class="block mt-4">
                    <label class="flex items-center">
                      <Checkbox name="required" v-model:checked="field.required"/>
                      <span class="ml-2 text-sm text-gray-600">is Required</span>
                    </label>
                    <label class="flex items-center">
                      <Checkbox name="fillable" v-model:checked="field.fillable"/>
                      <span class="ml-2 text-sm text-gray-600">is Fillable</span>
                    </label>
                  </div>
                </div>

                <div class="w-1/4 p-2 mt-4">
                  <InputLabel for="validationRules" value="Validation Rules"/>

                  <TextInput
                      id="validationRules"
                      type="text"
                      class="mt-1 block w-full"
                      v-model="field.validationRules"
                      required
                      autocomplete="current-password"
                  />
                </div>

              </div>
            </div>

            <div class="text-right pr-6 mt-3">
              <PrimaryButton class="ml-4" :class="{ 'opacity-25': crud.processing }" :disabled="crud.processing">
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
import InputError from './Components/InputError.vue';
import InputLabel from './Components/InputLabel.vue';
import PrimaryButton from './Components/PrimaryButton.vue';
import TextInput from './Components/TextInput.vue';
import VueMultiselect from 'vue-multiselect'
import {ref} from "vue";

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
   if(request.status ===  200){
     alert("Success")
   }
};

const options = [
  {name: 'Text', id: 'text'},
  {name: 'Bool', id: 'bool'},
  {name: 'Select', id: 'select'},
  {name: 'MultiSelect', id: 'multiselect'},
  {name: 'tag', id: 'tag'},
  {name: 'checkbox', id: 'checkbox'},
  {name: 'radio', id: 'radio'},
];

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
