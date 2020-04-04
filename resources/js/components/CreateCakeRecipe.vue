<template>
   <div class="container">
      <div class="row">
         <div class="mx-auto card">
            <div class="card-header">
               <h2>Recept Feltöltése</h2>
            </div>
            <div class="card-body">
               <div class="row">
                  <div v-if="recipeSteps.stepOne" class="col col-6-lg col-2-xs">
                     <div class="form-group">
                        <label for="recipe-name">Recept neve:</label>
                        <input required type="text" v-model="recipeName" class="form-control" id="recipe-name" aria-describedby="recipe-name"
                               placeholder="Írd be az új recept nevét">
                     </div>
                     <button @click="createNewRecipe" class="col btn btn-success">Recept létrehozása</button>
                  </div>
                  <div v-else-if="recipeSteps.stepTwo" class="col col-6-lg col-2-xs">
                     <div class="form-group">

                     </div>
                     <button @click="createNewRecipe" class="col btn btn-success">Recept feltöltése</button>
                  </div>
                  <div v-else class="col col-6-lg col-2-xs">
                     <div class="my-3 d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                           <span class="sr-only">Loading...</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
   export default {
      mounted() {

      },
      name: "CreateCakeRecipe",

      data() {
         return {
            recipeSteps: {
               stepOne: true,
               stepTwo: false,
            },
            recipeName: '',
            serverResponseData: null,
         }
      },

      methods: {
         createNewRecipe() {
            if(this.validateNewRecipeName()) {
               this.handleSteps('pending');

               axios.post('/registernewrecipe', {
                  recipeName: this.recipeName,
               })
                  .then((response) => {
                     this.validateServerResponse(response.data);
                     console.log(response);
                  })
                  .catch(function (error) {
                     console.log(error);
                  });


            } else {
               alert('Legalább 3 karakter!');
            }
         },

         validateNewRecipeName() {
            let validationState = false;

            if(this.recipeName.length < 3) {
               return validationState;
            }

            validationState = true;
            return validationState;
         },

         validateServerResponse(responseData) {
            this.handleSteps('fill');
         },

         validateNewRecipeContent() {

         },

         handleSteps(step) {
            if(step === 'register') {
               this.recipeSteps.stepOne = true;
               this.recipeSteps.stepOne = false;
            }
            else if (step === 'pending') {
               this.recipeSteps.stepOne = false;
               this.recipeSteps.stepOne = false;
            }
            else if (step === 'fill') {
               this.recipeSteps.stepOne = false;
               this.recipeSteps.stepOne = true;
            }
         }
      },
   }
</script>
