<template>
   <div class="mx-auto card">
      <form @submit.prevent="finishNewRecipe">
         <div class="card-header">
            <p class="text-center font-weight-bold">Recept neve</p>
            <input class="form-control text-center"
                   required
                   type="text"
                   v-model="recipe.name">
         </div>
         <div class="card-body recipe-edit-card">
            <div class="row">
               <div class="col-12 mb-3">
                  <p class="text-center font-weight-bold">Leírás</p>
                  <textarea v-model="recipe.desc"
                            class="form-control"
                            rows="13"/>
               </div>
            </div>
            <div v-for="(ingredient, index) in recipe.ingredients"
                 class="">
               <div class="row">
                  <div class="ingredient-data-row col-lg-3">
                     <label :for="'quantity-' + index">Mennyiség</label>
                     <input required class="form-control"
                            :id="'quantity-' + index"
                            v-model="ingredient.quantity"
                            :disabled="!ingredient.isIngredientSelected"
                            type="number">
                  </div>
                  <div class="ingredient-data-row col-lg-3">
                     <label :for="'unit-' + index">Egység</label>
                     <select class="form-control"
                             :id="'unit-' + index"
                             :disabled="!ingredient.isIngredientSelected"
                             required
                             v-model="ingredient.type_name">
                        <!-- Azt a tömböt küldjük vissza, ami egyezik az egység categoryval. Tömeg - űrmérték - darab -->
                        <option v-for="type in getIngredientUnitListByCategory(ingredient.unit_category, ingredient)"
                        >{{type.type_name}}
                        </option>
                     </select>
                  </div>
                  <div class="ingredient-data-row col-lg-5">
                     <label :for="'ingredient-' + index">Alapanyag</label>
                     <select required class="form-control"
                             :id="'ingredient-' + index"
                             v-model="ingredient.name"
                             id="exampleFormControlSelect1"
                             @change="getUnitsByIngredientUnitCategory(ingredient, index, $event)">
                        <option v-for="(availableIngredient, key) in availableIngredients">
                           {{availableIngredient.name}}
                        </option>
                     </select>
                  </div>
                  <div class="col-lg-1">
                     <p class="text-white">-</p>
                     <p @click="removeIngredientRow(index)"
                        class="ingredient-data-row-button btn btn-danger">-</p>
                  </div>
               </div>
            </div>
            <div class="">
               <div class="ingredient-add-box">
                  <h4 class="ingredient-add-box-text float-left">Új alapanyag hozzáadása</h4>
                  <h4 @click="addNewIngredientRow" class="float-right btn btn-success">+</h4>
               </div>
               <errorHandler :fetchedErrors="fetchedErrors"
                             @errorChanged="pending = $event"
                             class=""
               />
            </div>
         </div>
         <div class="card-footer">
            <div class="row">
               <spinner v-if="pending === true"
                        class=""
               />
               <button v-else type="submit" class="btn btn-block btn-success">Recept feltöltése</button>
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
         this.fetchUnitTypes();
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
            ingredientUnitTypes: [],
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

         getUnitsByIngredientUnitCategory(ingredient) {
            ingredient.isIngredientSelected = true;
            ingredient.quantity = null;

            //Ez:

            // for (let availableIngredient of this.availableIngredients) {
            //    if (availableIngredient.name === ingredient.name) {
            //       ingredient.unit_category = availableIngredient.unit_category;
            //    }
            // }
            //
            // Megegyezik ezzel:

            this.availableIngredients.filter(function (e) {
               e.name  === ingredient.name ? ingredient.unit_category = e.unit_category : '';
            });

         },

         removeIngredientRow(index) {
            this.recipe.ingredients.splice(index, 1);
         },

         // form submitra hívódik: validálás (duplikáció ellenőrzés), majd prepare data for push

         finishNewRecipe() {
            this.fetchedErrors = [];
            this.pending = true;

            this.prepareIngredientModels();
            this.prepareUnitModels();

            console.log(this.recipe);

            /*
               Ellenőrizzük a duplikációkat
            */
            let duplicate = this.checkDuplicateIngredients();

            // Megkaptuk a checkDuplicateIngredients függvénytől a duplikált névlistát,
            // majd filterrel kiszedjük a duplikációkat, hogy a hibaüzenetben csak egyszer szerepeljen az,
            // amit meg akarunk jeleníteni duplikációként

            duplicate = duplicate.filter(function(item, pos) {
               return duplicate.indexOf(item) == pos;
            });

            /*
               Ha a duplicate változó hossza nem nulla, akkor értelemszerűen találtunk duplikációt.
            */
            if(!duplicate.length) {
               // Frissítjük a receptet az adatbázisban
               axios.post('/fillnewlycreatedrecipe', {
                  newRecipe: this.recipe,
               })
                  .then((response) => {
                     this.$emit('updateSuccessful')
                  })
                  .catch((error) => {
                     this.fetchedErrors = error.response.data;
                  });

            } else {
               // Kigeneráljuk a backend és a frontend hibákat
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

         fetchUnitTypes() {
            axios.get('/fetchunittypes')
               .then((response) => {
                  this.ingredientUnitTypes = response.data;
               })
               .catch((error) => {
                  console.log(error);
               });
         },

         // Az egységektípusok select attr, optionjai feltöltésére használjuk.
         // Végigiterálunk a befetchelt egységtípus tömbön, és visszaküldjük azt az átalakított tömbjét
         // ahol a unit categoryk megegyeznek.
         //
         // filter = visszaad egy tömböt amit aszerint rendez össze, amilyen 'filter' paramétert kap.
         // fontos: mivel a tömb nem dinaimus adatszerkezet, ezért nem ki és betesz elemeket, hanem minden esetben
         // újrarendezi az egészet! (performace szempont)

         getIngredientUnitListByCategory(unit_category) {
            return this.ingredientUnitTypes.filter(function (e) {
               return e.unit_category == unit_category;
            });
         },

         // Előkészítjük a receptek ingredient 'modelljeit' a postolásra

         prepareIngredientModels() {
            for(let ingredient of this.recipe.ingredients) {
               for (let availableIngredient of this.availableIngredients) {
                  if (availableIngredient.name === ingredient.name) {
                     ingredient.id = availableIngredient.id;
                     ingredient.unitPrice = availableIngredient.unit_price;
                  }
               }
            }
         },

         // Előkészítjük a receptek unit részével kapcsolatos részeit a postolásra

         prepareUnitModels() {
            for (let ingredient of this.recipe.ingredients) {
               for (let unit of this.ingredientUnitTypes) {
                  if (ingredient.type_name === unit.type_name) {
                     ingredient.unit_id = unit.id;
                     ingredient.unit_category = unit.unit_category;
                  }
               }
            }
         }

      },

   }
</script>
