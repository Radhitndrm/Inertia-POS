<template>
    <Head>
        <title>Edit Customer - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-user"></i> EDIT CUSTOMER</span>
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
                                                <label class="fw-bold">No. Telp</label>
                                                <input class="form-control" v-model="form.no_telp"
                                                    :class="{ 'is-invalid': errors.no_telp }" type="text"
                                                    placeholder="No Telp">
                                            </div>
                                            <div v-if="errors.no_telp" class="alert alert-danger">
                                                {{ errors.no_telp }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Address</label>
                                                <input class="form-control" v-model="form.address"
                                                    :class="{ 'is-invalid': errors.address }" type="text"
                                                    placeholder="Address">
                                            </div>
                                            <div v-if="errors.address" class="alert alert-danger">
                                                {{ errors.address }}
                                            </div>
                                        </div>
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

import { Inertia } from '@inertiajs/inertia';

import Swal from 'sweetalert2';

const props = defineProps({
    errors: Object,
    customer: Object,

});

const form = reactive({
    name: props.customer.name,
    no_telp: props.customer.no_telp,
    address: props.customer.address,
});

const submit = () => {

    Inertia.put(`/apps/customers/${props.customer.id}`, {
        name: form.name,
        no_telp: form.no_telp,
        address: form.address,
    }, {
        onSuccess: () => {
            Swal.fire({
                title: 'Success!',
                text: 'Customer Updated successfully',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });
        }
    })
}
</script>