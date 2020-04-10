<template>
   <div class="mx-auto card">
      <div class="card-header">
         <h2 class="text-center">Recept módosítása</h2>
      </div>
      <div class="card-body">
         <div class="row">
            <div class="col col-6-lg col-2-xs">
               <div class="form-group">
                  <label for="recipe-name">Válassz receptet:</label>
                  <input required
                         type="number"
                         v-model="modifiableRecipeId"
                         class="form-control" id="recipe-name"
                         aria-describedby="recipe-name"
                         placeholder="Írd be a recept id-ját">
               </div>
               <button @click="modifyRecipe" class="col btn btn-warning">Kiválasztott recept módosítása</button>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
   export default {
      name: "stepModify",

      mounted() {
         this.fetchRecipes()
      },

      data: function () {
         return {
            modifiableRecipeId: null,
            fetchedRecipes: {}
         }
      },

      methods: {
         modifyRecipe() {
            this.$emit('modifyRecipe', this.modifiableRecipeId)
         },

         fetchRecipes() {
            axios.get('/cakelist')
               .then((response) => {
                  this.fetchedRecipes = response.data;
                  console.log(this.fetchedRecipes);
               })
               .catch((error) => {
                  console.log(error);
               });
         }
      }
   }
</script>

