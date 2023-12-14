<template>
    <head>
        <title>Edit Category - Aplikasi kasir</title>
    </head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"> <i class="fa fa-folder"></i> EDIT CATEGORY</span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="submit">
                                    <div class="mb-3">
                                        <input class="form-control" @input="form.image = $event.target.files[0]" type="file"
                                            :class="{ 'is-invalid': errors.image }">
                                    </div>
                                    <div v-if="errors.image" class="alert alert-danger">
                                        {{ errors.image }}
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold">Category Name</label>
                                        <input class="form-control" v-model="form.name"
                                            :class="{ 'is-invalid': errors.name }" type="text" placeholder="Category Name">
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
                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary shadow-sm rounded-sm"
                                                type="submit">UPDATE</button>
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

import Swal from 'sweetalert2'

const props = defineProps({
    errors: Object,
    category: Object,

})

const form = reactive({
    name: props.category.name,
    description: props.category.description,
    image: ''
});


const submit = () => {
    Inertia.post(`/apps/categories/${props.category.id}`, {
        _method: 'PUT',
        name: form.name,
        description: form.description,
        image: form.image
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