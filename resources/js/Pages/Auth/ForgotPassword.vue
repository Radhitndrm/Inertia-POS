<template>
    <head>
        <title>Forgot Password - Aplikasi Kasir</title>
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
                            <h5>Reset Password</h5>
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
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-primary shadow-sm rounded-sm px-4 w-100" type="submit">SEND PASSWORD RESET LINK</button>
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
 import LayoutAuth from '../../layouts/Auth.vue'; //import file layout auth

 //import reactive
 import { reactive } from 'vue'; //digunakan untuk mendefinisikan sebuah state di dalam aplikasi berbasis vue

 //inertia adapter
 import { Inertia } from '@inertiajs/inertia'; //dimanfaatkan untuk melakukan http request ke server


//import heade and userForm form Inertia
import {
    Head, //component head bisa digunakan untuk membuat sebuah tag meta menjadi dinamis
    Link, //componeny link bisa digunakan untuk navigasi ke halaman lain secara SPA
} from '@inertiajs/inertia-vue3';

export default {
    //layout
    layout: LayoutAuth, //mengimport layouth yg sudah diimport

    //register component
    components:{
        Head,
        Link
    },//register head dan link agar bisa digunakan di dalam template

    props: {
        errors:Object, ///ini akan dikirimkan apabila terjadi error dalam validasi
        session:Object //ini akan dikirmkan ke laravel ketika ada sebuah session bernama success
    },
    //define composition API
    setup(){

        //define form state
        const form = reactive({
            email:'',
        }); //state form berfungsi untuk menyimpan data (email) yg diinputkan ke dalam form

        //submit method
        const submit = () => {
            Inertia.post('/forgot-password',{
                email: form.email
            });//di dlama method submit, melakukan http request menggunakan adapter inertia dengan method POST ke dlm URL ('/forgot-password') dengan mengirimkan data email
        }

        //return form state and submit method
        return {
            form,
            submit,
        }; //agar state form dan submit dapat digunakan kembali maka dilakukan return
    }
        //jangan lupa untuk melakukan npm run dev apabila file yg ada didalam folder resource/js dapat perubahan
}
</script>

<style>

</style>