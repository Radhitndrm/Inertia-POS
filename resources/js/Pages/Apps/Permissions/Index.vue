<template>
    <Head>
        <title>Permissions - Aplikasi Kasir</title>
    </Head>
    <main class="c-main">
        <div class="container-fluid">
            <div class="fade-in">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 rounded-3 shadow border-top-purple">
                            <div class="card-header">
                                <span class="font-weight-bold"><i class="fa fa-key"></i> PERMISSIONS</span>
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="handleSearch">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" v-model="search"
                                            placeholder="Search by Permission name...">
                                        <button class="btn btn-primary input-group-text" type="submit"> <i
                                                class="fa fa-search me-2"></i> Search</button>
                                    </div>
                                </form>
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Permission Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(permission, index) in permissions.data" :key="index">
                                            <td>{{ permission.name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <Pagination :links="permissions.links" align="end" />
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


import Pagination from '../../../Components/Pagination.vue';

//import Head and Link from Inertia
import { Head } from '@inertiajs/inertia-vue3';

//import ref from vue
import { ref } from 'vue';

//import inertia adapter
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    permissions: Object
})

const search = ref('' || (new URL(document.location)).searchParams.get('q'));

const handleSearch = () => {
    Inertia.get('/apps/permissions', {
        //send params "q" value from state "search"
        q: search.value,
    });
}

</script>

<!-- <script>
    //import layout
    import LayoutApp from '../../../layouts/App.vue';

    //import component pagination 
    import Pagination from '../../../Components/Pagination.vue';

    //import Head and Link from Inertia
    import { Head, Link } from '@inertiajs/inertia-vue3';

    //import ref from vue
    import { ref } from 'vue';

    //import inertia adapter
    import { Inertia } from '@inertiajs/inertia';

    export default {
        //layout
        layout: LayoutApp,

        //regiter component
        components: {
            Head,
            Link,
            Pagination
        },

        //props
        props: {
            permissions : Object,
        },

        setup(){
            //define state search
            const search = ref('' || (new URL(document.location)).searchParams.get('q'));

            //define method search
            const handleSearch = () => {
                Inertia.get('/apps/permissions',{
                    //send params "q" value from state "search"
                    q: search.value,
                });
            }

            //return
            return {
                search,
                handleSearch,
            }
        }
    }

</script> -->

<style></style>