<template>
   <div class="mx-auto card">
      <div class="card-header">
         <h2 class="text-center">Recept regisztrálása</h2>
      </div>
      <div class="card-body">
         <div class="row">
            <div class="col col-6-lg col-2-xs">
               <div class="form-group">
                  <label for="recipe-name">Recept neve:</label>
                  <input required type="text"
                         v-model="actualRecipeName"
                         class="form-control" id="recipe-name"
                         aria-describedby="recipe-name"
                         placeholder="Írd be az új recept nevét">
               </div>
               <button @click="registerNewRecipe" class="col btn btn-primary">Recept regisztrálása</button>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
   export default {
      name: "stepRegister",

      props: {
         newRecipeName: String
      },

      data: function () {
         return {
            actualRecipeName: '',
         }
      },

      methods: {
         validateNewRecipeName() {

            //Validation ...
            //SUCCESS
            this.registerNewRecipe();
         },

         registerNewRecipe: function () {
            this.$emit('registerNewRecipe', this.actualRecipeName)
         }
      }
   }
</script>
