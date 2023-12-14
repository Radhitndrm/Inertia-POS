<template>
    <head>
        <title>Pending Transactions - Aplikasi Kasir</title>
    </head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-shopping-cart"></i> PENDING
                                    TRANSACTIONS</span>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Invoice</th>
                                            <th scope="col">Customer</th>
                                            <th scope="col">Total Price</th>
                                            <th scope="col">Status</th>

                                            <th scope="col" style="width:20%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(pendingTransaction, index) in pendingTransactions" :key="index">
                                            <td>{{ index + 1 }} </td>
                                            <td>{{ pendingTransaction.invoice }}</td>
                                            <td>{{ pendingTransaction.customer.name }}</td>
                                            <td>Rp. {{ formatPrice(pendingTransaction.grand_total) }}</td>
                                            <td><button class="btn btn-warning btn-sm text-white" disabled> {{
                                                pendingTransaction.status
                                            }}</button>
                                            </td>

                                            <td class="text-center">
                                                <Link :href="`/apps/pendingTransactions/${pendingTransaction.id}/edit`"
                                                    class="btn btn-success btn-sm me-2 "> <i class="fa fa-edit mr-1"></i>
                                                EDIT
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- Pagination component can be added here if needed -->
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





const props = defineProps({
    pendingTransactions: Object,
    pendingTransactionsGrandTotal: Object,

});




</script>