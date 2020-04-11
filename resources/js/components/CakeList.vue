<template>
    <div class="container">
        <div class="mt-2 mb-4">
            <h4>Kereső</h4>
            <input class="form-control" type="text" v-model="search">
        </div>

        <div class="row justify-content-center">
            <template class="col col-lg-8" v-if="showList">
                <transition-group name="bounce" tag="div">
                    <div class="m-2 card" v-for="cake in filteredList" :key="cake.id">
                        <div class="card-header text-center">
                            <span class="h5">{{cake.name}}</span>
                        </div>
                        <div class="card-body">
                            <p class="font-weight-bold">Hozzávalók:</p>
                            <div v-for="ingredient in cake.required_ingredients">
                                <p>{{ingredient.pivot.ingredient_quantity}} {{ingredient.unit_type}} {{ingredient.name}}
                                    - {{ingredient.pivot.ingredient_price}} Ft</p>
                            </div>
                            <p class="font-weight-bold">Alapanyagok ára összesen: {{cake.ingredients_price_sum}} Ft</p>
                            <p class="font-weight-bold">Leírás: <br><br><span
                                class="font-weight-normal">{{cake.desc}}</span></p>
                        </div>
                    </div>
                </transition-group>

            </template>

            <div v-else class="my-3 d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
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
            this.fetchCakes();
        },

        data() {
            return {
                cakes: {},
                showList: false,
                search: '',
            }
        },

        computed: {
            filteredList() {
                return this.cakes.filter(cake => {
                    return cake.name.toLowerCase().includes(this.search.toLowerCase())
                })
            }
        },

        methods: {
            fetchCakes() {
                this.showList = false;
                console.log(this.cakesTest);
                axios.get('/cakelist')
                    .then((response) => {
                        console.log(response.data);
                        this.cakes = response.data;
                        this.showList = true;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
        }
    }
</script>
