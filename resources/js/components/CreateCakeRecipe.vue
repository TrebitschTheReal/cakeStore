<template>
   <div class="container">
      <div class="row">

         <div v-if="recipeSteps.stepOne"
              class="mx-auto card">
            <div class="card-header">
               <h2 class="text-center">{{newRecipe.name}}</h2>
            </div>
            <div class="card-body">
               <div class="row">
                  <div class="col col-6-lg col-2-xs">
                     <div class="form-group">
                        <label for="recipe-name">Recept neve:</label>
                        <input @keyup="validateNewRecipeName()" required type="text" v-model="recipeName" class="form-control" id="recipe-name"
                               aria-describedby="recipe-name"
                               placeholder="Írd be az új recept nevét">
                     </div>
                     <button @click="registerNewRecipe" class="col btn btn-primary">Recept létrehozása</button>
                  </div>
               </div>
            </div>
         </div>

         <div v-else-if="recipeSteps.stepTwo"
              class="mx-auto card">
            <div class="card-header">
               <h2 class="text-center">{{newRecipe.name}}</h2>
            </div>
            <div class="card-body">
               <div class="row">
                  <div class="col col-lg-12 col-xs-12 text-center mb-3">
                     <p class="font-weight-bold">Leírás</p>
                     <textarea v-model="newRecipe.desc" class="form-control" name="" id="" cols="30" rows="10"/>
                  </div>
               </div>
               <div v-for="(newIngredient, index) in newRecipe.ingredients" class="form-group">
                  <div class="row">
                     <div class="col col-lg-3 col-xs-12 text-center">
                        <p>Mennyiség</p>
                        <input class="form-control" v-model="newIngredient.quantity" type="number">
                     </div>
                     <div class="col col-lg-2 col-xs-12 text-center">
                        <p>Egység</p>
                        <!-- TODO: valamit ki kell találni arra, hogy a megfelelő egység jelenjen meg -->
                        <input disabled class="form-control" type="text" v-model="newIngredient.unitType">
                     </div>
                     <div class="col col-lg-6 col-xs-12 text-center">
                        <p>Alapanyag</p>
                        <select class="form-control" v-model="newIngredient.name" id="exampleFormControlSelect1" @change="linkFetchedIngredientStatsToTheNewlyCreatedIngredient(index, $event)">
                           <option v-for="(availableIngredient, key) in availableIngredients">{{availableIngredient.name}}</option>
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
                  <button @click="finishNewRecipe()" class="col btn btn-success m-2">Recept feltöltése</button>
               </div>
            </div>
         </div>

         <div v-else-if="recipeSteps.stepThree" class="mx-auto text-center">
            <div class="alert alert-success">
               <h2>Sikeres recept feltöltés!</h2>
            </div>
         </div>

         <div v-else class="col col-6-lg col-2-xs">
            <div class="my-3 d-flex justify-content-center">
               <div class="spinner-border" role="status">
                  <span class="sr-only">Loading...</span>
               </div>
            </div>
         </div>

         <div class="my-3 col col-lg-12 col-xs-12">
            <transition-group name="bounce" tag="p">
               <p v-for="(error, index) in errors" :key="error" @click="errorHandling('delete', index)"
                  class="col col-lg-6 col-xs-6 alert alert-danger mx-auto text-center">{{error}}</p>
            </transition-group>
         </div>

      </div>
   </div>
</template>

