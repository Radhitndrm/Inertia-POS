<template>
    <Head>
        <title>Pos Transactions - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-7">
                        <!-- <div class="card border-0 rounded-3 shadow">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Product Name</label>
                                        <input type="text" class="form-control" v-model="selectedProduct" @keyup="searchProduct"
                                            placeholder="Product Name" readonly>
                                        <input type="hidden" v-model="selectedProductId">
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Qty</label>
                                            <input type="number" class="form-control text-center" v-model="qty"
                                                placeholder="Qty" min="1">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Stock Product</label>
                                            <input type="text" class="form-control text-center" placeholder="stock"
                                                v-model="stock" readonly>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button @click.prevent="clearSearch"
                                            class="btn btn-warning btn-md border-0 shadow text-uppercase mt-3 me-2"
                                            :disabled="!selectedProductId">CLEAR</button>
                                        <button @click.prevent="addToCart"
                                            class="btn btn-success btn-md border-0 shadow text-uppercase mt-3"
                                            :disabled="!selectedProductId">ADD ITEM</button>
                                    </div>
                                </div>
                            </div> -->
                        <div class="card border-0 rounded-3 shadow border-top-danger">
                            <div class="card-body">
                                <div class="mb-3">
                                    <form @click.prevent="SelectSearch">
                                        <label class="form-label fw-bold">Search By Categories</label>
                                        <select v-model="search" @change="filterProducts" class="form-select">
                                            <option value="" selected>All Categories</option>
                                            <option v-for="(category, index) in categories" :key="index"
                                                :value="category.id">{{ category.name }}</option>
                                        </select>
                                    </form>

                                </div>
                                <div class="mb-3">
                                    <div class="row">
                                        <div class="col-md-4 mb-3" v-for="(product, index) in products.data" :key="index">
                                            <div class="card h-100 w-100 ">
                                                <button @click.prevent="addToCart(product)" class="btn btn-white h-100">
                                                    <img :src="product.image" class="card-img-top" alt="Product Image">
                                                    <div class="card-body text-center">
                                                        <h5 class="card-title fw-bold">
                                                            {{ product.title }}
                                                        </h5>
                                                    </div>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <Pagination :links="products.links" align="end" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">

                        <div v-if="session.error" class="alert alert-danger">
                            {{ session.error }}
                        </div>

                        <div v-if="session.success" class="alert alert-success">
                            {{ session.success }}
                        </div>

                        <div class="card border-0 rounded-3 shadow border-top-success">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-5 col-5">
                                        <h4 class="fw-bold">POS GRAND TOTAL</h4>
                                    </div>
                                    <div class="col-md-7 col-7 text-end">
                                        <h4 class="fw-bold">Rp. {{ formatPrice(grandTotal) }}</h4>
                                        <div v-if="change > 0">
                                            <hr>
                                            <h5 class="text-success">Change : <strong>Rp. {{ formatPrice(change)
                                            }}</strong>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 rounded-3 shadow">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="fw-bold">Cashier</label>
                                        <input class="form-control" type="text" :value="auth.user.name" readonly>
                                    </div>
                                    <div class="col-md-6 float-end">
                                        <label class="fw-bold">Customer</label>
                                        <input class="form-control" v-model="customer"
                                            :class="{ 'is-invalid': errors.customer }" type="text"
                                            placeholder="Customer Name Required">
                                        <div v-if="errors.customer" class="alert alert-danger">
                                            {{ errors.customer }}
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr style="background-color: #e6e6e7;">
                                            <th scope="col">#</th>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="cart in carts" :key="cart.id">
                                            <td class="text-center">
                                                <button @click.prevent="destroyCart(cart.id)"
                                                    class="btn btn-danger btn-sm rounded-pill"><i
                                                        class="fa fa-trash"></i></button>
                                            </td>
                                            <td>{{ cart.product.title }}</td>
                                            <td>Rp. {{ formatPrice(cart.product.sell_price) }}</td>
                                            <td class="text-center">
                                                <div class="input-group">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        @click="incrementDecrement(cart, -1)">-</button>
                                                    <input type="number" class="form-control col-6 no-spinners"
                                                        v-model="cart.qty" :min="1"
                                                        v-on:keyup.prevent="updateQuantity(cart, cart.qty)">
                                                    <button class="btn btn-outline-secondary" type="button"
                                                        @click="incrementDecrement(cart, 1)">+</button>
                                                </div>
                                            </td>
                                            <td class="text-end">Rp. {{ formatPrice(cart.price) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold" style="background-color: #e6e6e7;">
                                                TOTAL</td>
                                            <td class="text-end fw-bold" style="background-color: #e6e6e7;">Rp. {{
                                                formatPrice(carts_total) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <div class="d-flex align-items-end flex-column bd-highlight mb-3">
                                    <div class="mt-auto bd-highlight">
                                        <label>Discount (Rp.)</label>
                                        <input type="number" v-model="discount" @keyup="setDiscount" class="form-control"
                                            placeholder="Discount (Rp.)">
                                    </div>
                                    <div class="bd-highlight mt-4">
                                        <label>Pay (Rp.)</label>
                                        <input type="number" v-model="cash" @keyup="setChange" class="form-control"
                                            placeholder="Pay (Rp.)">
                                    </div>
                                </div>
                                <div class="mt-4 text-end">
                                    <button @click.prevent="storePendingTransaction"
                                        class="btn btn-warning text-white btn-md border-0 shadow text-uppercase mt-4  mr-4"
                                        :disabled="!qty || !customer || !carts_total">Pay Later</button>
                                    <button @click.prevent="storeTransaction"
                                        class="btn btn-purple btn-md border-0 shadow text-uppercase mt-4 "
                                        :disabled="cash < grandTotal || grandTotal == 0 || !customer">Pay Order &
                                        Print</button>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>

//import layout
import LayoutApp from '../../../layouts/App.vue';

//import Heade from Inertia
import { Head } from '@inertiajs/inertia-vue3';

import Pagination from '../../../Components/Pagination.vue';




//import ref form vue
import { ref } from 'vue';

//import axios
import axios from 'axios';

//import inerita adapter
import { Inertia } from '@inertiajs/inertia';

//import sweet alert2
import Swal from 'sweetalert2';

import { reactive } from 'vue';

export default {
    //layout
    layout: LayoutApp,

    //register components
    components: {
        Head,

        Pagination,
    },

    data() {
        return {
            selectedProduct: '', // Initialize with an empty string
            selectedProductId: '', // Initialize with an empty string
            sell_price: '',
        };
    },

    //props
    props: {
        errors: Object,
        auth: Object,
        carts_total: Number,
        session: Object,
        carts: Array,
        categories: Array,
        products: Object,
        productsPaginate: Object,
    },

    methods: {



        selectProduct(product) {
            this.selectedProduct = product.title; // Set the selected product name
            this.selectedProductId = product.id; // Set the selected product ID
            this.sell_price = product.sell_price;
            this.stock = product.stock;
        },

        getFontSize(title) {
            if (title.length <= 50) {
                return '9px'; // Ukuran font default jika nama produk tidak terlalu panjang
            } else {
                const fontSize = 100 - (title.length - 50); // Mengurangi ukuran font seiring dengan penambahan karakter
                return `${fontSize}px`;
            }
        },

        filterProducts() {
            Inertia.get('/apps/pos-app-transactions', {
                q: this.search
            });
        },

    },

    //composition API
    setup(props) {

        const selectedProduct = ref('');
        const selectedProductId = ref('');



        const search = ref('' || (new URL(document.location)).searchParams.get(`q`));


        //define state
        const sell_price = ref('');
        const product = ref({});
        const qty = ref(1);

        const stock = ref('Stock of the selected Product');

        const updateQuantity = (cart) => {
            // Kirim perubahan ke server untuk memperbarui keranjang
            Inertia.post('/apps/pos-app-transactions/editCartQuantity', {
                product_id: cart.product.id,
                cart_id: cart.id,
                qty: cart.qty
            }, {
                onSuccess: () => {
                    // Perbarui grandTotal dengan    nilai yang baru
                    grandTotal.value = props.carts_total;
                },
            });
        };

        // Tambahkan fungsi incrementDecrement ke dalam setup(props) Anda
        const incrementDecrement = (cart, increment) => {
            if (cart) {
                const newQuantity = cart.qty + increment;

                if (newQuantity >= 1) {
                    // Memperbarui kuantitas produk
                    cart.qty = newQuantity;

                    // Kirim perubahan ke server untuk memperbarui keranjang
                    Inertia.post('/apps/pos-app-transactions/editCartQuantity', {
                        cart_id: cart.id,
                        qty: cart.qty
                    }, {
                        onSuccess: () => {
                            // Perbarui grandTotal dengan nilai yang baru
                            grandTotal.value = props.carts_total;
                        },
                    });
                }
            }
        };









        //method "clearSearch"
        const clearSearch = () => {

            //set state "product" to empty object
            selectedProduct.value = ('');
            selectedProductId.value = ('');
            qty.value = '1';
            stock.value = 'Stock of the selected Product'

        }

        //define state grandTotal
        const grandTotal = ref(props.carts_total);

        //method add to cart
        const addToCart = (product) => {
            if (product) {
                // Kirim data ke server dengan produk yang dipilih
                Inertia.post('/apps/pos-app-transactions/addToCart', {
                    product_id: product.id,
                    qty: qty.value,
                    sell_price: product.sell_price,

                }, {
                    onSuccess: () => {
                        // // Bersihkan input Product Name dan reset nilai lainnya
                        // selectedProduct.value = props.products.v;
                        // selectedProductId.value = '';
                        // qty.value = 1;
                        grandTotal.value = props.carts_total;
                        // cash.value = 0;
                        // change.value = 0;
                    },
                });
            }
        }

        //method "destroyCart"
        const destroyCart = (cart_id) => {
            Inertia.post('/apps/pos-app-transactions/destroyCart', {
                cart_id: cart_id
            }, {
                onSuccess: () => {

                    //update state "grandTotal"
                    grandTotal.value = props.carts_total;

                    //set cash to "0"
                    cash.value = 0;

                    //set change to "0"
                    change.value = 0;
                },
            })
        }

        //define state "cash", "change" dan "discount"
        const cash = ref(0);
        const change = ref(0);
        const discount = ref(0);

        //method "setDiscount"
        const setDiscount = () => {

            //set grandTotal
            grandTotal.value = props.carts_total - discount.value;

            //set cash to "0"
            cash.value = 0;

            //set change to "0"
            change.value = 0;
        }

        //method "setChange"
        const setChange = () => {

            //set change
            change.value = cash.value - grandTotal.value;
        }

        const errors = ref(props.errors);
        const customer = ref('');
        //method "storeTransaction"
        const storeTransaction = () => {



            //HTTP request
            axios.post('/apps/pos-app-transactions/store', {
                customer: customer.value,
                discount: discount.value,
                grand_total: grandTotal.value,
                cash: cash.value,
                change: change.value
            })
                .then(response => {

                    //call method "clearSaerch"
                    clearSearch();

                    //set qty to "1"
                    qty.value = 1;

                    //set grandTotal
                    grandTotal.value = props.carts_total;

                    //set cash to "0"
                    cash.value = 0;

                    //set change to "0"
                    change.value = 0;

                    //set customer_id to ""
                    customer.value = '';

                    //show success alert
                    Swal.fire({
                        title: 'Success!',
                        text: 'Transaction Successfully.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000
                    })
                        .then(() => {

                            setTimeout(() => {

                                //print
                                window.open(`/apps/pos-app-transactions/print?invoice=${response.data.data.invoice}`, '_blank');

                                //reload page
                                location.reload();

                            }, 50);

                        })
                })
        }

        const storePendingTransaction = () => {
            // HTTP request




            axios.post('/apps/pos-app-transactions/pendingStore', {
                customer: customer.value,
                discount: discount.value,
                grand_total: grandTotal.value,
                cash: cash.value,
                change: change.value,
            })
                .then((response) => {
                    // Handle successful response
                    // Call method "clearSearch"
                    clearSearch();

                    // Set qty to "1"
                    qty.value = 1;

                    // Set grandTotal
                    grandTotal.value = response.data.carts_total; // Assuming the response contains 'carts_total'

                    // Set cash to "0"
                    cash.value = 0;

                    // Set change to "0"
                    change.value = 0;

                    // Set customer_id to ""
                    customer.value = '';

                    // Show success alert
                    Swal.fire({
                        title: 'Success!',
                        text: 'Pending Successfully.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                    });

                    // Reload the page after a delay (e.g., 2 seconds)
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500); // 2000 milliseconds = 2 seconds
                });
        };

        return {
            updateQuantity,
            product,
            clearSearch,
            qty,
            grandTotal,
            addToCart,
            destroyCart,
            cash,
            change,
            discount,
            customer,
            setDiscount,
            setChange,
            storeTransaction,
            search,
            selectedProduct,
            stock,
            selectedProductId,
            sell_price,
            errors,
            storePendingTransaction,
            incrementDecrement


        }

    }
}
</script>

<style scoped>
.card-title {
    font-size: 12px;
    /* Ukuran font default */
}

@media screen and (max-width: 800px) {
    .card-title {
        font-size: 9px;
        /* Ukuran font untuk layar lebar hingga 768px (misalnya, tablet) */
    }
}

@media screen and (max-width: 576px) {
    .card-title {
        font-size: 16px;
        /* Ukuran font untuk layar lebar hingga 576px (misalnya, perangkat seluler) */
    }
}
</style>
