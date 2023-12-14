<template>
    <Head>
        <title>Add new User - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-user"></i> ADD NEW USER</span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="submit">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Full Name</label>
                                                <input class="form-control" v-model="form.name"
                                                    :class="{ 'is-invalid': errors.name }" type="text"
                                                    placeholder="Full Name">
                                            </div>
                                            <div v-if="errors.name" class="alert alert-danger">
                                                {{ errors.name }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Email Address</label>
                                                <input class="form-control" v-model="form.email"
                                                    :class="{ 'is-invalid': errors.email }" type="email"
                                                    placeholder="Email Address">
                                            </div>
                                            <div v-if="errors.email" class="alert alert-danger">
                                                {{ errors.email }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Password</label>
                                                <input class="form-control" v-model="form.password"
                                                    :class="{ 'is-invalid': errors.password }" type="password"
                                                    placeholder="Password">
                                            </div>
                                            <div v-if="errors.password" class="alert alert-danger">
                                                {{ errors.password }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Password Confirmation</label>
                                                <input class="form-control" v-model="form.password_confirmation"
                                                    type="password" placeholder="Password Confirmation">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="fw-bold">Roles</label>
                                                <br>
                                                <div class="form-check form-check-inline" v-for="(role, index) in roles"
                                                    :key="index">
                                                    <input class="form-check-input" type="checkbox" v-model="form.roles"
                                                        :value="role.name" :id="`check-${role.id}`">
                                                    <label class="form-label-check" :for="`check-${role.id}`">{{ role.name
                                                    }}</label>
                                                </div>
                                            </div>
                                            <div v-if="errors.roles" class="alert alert-danger">
                                                {{ errors.roles }}
                                            </div>
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

import { Inertia } from '@inertiajs/inertia';

import Swal from 'sweetalert2';

const props = defineProps({
    errors: Object,
    roles: Array,
});

const form = reactive({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    roles: []
});

const submit = () => {

    Inertia.post('/apps/users', {
        name: form.name,
        email: form.email,
        password: form.password,
        password_confirmation: form.password_confirmation,
        roles: form.roles
    }, {
        onSuccess: () => {
            Swal.fire({
                title: 'Success!',
                text: 'User saved successfully',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });
        }
    })
}
</script>