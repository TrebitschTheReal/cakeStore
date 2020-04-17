<template>
   <div class="mx-auto">
      <div class="m-lg-4 m-xs-2">
         <h2 class="text-center">Recept módosítása</h2>
         <hr>
      </div>
      <div class="col-6 form-group mx-auto">
         <input required
                type="text"
                v-model="search"
                class="form-control" id="recipe-name"
                aria-describedby="recipe-name"
                placeholder="Kezd el írni a recept nevét">
      </div>
      <table v-if="showList" class="col col-lg-10 mx-auto table">
         <thead class="thead-dark">
         <tr>
            <th scope="col">#</th>
            <th scope="col">Recept neve</th>
            <th scope="col">Anyag költség</th>
            <th scope="col">Feltöltés dátuma</th>
            <th scope="col">Utolsó módosítás dátuma</th>
            <th scope="col"></th>
            <th scope="col"></th>
         </tr>
         </thead>
            <tbody>
<!--
            <transition-group name="fade" mode="out-in" tag="tr">
-->
               <tr v-for="cake in filteredList" :key="cake.id">
                  <th scope="row">{{cake.id}}</th>
                  <td>{{cake.name}}</td>
                  <td>{{cake.ingredients_price_sum}} Ft</td>
                  <td>{{cake.created_at}}</td>
                  <td>{{cake.updated_at}}</td>
                  <td>
                     <button @click="modifyRecipe(cake.id)" class="btn btn-warning">Módosítás</button>
                  </td>
                  <td>
                     <button class="btn btn-danger">Törlés</button>
                  </td>
               </tr>
<!--
            </transition-group>
-->
            </tbody>
      </table>
      <spinner v-else/>
   </div>
</template>

<script>
   import spinner from '../loadingSpinner'

   export default {
      name: "stepModify",

      mounted() {
         this.fetchRecipes();
      },

      components: {
         spinner,
      },

      data() {
         return {
            modifiableRecipeId: null,
            fetchedRecipes: {},
            showList: false,
            search: '',
         }
      },

      computed: {
         filteredList() {
            return this.fetchedRecipes.filter(cake => {
               return cake.name.toLowerCase().includes(this.search.toLowerCase())
            })
         },
      },

      methods: {
         modifyRecipe(cakeId) {
            this.$emit('modifyRecipe', cakeId)
         },


         fetchRecipes() {
            axios.get('/cakelist')
               .then((response) => {
                  this.fetchedRecipes = response.data;
                  this.showList = true;
               })
               .catch((error) => {
                  console.log(error);
               });
         },
      }
   }
</script>

