<?php

namespace Besnik\LaravelInertiaCrud\Actions;

use Besnik\LaravelInertiaCrud\DTO\CrudFieldDto;
use Besnik\LaravelInertiaCrud\Utilities\CrudSupports;
use Besnik\LaravelInertiaCrud\Utilities\HtmlFields;
use Besnik\LaravelInertiaCrud\Utilities\MessageBucket;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateVue
{
    public function execute(CrudSupports $crudSupports): bool
    {
        if ($crudSupports->crudDto->createInertiaTable) {
            $this->createIndex($crudSupports);
        }

        if ($crudSupports->crudDto->createInertiaCreateModal) {
            $this->createModal($crudSupports);
        }

        if ($crudSupports->crudDto->createInertiaUpdateModal) {
            $this->updateModal($crudSupports);
        }

        $this->supportJS($crudSupports);

        return true;
    }

    public function createIndex(CrudSupports $crudSupports): bool
    {
        $support = $crudSupports->vueSupports();

        if (File::exists($support->indexVuePath)) {
            MessageBucket::addError("Inertia Page 'Index.vue' Already exist");
            return true;
        }

        $tableHeader = '';
        $tableRows = '';

        foreach ($crudSupports->crudDto->fields as $field) {
            /** @var CrudFieldDto $field */

            $title = $this->makeTitle($field);
            $tableHeader .= "               <th>$title</th>\n";

            $tableRows .= "                <td>{{ datum.{$field->name} }}</td>\n";
        }

        File::put($support->indexVuePath, <<<EOT
<script setup>
import AuthenticatedLayout from '@/Admin/Layouts/AuthenticatedLayout.vue';
import { onMounted, ref} from "vue";
import PrimaryButton from '@/Admin/Components/PrimaryButton.vue';
import {TailwindPagination} from 'laravel-vue-pagination';
import {getPaginateData, deleteData} from './Support';
import CreateModal from './CreateModal.vue';
import UpdateModal from './UpdateModal.vue';
import {webToast} from '@/Admin/Utilities/webtoast'
import {openModal} from "@/Admin/Components/InteractiveUI/Modal";

const props = defineProps({
  servers: Object,
})

const data = ref({})
const crud = ref({})

onMounted(() => {
 data.value = {...props.servers}
})

const setData = (data) => {
  webToast.Info({
    status: 'Wow !',
    message: 'You are success',
  })
  data.value = data
}

const openCreateModal = () => {
  crud.value = {action: 'create'}
  openModal('create')
}

const openUpdateModal = (item, index) => {
  crud.value = {action: 'update', index: index, ...item}
  openModal('update')
}

</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Servers </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

          <PrimaryButton type="button" @click="openCreateModal()"> Create </PrimaryButton>

          <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

            <!--    <ComponentFilter :themes="props.themes" :users="props.users" :categories="props.categories"  @submit-filter="getFilter"/>&ndash;&gt;-->

            <table class="table w-full">

              <thead>
              <tr>
                <th>SL</th>
{$tableHeader}
              </tr>
              </thead>
              <tbody>
              <tr v-for="(datum, i) in data.data">
                <td>{{ i+1 }}</td>
{$tableRows}
                <td>
                  <button type="button" @click="openUpdateModal(datum, i)" class="btn-bordered info">Edit</button>
                  <button type="button" @click="deleteData(datum, i)" class="ml-2 btn-bordered warning">Delete</button>
                </td>
              </tr>
              </tbody>
            </table>

            <div class="mt-4 flex" v-if="servers.total">
              <p class="p-3">Total: {{ servers.total }}</p>
              <TailwindPagination
                  :data="servers"
                  @pagination-change-page="(page) => getPaginateData(page)"
              />
            </div>

            <CreateModal v-if="crud.action === 'create'" :data="crud"/>
            <UpdateModal  v-if="crud.action === 'update'"  :data="crud"/>

          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
EOT
        );

        MessageBucket::addInfo("Inertia Page 'Index.vue' Created");
        return true;
    }

    /**
     * @param  \Besnik\LaravelInertiaCrud\DTO\CrudFieldDto  $field
     * @return string
     */
    public function makeTitle(CrudFieldDto $field): string
    {
        $normalized = str_replace('_', ' ', Str::snake($field->name));
        $title = ucwords($normalized);
        return $title;
    }

    public function createModal(CrudSupports $crudSupports): bool
    {
        $support = $crudSupports->vueSupports();

        if (File::exists($support->createModalPath)) {
            MessageBucket::addError("Inertia Create Modal Already exist");
            return true;
        }

        $fields = '';

        $crudSingularName = Str::singular($crudSupports->tableName);

        foreach ($crudSupports->crudDto->fields as $field) {
            /** @var CrudFieldDto $field */

            $title = $this->makeTitle($field);
            $type = $field->type;
            $fields .= HtmlFields::$type($field->name, $title);
        }

        File::put($support->createModalPath, <<<EOT
<template>
  <Modal uid="create"  :outsideClick="false">
    <template v-slot:title>
      Create Server
    </template>

    <template v-slot:body>
      <form @submit.prevent="submit">

        <div class="flex flex-wrap justify-center">

           {$fields}

        </div>

        <PrimaryButton type="submit" class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Update
        </PrimaryButton>
      </form>

    </template>

  </Modal>
</template>

<script setup>
import { useForm} from '@inertiajs/vue3';
import {Modal, openModal, closeModal} from '@/Admin/Components/InteractiveUI/Modal';
import {onMounted, ref, watch} from "vue";
import PrimaryButton from '@/Admin/Components/PrimaryButton.vue';
import {webToast} from "@/Admin/Utilities/webtoast";
import InputError from '@/Admin/Components/InputError.vue';
import NProgress from 'nprogress'


const props = defineProps({
  data: Object,
})

let form = useForm({...props.data});
const errors = ref({});

watch(() => props.data, (newData) => {
  form = useForm({...newData})
});

const submit = () => {
  NProgress.start();
  form.post(route('{$crudSupports->route}.store'), {
    onFinish: () => form.reset(),
    onError: (error) => {
      errors.value =  error
      webToast.Info({
        status:'Ops !',
        message:'Something went wrong.'
      })
    },
    onSuccess: () => {
      closeModal('update')
      webToast.Success({
        status:'Wow !',
        message:'Operation Success'
      })
    }
  });

};

</script>
EOT
        );

        MessageBucket::addInfo("Inertia Create Modal Created");
        return true;
    }

    public function updateModal(CrudSupports $crudSupports): bool
    {
        $support = $crudSupports->vueSupports();

        if (File::exists($support->updateModalPath)) {
            MessageBucket::addError("Inertia Update Modal Already exist");
            return true;
        }
        $fields = '';

        foreach ($crudSupports->crudDto->fields as $field) {
            /** @var CrudFieldDto $field */

            $title = $this->makeTitle($field);
            $type = $field->type;
            $fields .= HtmlFields::$type($field->name, $title);
        }

        File::put($support->updateModalPath, <<<EOT
<template>
  <Modal uid="update"  :outsideClick="false">
    <template v-slot:title>
      Create Server
    </template>

    <template v-slot:body>
      <form @submit.prevent="submit">

        <div class="flex flex-wrap justify-center">

           {$fields}

        </div>

        <PrimaryButton type="submit" class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Update
        </PrimaryButton>
      </form>

    </template>

  </Modal>
</template>

<script setup>
import { useForm} from '@inertiajs/vue3';
import {Modal, openModal, closeModal} from '@/Admin/Components/InteractiveUI/Modal';
import {onMounted, ref, watch} from "vue";
import PrimaryButton from '@/Admin/Components/PrimaryButton.vue';
import {webToast} from "@/Admin/Utilities/webtoast";
import InputError from '@/Admin/Components/InputError.vue';
import NProgress from 'nprogress'


const props = defineProps({
  data: Object,
})

let form = useForm({...props.data});
const errors = ref({});

watch(() => props.data, (newData) => {
  form = useForm({...newData})
});

const submit = () => {
  NProgress.start();
  form.post(route('{$crudSupports->route}.store'), {
    onFinish: () => form.reset(),
    onError: (error) => {
      errors.value =  error
      webToast.Info({
        status:'Ops !',
        message:'Something went wrong.'
      })
    },
    onSuccess: () => {
      closeModal()
      webToast.Success({
        status:'Wow !',
        message:'Operation Success'
      })
    }
  });

};

</script>
EOT
        );


        MessageBucket::addError("Inertia Update Modal Created");
        return true;
    }

    public function supportJS(CrudSupports $crudSupports): bool
    {
        $support = $crudSupports->vueSupports();

        if (File::exists($support->supportJSPath)) {
            MessageBucket::addError("Inertia Support JS Already exist");
            return true;
        }

        $fields = '';

        foreach ($crudSupports->crudDto->fields as $field) {
            /** @var CrudFieldDto $field */

            $title = $this->makeTitle($field);
            $type = $field->type;
            $fields .= HtmlFields::$type($field->name, $title);
        }

        File::put($support->supportJSPath, <<<EOT
import {router, useForm} from "@inertiajs/vue3";
import NProgress from 'nprogress'
import {webToast} from "@/Admin/Utilities/webtoast";

export const deleteData = async (item, index) => {
    const confirm = webToast.confirm("Are You sure to delete?")

    confirm.addEventListener('click', function () {
        const request = useForm({...item})
        request.delete(route('{$crudSupports->route}.destroy', {id: item.id}), {
            onError: (error) => {
                webToast.Info({
                    status: 'Ops !',
                    message: 'Something went wrong.'
                })
            },
            onSuccess: () => {
                webToast.Success({
                    status: 'Wow !',
                    message: 'Deleted Successfully'
                })
            }
        });
    })
}

export const getPaginateData = (page) => {
    NProgress.start();
    router.get(route('{$crudSupports->route}.index', {page: page}))
}

EOT
        );

        MessageBucket::addError("Inertia Support JS Created");

        return true;
    }
}