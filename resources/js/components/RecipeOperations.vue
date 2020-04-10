<template>
   <div class="container">
      <div class="row">

         <!-- Recept regisztrálása -->
         <stepRegister v-if="recipeSteps.stepRegister"
                       @registerNewRecipe="registerNewRecipeToDB($event)"
         />

         <!-- Recept managelése -->
         <stepManageRecipe v-else-if="recipeSteps.stepManageRecipe"
                           :recipe="recipe"
                           @updateRecipe="updateRecipeToDB($event)"
         />

         <!-- Sikeres recept művelet -->
         <successRecipeUploadAlert v-else-if="recipeSteps.stepSuccessRecipeOperation"
                                   :recipeSuccessMessage="recipeSuccessMessage"
         />

         <!-- Töltés animáció -->
         <loadingSpinner v-else/>

      </div>
   </div>


</template>

<script>
   import stepRegister from './steps/stepRegister'
   import stepManageRecipe from './steps/stepManageRecipe'
   import loadingSpinner from './loadingSpinner'
   import successRecipeUploadAlert from './successRecipeUploadAlert'

   export default {
      name: "RecipeOperations",
      components: {
         stepRegister,
         stepManageRecipe,
         loadingSpinner,
         successRecipeUploadAlert
      },
      data: function () {
         return {
            recipeSuccessMessage: '',
            recipeSteps: {
               stepRegister: true,
               stepManageRecipe: false,
               stepSuccessRecipeOperation: false,
            },

            recipe: {
               id: null,
               name: 'Recept regisztrálása',
               desc: null,
               ingredients: [
                  {
                     listID: 0,
                     id: null,
                     name: null,
                     quantity: null,
                     unitType: null,
                     unitPrice: null,
                     sumIngredientPrice: null,
                  },

                  {
                     listID: 1,
                     id: null,
                     name: null,
                     quantity: null,
                     unitType: null,
                     unitPrice: null,
                     sumIngredientPrice: null,
                  },
               ],
            },
         }
      },

      methods: {
         registerNewRecipeToDB(newRecipeName) {
            this.handleSteps('pending');
            axios.post('/registernewrecipe', {
               name: newRecipeName,
            })
               .then((response) => {
                  this.validateServerResponseOnSuccess(response.data);
                  console.log(response);
               })
               .catch((error) => {
                  this.validateServerResponseOnFail(error);
                  console.log(error);
                  console.log('Backend error: ', error.response.data);
                  console.log('Statuscode: ', error.response.status);
                  console.log('Response headers: ', error.response.headers);
               });
         },

         updateRecipeToDB(modifiedRecipe) {
            this.recipe = modifiedRecipe;
            console.log('ASDASDASDASDASDASDASJASDSDDS', this.recipe);
            this.handleSteps('pending');

            axios.post('/fillnewlycreatedrecipe', {
               newRecipe: this.recipe,
            })
               .then((response) => {
                  //this.validateServerResponseOnSuccess(response.data);
                  this.recipeSuccessMessage = 'Sikeres recept feltöltés!';
                  this.handleSteps('finish');
               })
               .catch((error) => {
                  //this.validateServerResponseOnFail(error.response.status);
                  console.log(error);
                  console.log('Backend error: ', error.response.data);
                  console.log('Statuscode: ', error.response.status);
                  console.log('Response headers: ', error.response.headers);
               });
         },

         handleSteps(step) {
            if (step === 'register') {
               this.recipeSteps.stepRegister = true;
            } else if (step === 'pending') {
               this.recipeSteps.stepRegister = false;
               this.recipeSteps.stepManageRecipe = false;
               this.recipeSteps.stepSuccessRecipeOperation = false;
            } else if (step === 'fill') {
               this.recipeSteps.stepManageRecipe = true;
            } else if (step === 'finish') {
               this.recipeSteps.stepSuccessRecipeOperation = true;
            }
         },

         validateServerResponseOnSuccess(responseData) {
            this.registerNewRecipeObject(responseData);
            this.handleSteps('fill');
         },

         validateServerResponseOnFail(error) {
            if (error.response.status === 422) {
               alert(error.response.data.errors.name);
            }
            this.handleSteps('register');
         },

         registerNewRecipeObject(responseData) {
            this.recipe.id = responseData.new_recipe_id;
            this.recipe.name = this.newRecipeNameFirstLetterToUpperCase(responseData.new_recipe_name);
            console.log(this.recipe.id);
            console.log(this.recipe.name)
         },

         newRecipeNameFirstLetterToUpperCase(newRecipeName) {
            return newRecipeName.charAt(0).toUpperCase() + newRecipeName.slice(1);
         },
      },
   }
</script>
