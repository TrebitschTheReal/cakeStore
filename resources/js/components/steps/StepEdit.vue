<template>
   <div class="mx-auto col">
      <div class="m-lg-4 m-xs-2">
         <h2 class="text-center">Recept módosítása</h2>
         <hr>
      </div>
      <div class="col-lg-6 col-xs-12 form-group mx-auto">
         <input required
                type="text"
                v-model="search"
                class="form-control" id="recipe-name"
                aria-describedby="recipe-name"
                placeholder="Kezd el írni a recept nevét">
      </div>
      <tableView v-if="showList"
                 :filteredList="this.filteredList"
                 :tableData="'recipe'"
                 @modifyRecipe="modifyRecipe($event)"
      />
      <spinner v-else/>
   </div>
</template>

<script>
   import spinner from '../partials/loadingSpinner';
   import tableView from '../partials/tableView';

   export default {
      name: "stepModify",

      mounted() {
         this.fetchRecipes();
      },

      components: {
         spinner,
         tableView,
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

