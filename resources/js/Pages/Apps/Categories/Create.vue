<template>
    <Head>
        <title>Add new Category - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-folder"></i> ADD NEW CATEGORY</span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="submit">
                                    <div class="row">
                                        <div class="mb-3">
                                            <input class="form-control" type="file"
                                                @input="form.image = $event.target.files[0]"
                                                :class="{ 'is-invalid': errors.image }">
                                        </div>
                                        <div v-if="errors.image" class="alert alert-danger">
                                            {{ errors.image }}
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold">Category Name</label>
                                            <input class="form-control" type="text" v-model="form.name"
                                                :class="{ 'is-invalid': errors.name }" placeholder="Category Name">
                                        </div>
                                        <div v-if="errors.name" class="alert alert-danger">
                                            {{ errors.name }}
                                        </div>
                                        <div class="mb-3">
                                            <label class="fw-bold">Description</label>
                                            <textarea class="form-control" v-model="form.description"
                                                :class="{ 'is-invalid': errors.description }" rows="4"
                                                placeholder="Description"></textarea>
                                        </div>
                                        <div v-if="errors.description" class="alert alert-danger">
                                            {{ errors.description }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary shadow-sm rounded-sm" type="submit">SAVE</button>
                                            <button class="btn btn-warning shadow-sm rounded-sm ms-3"
                                                type="reset">RESET</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    layout: LayoutApp
}
</script>

<script setup>
import LayoutApp from '../../../layouts/App.vue';

import { Head, Link } from '@inertiajs/inertia-vue3';

import { reactive } from 'vue';

import { Inertia } from '@inertiajs/inertia'

import Swal from 'sweetalert2';

const props = defineProps({
    errors: Object
});

const form = reactive({
    image: '',
    name: '',
    description: ''
});

const submit = () => {
    Inertia.post('/apps/categories', {
        image: form.image,
        name: form.name,
        description: form.description
    }, {
        onSuccess: () => {
            Swal.fire({
                title: 'Success!',
                text: 'Category saved successfully!',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });
        }
    })
}
</script>