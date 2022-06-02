<template>
    <div id="app">
               <header class="flex items-center justify-between flex-wrap bg-teal-700 p-6">
                <div class="flex items-center flex-shrink-0 text-white mr-6">
                    <!-- <svg class="fill-current h-8 w-8 mr-2" width="54" height="54" viewBox="0 0 54 54" xmlns="http://www.w3.org/2000/svg"><path d="M13.5 22.1c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05zM0 38.3c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05z"/></svg> -->
                    <span class="font-semibold text-xl tracking-tight">MongoSV</span>
                </div>
                <div class="block lg:hidden">
                    <button class="flex items-center px-3 py-2 border rounded text-teal-200 border-teal-400 hover:text-white hover:border-white">
                        <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                    </button>
                </div>
            </header> 
        <div class="container mt-8 m-auto">
            <!-- progress bar -->
            <div class="flex flex-wrap mx-3 mb-11 justify-center">
                <div class="w-full md:w-1/2 px-3">
                    <div class="px-3 py-3 rounded-lg shadow bg-white border-2 border-gray-300">
                        <div class="text-center mb-3">
                            <h1 class="text-2xl font-semibold">MongoSV</h1>
                            <p class="text-gray-600">Upload your csv file and Insert the data to  mongoDB</p>
                        </div>
                        <div class="text-center mb-3">
                            <div class="w-full">
                                <div class="bg-gray-200 rounded-md" v-if="processing">
                                    <span class=" w-11 h-11 rounded-full bg-green-800">
                                    </span>
                                    <span>
                                        <span class="text-green-800 text-sm font-semibold">{{process.status}}:</span>
                                        <span class="text-gray-600 text-sm">{{process.message}}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <div class="w-full">
                                <div class="bg-gray-200 rounded-md h-6" v-if="loading">
                                    <div class="w-full loading">
                                    <span>
                                        <span class="text-white text-sm font-semibold">Loading </span>
                                        <span class="text-gray-100 text-sm">Uplaoding Databale</span>
                                    </span>
                                    </div>
                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <form @submit.prevent="onSubmit" >
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                        Database
                    </label>
                    <input v-model="file.db" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-first-name" type="text" placeholder="Jane">
                </div>
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                        Collection
                    </label>
                    <input v-model="file.collection" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" type="text" placeholder="Doe">
                </div>
                
            </div>
            <!-- file upload field -->
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-avatar">
                        Avatar
                    </label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-avatar" type="file" mane="csv" @input="file.csv = $event.target.files[0]">
                </div>
            </div>

            <!-- button -->
            <div class="flex flex-wrap -mx-3 mb-2">

                <div class="w-full px-3">
                    <button class="shadow bg-teal-500 hover:bg-teal-400 focus:shadow-outline focus:outline-none text-white font-bold py-2 px-4 rounded" type="submit">
                        Submit
                    </button>
                </div>
            </div>
            </form>
            <!-- end header -->
        </div>
    </div>
</template>
<script>
 import { Head } from '@inertiajs/inertia-vue3'

 
    export default {
        name: 'Index',
        components: {
            Head
        },
        data() {
            return {
                message: 'Hello Vue!',
                file: {
                    db: '',
                    collection: '',
                    // password: '',
                    // birthday: '',
                    csv: null
                },
                processing: false,
                process:{},
                loading: false,
            }
        },
        
        methods: {
            onSubmit() {
                const formData = new FormData()
                formData.append('db', this.file.db)
                formData.append('collection', this.file.collection)
                formData.append('csv', this.file.csv)

                const headers = { 'Content-Type': 'multipart/form-data' };
                this.loading = true
                axios.post('/csv', formData, { headers })
                    .then(response => {
                        this.processing = true;

                    })
                    .catch(error => {
                        console.log(error)
                    })
                console.log(formData)
            }
        },
        mounted() {
                        
            var pusher = new Pusher('97d99defd9620ae958cf', {
            cluster: 'eu'
            });
            let vm = this;

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(data) {
                console.log(data.data);
                vm.process.status = data.data['status'];
                vm.process.message = data.data['message'];
                vm.loading = false;
                vm.processing = true;

            });

        
        }


    }

</script>
<style scoped>
.loading {
    position: relative;
    width: 10%;
    height: 100%;
    border: 2px solid #fff;
    background: green;
    animation: loading 3s linear infinite;
    border-radius: 5%;
    color: #fff;
}
/* keyframe */
@keyframes loading {
    0% {
        width: 0%;
    }
    100% {
        width: 100%;
    }
}
</style>