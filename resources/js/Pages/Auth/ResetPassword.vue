<template>
    <head>
        <title>Reset Password - Aplikasi Kasir</title>
    </head>
    <div class="col-md-4">
        <div class="fade-in">
            <div class="text-center mb-4">
                <a href="" class="text-dark text-decoration-none">
                    <img src="/images/cash-machine.png" width="70">
                    <h3 class="mt-2 font-weight-bold">Aplikasi Kasir</h3>
                </a>
            </div>
            <div class="card-group">
                <div class="card border-top-purple border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="text-start">
                            <h5>Update Password</h5>
                        </div>
                        <hr>
                        <div v-if="session.status" class="alert alert-success mt-2">
                            {{ session.status }}
                        </div>
                        <form @submit.prevent="submit">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                                <input class="form-control" v-model="form.email" :class="{'is-invalid': errors.email}" type="email" placeholder="Email Address">
                            </div>
                            <div v-if="errors.email" class="alert alert-danger">
                                {{ errors.email }}
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </div>
                                <input class="form-control" v-model="form.password" :class="{'is-invalid': errors.password}" type="password" placeholder="Password">
                            </div>
                            <div v-if="errors.password" class="alert alert-danger">
                                {{ errors.password }}
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </div>
                                <input class="form-control" v-model="form.password_confirmation" :class="{'is-invalid': errors.password_confirmation}" type="password" placeholder="Password Confirmation">
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary shadow-sm rounded-dm px-4 w-100" type="submit">Update Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    //import layout
    import LayoutAuth from '../../layouts/Auth.vue'; //mengimport layout auth

    //import reactive
    import { reactive } from 'vue'; //import reactive API agar bisa mendefiniskan sebuah state dalam aplikasi berbasis vue

    //inertia adapter
    import { Inertia } from '@inertiajs/inertia'; //mengimport adapter inertia agar bisa melakukan http request ke dlm server

    //import Head and useForm Inertia
    import {
        Head, //componednt head digunakan untuk membuat tag meta menjadi dinamis
        Link //component link digunakan untuk bisa navigasi ke halaman lain secara dinamis
    } from '@inertiajs/inertia-vue3';

    export default {
        //layout
        layout: LayoutAuth, //register layout yg diatas tadi

        //regitster component
        components: {
            Head,
            Link
        }, //register kedua komponen agar bisa digunakan di dlam template

        props: {
            errors: Object, //props ini akan diberikan ke laravel ketika terjadi error validasi
            route: Object, //props ini digunakan untuk mendapatkan token yang berada pada url
            session: Object, //props ini akan diberikan ke laravel ketika ada sebuah session yang bernama success
        },

        //define composition API (parameter props ditambahkan fungsinya agar bisa menggunakan props dalam method setup)
        setup(props){

            //define form state
            const form = reactive({
                email: props.route.query.email,
                password: '',
                password_confirmation: '',
                token: props.route.params.token,
            }); //object email dan token di set default menjadi route.yang mana data email dan token didapatkan dari url browser

            //submit method
            const submit = () => {

                //send data to server
                Inertia.post('/reset-password',{

                    //data
                    email: form.email,
                    password: form.password,
                    password_confirmation: form.password_confirmation,
                    token: form.token,
                }) //melakukan http request menggunakan inertia adapter dgn method post ke dalam url ('/reset-password') dgn mengirimkan data email password,password_confrim,token
            }

            //return form statr and submit method
            return {
                form,
                submit,
            };  //agar state form dan submit bisa digunakan di dlm template diperlukan return
        }
    //jgn lupa lakukan npm run dev apabila file dari resource/js mendapatkan perubahan
    }
</script>

<style>

</style>