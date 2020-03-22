<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>
                    <div class="card-body" v-model="cakes">{{ cakes }}</div>
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
                cakes: null
            }
        },

        methods: {
            getAllCakes() {
                console.log(this.cakesTest);
                axios.get('/cakelist')
                    .then((response) => {
                        this.cakes = response.data;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        }
    }
</script>
