<template>
   <div class="container">
      <div class="row">

         <div v-if="recipeSteps.stepOne"
              class="mx-auto card">
            <div class="card-header">
               <h2 class="text-center">{{newRecipe}}</h2>
            </div>
            <div class="card-body">
               <div class="row">
                  <div class="col col-6-lg col-2-xs">
                     <div class="form-group">
                        <label for="recipe-name">Recept neve:</label>
                        <input required type="text" v-model="recipeName" class="form-control" id="recipe-name"
                               aria-describedby="recipe-name"
                               placeholder="Írd be az új recept nevét">
                     </div>
                     <button @click="createNewRecipe" class="col btn btn-primary">Recept létrehozása</button>
                  </div>
               </div>
            </div>
         </div>

         <div v-else-if="recipeSteps.stepTwo"
              class="mx-auto card">
            <div class="card-header">
               <h2 class="text-center">{{newRecipe[0].name}}</h2>
            </div>
            <div class="card-body">
               <div v-for="(newIngredient, index) in newRecipe[0].ingredients" class="form-group">
                  <div class="row">
                     <div class="col col-lg-3 col-xs-12 text-center">
                        <p>Mennyiség</p>
                        <input class="form-control" type="number">
                     </div>
                     <div class="col col-lg-2 col-xs-12 text-center">
                        <p>Egység</p>
                        <input disabled class="form-control" type="text" value="g">
                     </div>
                     <div class="col col-lg-6 col-xs-12 text-center">
                        <p>Alapanyag</p>
                        <select class="form-control" id="exampleFormControlSelect1">
                           <option>Cukor</option>
                           <option>2</option>
                        </select>
                     </div>
                     <div class="col col-lg-1 col-xs-12 text-center">
                        <p class="text-white">-</p>
                        <p @click="removeIngredientRow(index)" class="btn btn-danger">-</p>
                     </div>
                  </div>
               </div>
               <div class="col col-lg-12 col-xs-12">
                  <div class="mt-4 mb-2 alert alert-success">
                     <h4 class="">Új alapanyag hozzáadása<span @click="addNewIngredientRow" class="float-right btn btn-success">+</span>
                     </h4>
                  </div>
               </div>
            </div>
            <div class="card-footer">
               <div class="row">
                  <button @click="createNewRecipe" class="col btn btn-success m-2">Recept feltöltése</button>
               </div>
            </div>
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
</template>

<script>
   export default {
      mounted() {

      },
      name: "CreateCakeRecipe",

      data() {
         return {
            recipeSteps: {
               stepOne: false,
               stepTwo: true,
            },
            recipeName: '',
            serverResponseData: null,
            errors: [],
            newRecipe: [
               {
                  id: null,
                  name: 'Recept Feltöltése',
                  desc: null,
                  ingredients: [
                     {name: 'default', quantity: 0},
                     {name: 'default', quantity: 0},
                  ]
               }
            ]
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
                     this.validateServerResponseOnSuccess(response.data);
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

         validateServerResponseOnSuccess(responseData) {
            this.createNewRecipeObject(responseData);
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
         },

         createNewRecipeObject(responseData) {
            this.newRecipe.id = responseData.new_recipe_id;
            this.newRecipe.name = this.newRecipeNameFirstLetterToUpperCase(responseData.new_recipe_name);
            console.log(this.newRecipe.id);
            console.log(this.newRecipe.name);
         },

         newRecipeNameFirstLetterToUpperCase(newRecipeName) {
            return newRecipeName.charAt(0).toUpperCase() + newRecipeName.slice(1);
         },

         addNewIngredientRow() {
            this.newRecipe[0].ingredients.push({
               name: 'default',
               quantity: 0
            });
         },

         removeIngredientRow(key) {
            this.newRecipe[0].ingredients.splice(key, 1);
         }
      },
   }
</script>

<style scoped>

</style>
