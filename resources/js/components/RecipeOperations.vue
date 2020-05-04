<template>
   <div class="container">
      <div class="row">

         <!-- Transitiont használunk az elemek beúsztatására. mode="out-in" -> amikor az egyik eltűnik CSAK akkor jöhet a másik-->
         <transition name="slide-fade" mode="out-in">
            <!-- Recept művelet kiválasztása -->
            <stepOperationChoose v-if="recipeSteps.stepOperationChoose"
                                 @choosenOperation="handleSteps($event)"
            />

            <!-- Recept regisztrálása -->
            <stepRegister v-else-if="recipeSteps.stepRegister"
                          @registerNewRecipe="registerNewRecipeObject($event)"
            />

            <!-- Recept módosítása -->
            <stepEdit v-else-if="recipeSteps.stepEdit"
                      @modifyRecipe="editRecipe($event)"

            />

            <!-- Recept managelése -->
            <stepManageRecipe v-else-if="recipeSteps.stepManageRecipe"
                              :recipe="recipe"
                              @updateSuccessful="updateSuccessful()"
            />

            <!-- Alapanyag feltöltése -->
            <stepIngredientUpload v-else-if="recipeSteps.stepIngredientUpload"
            />

            <!-- Sikeres recept művelet -->
            <successRecipeUploadAlert v-else-if="recipeSteps.stepSuccessRecipeOperation"
                                      :recipeSuccessMessage="recipeSuccessMessage"
            />

            <!-- Loading spinner -->
            <loadingSpinner v-else/>
         </transition>

      </div>
   </div>


</template>

<script>
   import stepRegister from './steps/StepRegister'
   import stepManageRecipe from './steps/StepManageRecipe'
   import loadingSpinner from './partials/loadingSpinner'
   import successRecipeUploadAlert from './partials/successRecipeUploadAlert'
   import stepOperationChoose from "./steps/StepOperationChoose"
   import stepEdit from './steps/StepEdit'
   import stepIngredientUpload from './steps/StepIngredientUpload'

   export default {
      name: "RecipeOperations",
      components: {
         stepRegister,
         stepManageRecipe,
         stepOperationChoose,
         stepIngredientUpload,
         loadingSpinner,
         successRecipeUploadAlert,
         stepEdit
      },
      mounted() {
         this.recipeSteps.stepOperationChoose = true;
      },

      data: function () {
         return {
            recipeSuccessMessage: '',
            recipeSteps: {
               stepOperationChoose: false,
               stepEdit: false,
               stepIngredientUpload: false,
               stepRegister: false,
               stepManageRecipe: false,
               stepSuccessRecipeOperation: false,
            },

            /*
            * Recept modell
            */
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
                     unitPrice: null,
                     sumIngredientPrice: null,
                  },

                  {
                     listID: 1,
                     id: null,
                     name: null,
                     quantity: null,
                     unitPrice: null,
                     sumIngredientPrice: null,
                  },
               ],
            },
         }
      },

      /*
      Metódusok
      */
      methods: {

         // Ez most így csúnya
         updateSuccessful() {
            this.handleSteps('pending');
            this.recipeSuccessMessage = 'Sikeres recept feltöltés!';
            this.handleSteps('finish');
         },

         /*
         * Lekérjük a módosítandó recept adatait és átlépünk a manage operációba a receptet az adatbázisban
         */
         editRecipe(modifiableRecipeId) {
            this.handleSteps('pending');
            axios.post('/modifyrecipe', {
               modifiableRecipeId: modifiableRecipeId,
            })
               .then((response) => {
                  this.recipe.id = response.data.id;
                  this.recipe.name = response.data.name;
                  this.recipe.desc = response.data.desc;

                  //kinullázuk az adott recept ingredeitns tömbjét, hogy bele tudjuk pusholni amiket megkaptunk backendről
                  this.recipe.ingredients = [];

                  //végigiterálunk a megkapott alapanyagokon
                  for (let i = 0; i < response.data.required_ingredients.length; i++) {

                     //minden iterálásnál belepusholunk egy objektumot a módosítandó receptünk tömbjébe.
                     this.recipe.ingredients.push({
                        listID: i,
                        id: response.data.required_ingredients[i].id,
                        name: response.data.required_ingredients[i].name,
                        isIngredientSelected: true,
                        unitPrice: response.data.required_ingredients[i].unit_price,
                        quantity: response.data.required_ingredients[i].pivot.ingredient_quantity,
                        sumIngredientPrice: response.data.required_ingredients[i].pivot.ingredient_price,
                        unit_type_name: response.data.required_ingredients[i].pivot.ingredient_unit_type,
                        unit_category: response.data.required_ingredients[i].unit_category,
                     });
                  }

                  this.handleSteps('fill');
               })
               .catch((error) => {
                  //this.validateServerResponseOnFail(error.response.status);
                  console.log(error);
                  console.log('Backend error: ', error.response.data);
                  console.log('Statuscode: ', error.response.status);
                  console.log('Response headers: ', error.response.headers);
               });
         },

         /*
         * Step kezelő. Ezen keresztül manipuláljuk a megjelenő operációt
         */
         handleSteps(step) {
            this.recipeSteps.stepOperationChoose = false;

            if (step === 'register') {
               this.recipeSteps.stepRegister = true;
            } else if (step === 'edit') {
               this.recipeSteps.stepEdit = true;
            } else if (step === 'pending') {
               this.recipeSteps.stepOperationChoose = false;
               this.recipeSteps.stepRegister = false;
               this.recipeSteps.stepEdit = false;
               this.recipeSteps.stepManageRecipe = false;
               this.recipeSteps.stepSuccessRecipeOperation = false;
            } else if (step === 'fill') {
               this.recipeSteps.stepManageRecipe = true;
            } else if (step === 'ing-upload') {
               this.recipeSteps.stepIngredientUpload = true;
            } else if (step === 'finish') {
               this.recipeSteps.stepSuccessRecipeOperation = true;
            }
         },

         validateServerResponseOnFail(error) {
            if (error.response.status === 422) {
               alert(error.response.data.errors.name);
            }
            this.handleSteps('register');
         },

         registerNewRecipeObject(responseData) {
            this.handleSteps('pending');
            console.log('TUTUTUTUTUTU', responseData);
            this.recipe.id = responseData.new_recipe_id;
            this.recipe.name = this.newRecipeNameFirstLetterToUpperCase(responseData.new_recipe_name);
            console.log(this.recipe.id);
            console.log(this.recipe.name);

            this.handleSteps('fill');
         },

         newRecipeNameFirstLetterToUpperCase(newRecipeName) {
            return newRecipeName.charAt(0).toUpperCase() + newRecipeName.slice(1);
         },
      },
   }
</script>
