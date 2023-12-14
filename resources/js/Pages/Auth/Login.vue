<template>
    <Head>
        <title>Login Account - Aplikasi Kasir</title>
    </Head>
    <div class="col-md-4">
        <div class="fade-in">
            <div class="text-center mb-4">
                <a href="" class="text-dark text-decoration-none">
                    <img src="/images/cash-machine.png" width="70">
                    <h3 class="mt-2 font-weight-bold">APLIKASI KASIR</h3>
                </a>
            </div>
            <div class="card-group">
                <div class="card border-top-purple border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="text-start">
                            <h5>LOGIN ACCOUNT</h5>
                            <p class="text-muted">Sign In to your account</p>
                        </div>
                        <hr>
                        <div v-if="session.status" class="alert alert-success mt-2">
                            {{ session.status }} //akan menampilkan alert apabila status(success)
                        </div>
                        <form @submit.prevent="submit">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                                <input class="form-control" v-model="form.email" :class="{ 'is-invalid': errors.email }"
                                    type="email" placeholder="Email Address">
                            </div>
                            <div v-if="errors.email" class="alert alert-danger">
                                {{ errors.email }}//menampilkan error validasi
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fa fa-lock"></i>
                                    </span>
                                </div>
                                <input class="form-control" v-model="form.password"
                                    :class="{ 'is-invalid': errors.password }" type="password" placeholder="Password">
                            </div>
                            <div v-if="errors.password" class="alert alert-danger">
                                {{ errors.password }}//menampilkan error validasi
                            </div>
                            <div class="row">
                                <div class="col-12 mb-3 text-end">
                                    <Link href="/forgot-password">Forgot Password?</Link>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary shadow-sm rounded-sm px-4 w-100"
                                        type="submit">LOGIN</button>
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
import { reactive } from 'vue'; //import reactive API dari vue.js. ini akan digunakan untuk mendefinisikan sebuah state dlam aplikasi berbasis vue

//import inertia adapter
import { Inertia } from '@inertiajs/inertia'; //import adapter dari inertia. ini bisa dimanfaatkan untuk melakukan http request ke dalam server
//http request merupakan suatu kondisi dimana server membaca apa yang dikirimi oleh client melalui aplikasi web server
//import Heade and useForm from Inertia
import {
    Head, //mengimport component head agar bisa digunakan untuk membuat sebuah meta tag menjadi dinamis
    Link,  //mengimport component link agar digunakan untuk melakukan navigasi ke halaman lain secara SPA
} from '@inertiajs/inertia-vue3';

export default {

    //layout
    layout: LayoutAuth, //register layout yang sudah kita import diatas

    //register component
    components: {
        Head,
        Link
    }, // register component head dan link agar bisa digunakan di dalam template

    props: {
        errors: Object, //berfungsi ketika terjadi error atau validasi request yang tidak sesusai props ini akan dikirimkan oleh laravel 
        session: Object //untuk props session akan dikirimkan oleh laravel ketika sebuah ada session bernama status 
    },

    //define composition API
    setup() {

        //define form state
        const form = reactive({
            email: '',
            password: '',
        }); //disini state email dan password akan digunakan untuk menyimpan data yang diinputkan ke dalam form

        //submit method
        const submit = () => {

            //send data to server
            Inertia.post('/login', {

                //data
                email: form.email,
                password: form.password,
            }); //melakukan http request ke dalam url ('/login') dengan mengirimkan kedua data email dan password
        }

        //return form state and submit method
        return {
            form,
            submit,
        }; //agar state form dan submit bisa digunakan kembali ke dalam template maka kita perlu melakukan return di dalam method setup     
        //jgn lupa lakukan npm run dev apabila file dari resource/js mendapatkan perubahan
    }

}
</script>

<style></style>