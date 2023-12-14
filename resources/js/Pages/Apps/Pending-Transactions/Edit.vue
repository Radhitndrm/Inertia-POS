<template>
    <head>
        <title>Process Pending Transaction - Aplikasi kasir</title>
    </head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"> <i class="fa fa-edit"></i> PROCESS PENDING
                                    TRANSACTION</span>
                                <div v-if="change > 0">
                                    <hr>
                                    <h5 class="text-success">Change : <strong>Rp. {{ formatPrice(change) }}</strong>
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="submit">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Customer Name</label>
                                                <input class="form-control" type="text" v-model="form.customer" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Invoice</label>
                                                <input class="form-control" v-model="form.invoice" type="text" disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Total Price</label>
                                                <input class="form-control" type="text" v-model="form.grand_total" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="fw-bold">Discount</label>
                                                <input class="form-control" v-model="discount" @keyup="setDiscount"
                                                    type="number" placeholder="Discount">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="fw-bold">Customer's Cash</label>
                                                <input class="form-control" v-model="cash" @keyup="setChange" type="text"
                                                    placeholder="Cash">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary shadow-sm rounded-sm" type="submit"
                                                :disabled="form.grand_total > cash">Pay</button>
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

import { ref } from 'vue';

const props = defineProps({
    errors: Object,
    pendingTransaction: Object,

})
const cash = ref(0);
const discount = ref(0);
const change = ref(0);

const setDiscount = () => {
    // Menghitung grand_total sesuai dengan nilai diskon baru
    form.grand_total = props.pendingTransaction.grand_total - discount.value;
}

const setChange = () => {
    change.value = cash.value - form.grand_total
}

const form = reactive({
    customer: props.pendingTransaction.customer.name,
    invoice: props.pendingTransaction.invoice,
    grand_total: props.pendingTransaction.grand_total,
    discount: props.pendingTransaction.grand_total // Inisialisasi diskon dengan nilai grand total awal
});

const submit = () => {
    Inertia.post(`/apps/pendingTransactions/${props.pendingTransaction.id}`, {
        _method: 'PUT',
        cash: cash.value,
        change: change.value,
        discount: discount.value
    }, {
        onSuccess: () => {
            Swal.fire({
                title: 'Success!',
                text: 'Transaction Paid!',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000
            });
        }
    })
}
</script>