<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Example Component</div>

                    <div class="card-body">
                        {{cakes}}
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
                cakes: null
            }
        },

        methods: {
            getAllCakes() {
                axios.get('/cakelist')
                    .then((response) => {
                        self.cakes = response.data;
                        console.log(response.data);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        }
    }
</script>
