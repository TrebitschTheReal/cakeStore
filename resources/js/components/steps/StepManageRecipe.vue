<template>
   <div class="mx-auto card">
      <form v-on:submit.prevent="finishNewRecipe">
         <div class="card-header">
            <h2 class="text-center">{{recipe.name}}</h2>
         </div>
         <div class="card-body">
            <div class="row">
               <div class="col col-lg-12 col-xs-12 text-center mb-3">
                  <p class="font-weight-bold">Leírás</p>
                  <textarea v-model="recipe.desc" class="form-control" name="" cols="30" rows="10"/>
               </div>
            </div>
            <div v-for="(ingredient, index) in recipe.ingredients" class="form-group">
               <div class="row">
                  <div class="col col-lg-3 col-xs-12 text-center">
                     <p>Mennyiség</p>
                     <input required class="form-control" v-model="ingredient.quantity" type="number">
                  </div>
                  <div class="col col-lg-2 col-xs-12 text-center">
                     <p>Egység</p>
                     <input required disabled class="form-control" type="text" v-model="ingredient.unitType">
                  </div>
                  <div class="col col-lg-6 col-xs-12 text-center">
                     <p>Alapanyag</p>
                     <select required class="form-control" v-model="ingredient.name" id="exampleFormControlSelect1"
                             @change="linkFetchedIngredientStatsToTheNewlyAddedIngredient(index, $event)">
                        <option v-for="(availableIngredient, key) in availableIngredients">
                           {{availableIngredient.name}}
                        </option>
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
                  <h4 class="">Új alapanyag hozzáadása<span @click="addNewIngredientRow"
                                                            class="float-right btn btn-success">+</span>
                  </h4>
               </div>
               <errorHandler :fetchedErrors="fetchedErrors"
                             @errorChanged="pending = $event"
                             class="my-2"
               />
            </div>
         </div>
         <div class="card-footer">
            <div class="row">
               <spinner v-if="pending === true"
                        class="mt-4 col col-lg-12"
               />
               <button v-else type="submit" class="col-12 btn btn-success m-2">Recept feltöltése</button>
            </div>
         </div>
      </form>
   </div>
</template>

<script>
   import spinner from '../partials/loadingSpinner'
   import errorHandler from "../partials/errorHandling";

   export default {
      name: "stepManageRecipe",

      components: {
         spinner,
         errorHandler
      },


      /*
      A mounted CSAK akkor fut le, ha true értéket kap az adott step ami aktiválja az aktuális komponenst.
      Amíg a dom jelen része nincs kirenderelve (a jelenlegi komponens, ami v-ifre van kötve), addig az konkrétan nem is létezik!
       */
      mounted() {
         this.fetchInredientsList();
      },

      props: {
         recipe: {}
      },

      data: function () {
         return {
            fetchedErrors: [],
            pending: false,
            availableIngredients: [],
         }
      },

      methods: {
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

         addNewIngredientRow() {
            console.log('-----------------------');
            console.log(this.recipe.ingredients);
            console.log('-----------------------');

            this.recipe.ingredients.push({
               listID: this.getLastIDofNewRecipeIngredientsArray(),
               id: null,
               name: null,
               quantity: null,
               unitType: null,
               unitPrice: null,
            });
         },

         getLastIDofNewRecipeIngredientsArray() {
            if (this.recipe.ingredients.length > 0) {
               return Math.max.apply(Math, this.recipe.ingredients.map(function (o) {
                  return o.listID;
               })) + 1;
            } else return 0;
         },

         linkFetchedIngredientStatsToTheNewlyAddedIngredient(index) {
            for (let availableIngredient of this.availableIngredients) {
               if (availableIngredient.name === this.recipe.ingredients[index].name) {
                  this.recipe.ingredients[index].id = availableIngredient.id;
                  this.recipe.ingredients[index].unitType = availableIngredient.unit_type;
                  this.recipe.ingredients[index].unitPrice = availableIngredient.unit_price;
               }
            }
         },

         removeIngredientRow(index) {
            this.recipe.ingredients.splice(index, 1);
         },

         finishNewRecipe() {
            this.fetchedErrors = [];
            this.pending = true;
            /*
               Ellenőrizzük a duplikációkat
            */
            let duplicate = this.checkDuplicateIngredients();

            /*
               Ha a duplicate változó hossza nem nulla, akkor értelemszerűen találtunk duplikációt.
            */
            if(!duplicate.length) {
               this.sumNewRecipeIngredientPrices();
               this.$emit('updateRecipe', this.recipe)
            } else {
               let errorString = 'A ' + duplicate + ' többször szerepel!';
               this.fetchedErrors = [errorString];
               this.pending = false;
            }
         },

         checkDuplicateIngredients() {
            /*
               Végigiterálunk az ingredienteken, és ha duplikációt találunk, azt bepusholjuk a duplicates tömbbe,
               majd mindenképpen visszaküldjük a tömböt.
             */
            let cnt = 0;
            let duplicates = [];

            for (let ingredient of this.recipe.ingredients) {
               for (let anotherIngredient of this.recipe.ingredients) {
                  if (ingredient.name === anotherIngredient.name) {
                     cnt++
                  }

                  if (cnt > 1) {
                     duplicates.push(ingredient.name);
                  }
               }
               cnt = 0;
            }
            return duplicates;
         },

         sumNewRecipeIngredientPrices() {
            for (let newIngredient of this.recipe.ingredients) {
               newIngredient.sumIngredientPrice = newIngredient.quantity * newIngredient.unitPrice;
            }
         },

      }
   }
</script>
