<template>
   <div class="container">
      <div class="row">

         <div v-if="recipeSteps.stepOne"
              class="mx-auto card">
            <div class="card-header">
               <h2 class="text-center">{{newRecipe[0].name}}</h2>
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
                        <input class="form-control" v-model="newIngredient.quantity" type="number">
                     </div>
                     <div class="col col-lg-2 col-xs-12 text-center">
                        <p>Egység</p>
                        <input disabled class="form-control" type="text" value="g">
                     </div>
                     <div class="col col-lg-6 col-xs-12 text-center">
                        <p>Alapanyag</p>
                        <select class="form-control" v-model="newIngredient.name" id="exampleFormControlSelect1">
                           <option v-for="(ingredient, index) in availableIngredients">{{ingredient.name}}</option>
                        </select>
                     </div>
                     <div class="col col-lg-1 col-xs-12 text-center">
                        <p class="text-white">-</p>
                        <p @click="removeIngredientRow(newIngredient.id)" class="btn btn-danger">-</p>
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
         this.fetchInredientsList()
      },

      name: "CreateCakeRecipe",

      data() {
         return {
            //stepOne = új recept regisztrálása
            //stepTwo = alapanyag hozzárendelés
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
                     {id: 0, name: 'default', quantity: 0},
                     {id: 1, name: 'default', quantity: 0},
                  ]
               }
            ],
            availableIngredients: [],
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
            this.fetchInredientsList();
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

         //A feltöltés lépéseit kezeli.
         //register  -  első lépés
         //pending   -  spinner
         //fill      -  recept hozzárendelés
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
            this.newRecipe[0].id = responseData.new_recipe_id;
            this.newRecipe[0].name = this.newRecipeNameFirstLetterToUpperCase(responseData.new_recipe_name);
         },

         newRecipeNameFirstLetterToUpperCase(newRecipeName) {
            return newRecipeName.charAt(0).toUpperCase() + newRecipeName.slice(1);
         },

         addNewIngredientRow() {
            console.log(this.newRecipe[0].ingredients);
            let newId = 0;

            if(this.newRecipe[0].ingredients.length === 0) {
               this.newRecipe[0].ingredients.push({
                  id: newId,
                  name: 'default',
                  quantity: 0
               });
            }
            else {
               let lastElement = this.newRecipe[0].ingredients.length-1;
               newId = (this.newRecipe[0].ingredients[lastElement].id) + 1;

               this.newRecipe[0].ingredients.push({
                  id: newId,
                  name: 'default',
                  quantity: 0
               });
            }
         },

         removeIngredientRow(itemId) {
            console.log(itemId);
            let id = itemId;
            let targetKey = 0;

            $.each(this.newRecipe[0].ingredients, function (key, ingredient) {
               if(ingredient.id === id) {
                  targetKey = key;
                  return false;
               }
            });

            this.newRecipe[0].ingredients.splice(targetKey, 1);
         },

         fetchInredientsList() {
            axios.get('/fetchingredients')
               .then((response) => {
                  this.availableIngredients = response.data;
                  console.log(this.availableIngredients);
               })
               .catch((error) => {
                  console.log(error);
                  console.log('Backend error: ', error.response.data);
                  console.log('Statuscode: ', error.response.status);
                  console.log('Response headers: ', error.response.headers);
               });
         }
      },
   }
</script>

<style scoped>

</style>
