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
                        <input required type="text" v-model="recipeName" class="form-control" id="recipe-name"
                               aria-describedby="recipe-name"
                               placeholder="Írd be az új recept nevét">
                     </div>
                     <button @click="createNewRecipe" class="col btn btn-primary">Recept létrehozása</button>
                  </div>
                  <div v-else-if="recipeSteps.stepTwo" class="col col-6-lg col-2-xs">
                     <div class="col col-6-lg col-2-xs">
                        <div class="form-group">
                           <label for="actualElState">Aktuális ELMŰ állás</label>
                           <input type="number" class="form-control" id="actualElState" aria-describedby="emailHelp"
                                  placeholder="">
                        </div>
                        <div class="form-group">
                           <label for="actualGasState">Aktuális Gáz állás</label>
                           <input type="number" class="form-control" id="actualGasState" aria-describedby="emmarchElStateailHelp"
                                  placeholder="">
                        </div>
                        <div class="form-group">
                           <label for="actualWaterCanState">Aktuális Víz állás</label>
                           <input type="number" class="form-control" id="actualWaterCanState" aria-describedby="emailHelp"
                                  placeholder="">
                        </div>
                     </div>
                     <div class="col col-6-lg col-2-xs">
                        <div class="form-group">
                           <label for="actualElState">Márciusi ELMŰ állás</label>
                           <input type="number" class="form-control" id="marchElState" aria-describedby="emailHelp"
                                  placeholder="">
                        </div>
                        <div class="form-group">
                           <label for="actualGasState">Márciusi Gáz állás</label>
                           <input type="number" class="form-control" id="marchGasState" aria-describedby="emailHelp"
                                  placeholder="">
                        </div>
                        <div class="form-group">
                           <label for="actualWaterCanState">Márciusi Víz állás</label>
                           <input type="number" class="form-control" id="marchWaterCanState" aria-describedby="emailHelp"
                                  placeholder="">
                        </div>
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
            errors: [],
         }
      },

      methods: {
         createNewRecipe() {
            if (this.validateNewRecipeName()) {
               this.handleSteps('pending');

               axios.post('/registernewrecipe', {
                  recipeName: this.recipeName,
               })
                  .then((response) => {
                     this.validateServerResponseOnSuccess();
                     console.log(response);
                  })
                  .catch((error) => {
                     this.validateServerResponseOnFail(error.response.status);
                     console.log(error);
                     console.log('Backend error: ', error.response.data);
                     console.log('Statuscode: ', error.response.status);
                     console.log('Response headers: ', error.response.headers);
                  });


            } else {
               alert('Legalább 3 karakter!');
            }
         },

         validateNewRecipeName() {
            let validationState = false;

            if (this.recipeName.length < 1) {
               return validationState;
            }

            validationState = true;
            return validationState;
         },

         validateNewRecipeContent() {

         },

         validateServerResponseOnSuccess() {
            this.handleSteps('fill');
         },

         validateServerResponseOnFail(statuscode) {
            if (statuscode === 409) {
               console.log('Error: Már létezik!');
            }
            else if(statuscode === 406) {
               console.log('Error: Nem megfelelő név!')
            }

            this.handleSteps('register');
         },

         handleSteps(step) {
            if (step === 'register') {
               this.recipeSteps.stepOne = true;
            } else if (step === 'pending') {
               this.recipeSteps.stepOne = false;
               this.recipeSteps.stepOne = false;
            } else if (step === 'fill') {
               this.recipeSteps.stepTwo = true;
            }
         }
      },
   }
</script>

<style scoped>

</style>
