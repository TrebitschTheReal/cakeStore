<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Cakes</div>

                    <div v-if="showList" class="card-body" v-model="cakes">{{ cakes }}</div>

                    <div v-else class="my-3 d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        created() {

        },

        mounted() {
            this.getAllCakes();
        },

        data() {
            return {
                cakes: null,
                showList: false
            }
        },

        methods: {
            getAllCakes() {
                this.showList = false;
                console.log(this.cakesTest);
                axios.get('/cakelist')
                    .then((response) => {
                        this.cakes = response.data;
                        this.showList = true;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        }
    }
</script>
