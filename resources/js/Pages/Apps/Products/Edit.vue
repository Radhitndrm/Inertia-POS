<template>
    <Head>
        <title>Edit Product - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"> <i class="fa fa-shopping-bag"></i> EDIT PRODUCT</span>
                            </div>
                            <div class="card-body">

                                <form @submit.prevent="submit">
                                    <div class="mb-3">
                                        <input class="form-control" type="file" @input="form.image = $event.target.files[0]"
                                            :class="{ 'is-invalid': errors.image }">
                                    </div>
                                    <div v-if="errors.image" class="alert alert-danger">
                                        {{ errors.image }}
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Barcode</label>
                                                <input class="form-control" type="text" v-model="form.barcode"
                                                    :class="{ 'is-invalid': errors.barcode }"
                                                    placeholder="Barcode / Product Code">
                                            </div>
                                            <div v-if="errors.barcode" class="alert alert-danger">
                                                {{ errors.barcode }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Category</label>
                                                <select class="form-select" v-model="form.category_id"
                                                    :class="{ 'is-invalid': errors.category_id }">
                                                    <option v-for="(category, index) in categories" :key="index"
                                                        :value="category.id">{{ category.name }}</option>
                                                </select>
                                            </div>
                                            <div v-if="errors.category_id" class="alert alert-danger">
                                                {{ errors.category_id }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Title Product</label>
                                                <input class="form-control" v-model="form.title"
                                                    :class="{ 'is-invalid': errors.tile }" type="text"
                                                    placeholder="Title Product">
                                            </div>
                                            <div v-if="errors.title" class="alert alert-danger">
                                                {{ errors.title }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Stocks</label>
                                                <input class="form-control" v-model="form.stock"
                                                    :class="{ 'is-invalid': errors.stock }" type="text"
                                                    placeholder="Product Stock">
                                            </div>
                                            <div v-if="errors.stock" class="alert alert-danger">
                                                {{ errors.stock }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="fw-bold">Description</label>
                                        <textarea class="form-control" rows="4" v-model="form.description"
                                            :class="{ 'is-invalid': errors.description }"
                                            placeholder="Description"></textarea>
                                    </div>
                                    <div v-if="errors.description" class="alert alert-danger">
                                        {{ errors.description }}
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Buy Price</label>
                                                <input class="form-control" v-model="form.buy_price"
                                                    :class="{ 'is-invalid': errors.buy_price }" type="text"
                                                    placeholder="Buy Price">
                                            </div>
                                            <div v-if="errors.buy_price" class="alert alert-danger">
                                                {{ errors.buy_price }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Sell Price</label>
                                                <input class="form-control" v-model="form.sell_price"
                                                    :class="{ 'is-invalid': errors.sell_price }" type="text"
                                                    placeholder="Sell Price">
                                            </div>
                                            <div v-if="errors.sell_price" class="alert alert-danger">
                                                {{ errors.sell_price }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button class="btn btn-primary shadow-sm rounded-sm" type="submit">UPDATE
                                                </button>
                                                <button class="btn btn-warning shadow-sm rounded-sm ms-3"
                                                    type="reset">RESET</button>
                                            </div>
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
    product: Object,
    categories: Array,

});

const form = reactive({
    image: '',
    barcode: props.product.barcode,
    category_id: props.product.category_id,
    title: props.product.title,
    description: props.product.description,
    buy_price: props.product.buy_price,
    sell_price: props.product.sell_price,
    stock: props.product.stock
});

const submit = () => {
    Inertia.post(`/apps/products/${props.product.id}`, {

        _method: 'PUT',
        image: form.image,
        barcode: form.barcode,
        category_id: form.category_id,
        title: form.title,
        description: form.description,
        buy_price: form.buy_price,
        sell_price: form.sell_price,
        stock: form.stock
    }, {
        onSuccess: () => {
            Swal.fire({
                title: 'Success!',
                text: 'Product saved Successfully!',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });
        }
    })
}
</script>