<script>
   export default {
      mounted() {
         //Ezt innen ki lehet venni, ha befejeződik az elem fejlesztése.
         // Ráér akkor fetchelni, amikor stepváltás van
         this.fetchInredientsList()
      },

      name: "CreateCakeRecipe",

      data() {
         return {
            //stepOne = új recept regisztrálása
            //stepTwo = alapanyag hozzárendelés
            recipeSteps: {
               //TODO: in production ezeket beállítani normálisan
               stepOne: true,
               stepTwo: false,
               stepThree: false,
            },
            recipeName: '',
            serverResponseData: null,
            newRecipe: {
               id: null,
               name: 'Recept Feltöltése',
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

            isError: false,
            errors: [],
            availableIngredients: [],
         }
         },

      methods: {
         registerNewRecipe() {
            this.handleSteps('pending');
            this.validateNewRecipeName();

            //Ha a hibalista üres
            if (this.errors.length === 0) {
               axios.post('/registernewrecipe', {
                  name: this.recipeName,
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

            //Ha a hibalista nem üres
            } else {
               this.handleSteps('register');
            }
         },

         //Recept név validálása
         validateNewRecipeName() {
            this.errorHandling('clear');

            //Ha a feltétel NEM felel meg:
            if (
               //regex - csak betűk (kis / nagy) és számok, min 3, max 40 karakter
               !/^[a-zA-Z0-9_ ]{3,40} *$/g.test(this.recipeName)
            ) {
               this.errorHandling('push', 'A névben csak betűk és számok szerepelhetnek! Minimum 3 és maximum 40 karakter!');
            //Ha megfelel:
            } else {
               //Végigmegyünk a tömbön, megkeressük a hibaüzenet stringjét, és újrarendezzük a tömböt a hibaüzenet nélkül.
               this.errors.filter(e => e !== 'A névben csak betűk és számok szerepelhetnek! Minimum 3 és maximum 40 karakter!')
            }
         },

         validateNewRecipeContent() {
            return true;
         },

         validateServerResponseOnSuccess(responseData) {
            this.registerNewRecipeObject(responseData);
            this.fetchInredientsList();
            this.handleSteps('fill');
         },

         validateServerResponseOnFail(error) {
            if (error.response.status === 422) {
               this.errorHandling('push', error.response.data.errors.name[0])
            }

            this.handleSteps('register');
            this.errorHandling('show');
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
               this.recipeSteps.stepTwo = false;
               this.recipeSteps.stepThree = false;
            } else if (step === 'fill') {
               this.recipeSteps.stepTwo = true;
            } else if (step === 'finish') {
               this.recipeSteps.stepThree = true;
            }
         },

         registerNewRecipeObject(responseData) {
            this.newRecipe.id = responseData.new_recipe_id;
            this.newRecipe.name = this.newRecipeNameFirstLetterToUpperCase(responseData.new_recipe_name);
            console.log(this.newRecipe.id);
            console.log(this.newRecipe.name)
         },

         newRecipeNameFirstLetterToUpperCase(newRecipeName) {
            return newRecipeName.charAt(0).toUpperCase() + newRecipeName.slice(1);
         },

         addNewIngredientRow() {
            console.log('-----------------------');
            console.log(this.newRecipe.ingredients);
            console.log('-----------------------');

            this.newRecipe.ingredients.push({
               listID: this.getLastIDofNewRecipeIngredientsArray(),
               id: null,
               name: null,
               quantity: null,
               unitType: null,
               unitPrice: null,
            });
         },

         removeIngredientRow(index) {
            this.newRecipe.ingredients.splice(index, 1);
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
         },

         getLastIDofNewRecipeIngredientsArray() {
            if(this.newRecipe.ingredients.length > 0) {
               return Math.max.apply(Math, this.newRecipe.ingredients.map(function(o) { return o.listID; })) + 1;
            }
            else return 0;
         },

         linkFetchedIngredientStatsToTheNewlyCreatedIngredient(index) {
            for (let availableIngredient of this.availableIngredients) {
               if (availableIngredient.name === this.newRecipe.ingredients[index].name) {
                  this.newRecipe.ingredients[index].id = availableIngredient.id;
                  this.newRecipe.ingredients[index].unitType = availableIngredient.unit_type;
                  this.newRecipe.ingredients[index].unitPrice = availableIngredient.unit_price;
               }
            }
         },

         finishNewRecipe() {
            this.handleSteps('pending');
            this.sumNewRecipeIngredientPrices();

            if (this.validateNewRecipeContent()) {
               axios.post('/fillnewlycreatedrecipe', {
                  newRecipe: this.newRecipe,
               })
                  .then((response) => {
                     //this.validateServerResponseOnSuccess(response.data);
                     this.handleSteps('finish');
                  })
                  .catch((error) => {
                     //this.validateServerResponseOnFail(error.response.status);
                     console.log(error);
                     console.log('Backend error: ', error.response.data);
                     console.log('Statuscode: ', error.response.status);
                     console.log('Response headers: ', error.response.headers);
                  });
            } else {
               this.handleSteps('fill');
               alert('Frontend validációs hiba');
            }
         },

         // Kiszámolja, hogy egy alapanyag mennyibe fog kerülni az új receptben (mennyiség * ár)
         sumNewRecipeIngredientPrices() {
            for (let newIngredient of this.newRecipe.ingredients) {
               newIngredient.sumIngredientPrice = newIngredient.quantity * newIngredient.unitPrice;
            }
         },

         // Hibakezelő. Az első paraméter a műveletre utal, a második a hibaüzenet, az index pedig a kattintásra kivenni kívánt üzenet indexe.
         // A javascript absztraksziója miatt az errorMsg és az index opcionális
         errorHandling(operation, errorMsg, index) {
            if(operation === 'push' && !this.errors.includes(errorMsg)) {
               this.errors.push(errorMsg);
            }
            else if(operation === 'delete') {
               this.errors.splice(index, 1);
            }
            else if(operation === 'show') {
               this.isError = true;
            }
            else if(operation === 'hide') {
               this.isError = false;
            }
            else if(operation === 'clear') {
               this.errors = [];
            }
         },
      },
   }
</script>

<style scoped>

</style>
