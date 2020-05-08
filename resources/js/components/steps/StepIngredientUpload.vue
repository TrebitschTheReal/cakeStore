<template>
   <div class="col-12">
      <div class="management">
         <h2 class="text-center">Alapanyagok kezelése</h2>
         <hr>
         <transition name="bounce" mode="out-in">
            <form class="row"
                  @submit.prevent="uploadIngredient">
               <div class="col-lg-3">
                  <label for="ingredient-name">Alapanyag neve</label>
                  <input required
                         id="ingredient-name"
                         class="form-control"
                         :disabled="pending"
                         v-model="ingredientModel.name"
                         type="text">
               </div>
               <div class="col-lg-3">
                  <label for="ingredient-quantity">Mennyiség</label>
                  <input required
                         id="ingredient-quantity"
                         v-model="ingredientModel.uploaded_unit_quantity"
                         :disabled="pending"
                         class="form-control"
                         type="number"
                  >
               </div>
               <div class="col-lg-3">
                  <label for="ingredient-unit">Egységtípusa</label>
                  <select class="form-control"
                          :disabled="pending"
                          name=""
                          id="ingredient-unit"
                          required
                          v-model="selectedUnitName"
                  >
                     <option v-for="type in ingredientUnitTypes"
                     >{{type.type_name}}
                     </option>
                  </select>
               </div>
               <div class="col-lg-3">
                  <label for="price">Ár</label>
                  <input required
                         id="price"
                         :disabled="pending"
                         class="form-control"
                         type="number"
                         v-model="ingredientModel.unit_price"
                  >
               </div>
               <spinner v-if="pending"
                        class=""
               />
               <template v-else>
                  <input :disabled="pending"
                         :class="modify ? 'submit-button-margin col-xs-12 col-lg-6 my-3 mx-auto btn btn-warning' : 'submit-button-margin col-xs-12 col-lg-6 my-3 mx-auto btn btn-block btn-success'"
                         type="submit"
                         :value="modify ? 'Meglévő alapanyag módosítása' : 'Új alapanyag feltöltése'"
                  />
               </template>
            </form>
         </transition>

         <transition name="bounce">
            <div v-if="success"
                 class="my-3 alert-response">
               <h3 class="alert alert-success text-center"
                   @click="success = false"
               >{{successResponse}}</h3>
            </div>
         </transition>

         <!-- A fetchedErrors bindelve van a child komponens propjaként, és ott watcholjuk a változást -->
         <!-- Ha a child komponens emitel egy errorChanged eventet, akkor a visszaérkező paraméter ($event) értékével tesszük egyenlővé a pendinget -->
         <errorHandler :fetchedErrors="fetchedErrors"
                       @errorChanged="pending = $event"
                       class="my-2"
         />

         <h2 class="text-center">Alapanyag kereső</h2>
         <hr>
         <div class="form-group">
            <input required
                   type="text"
                   v-model="search"
                   class="form-control mx-auto col-lg-8"
                   id="recipe-name"
                   aria-describedby="recipe-name"
                   @keyup="checkSearcher"
                   placeholder="Kezd el írni az alapanyag nevét">
         </div>
      </div>

      <tableView v-if="showList"
                 class="p-lg-5"
                 :filteredList="this.filteredList"
                 :tableData="'ingredient'"
                 :pending="pending"
                 @modifyIngredient="modifyIngredient($event)"
                 @fetchIngredients="fetchIngredients"
                 @succesResponse="generateSuccessResponse($event)"
                 @fetchedErrors="fetchedErrors = $event"
                 @startPending="pending = true"
      />

   </div>
</template>

<script>
   import spinner from '../partials/loadingSpinner';
   import errorHandler from "../partials/errorHandling";
   import tableView from '../partials/tableView';

   export default {
      name: "stepIngredientUpload",

      components: {
         spinner,
         errorHandler,
         tableView
      },

      mounted() {
         this.fetchUnitTypes();
         this.fetchIngredients();
      },

      data() {
         return {
            // Mivel nem tudunk vue-ban az option attribútumnak selected értéket adni ha a selectre v-modelt teszünk,
            // ezért szükség van egy segédváltozóra ami alapanyag módosításnál felveszi a korábban feltöltött értéket
            // Alapanyag feltöltésnél, matcholtatjuk ennek a változónak az értékét a unit objektummal
            // Ezt a megoldást kell madj használni a recept módosítás felületen is.
            selectedUnitName: '',
            fetchedErrors: {},
            successResponse: '',
            modify: false,
            showList: false,
            success: false,
            pending: false,
            ingredientUnitTypes: [],
            search: '',
            ingredientModel: {
               id: null,
               name: '',
               uploaded_unit_quantity: null,
               unit_price: null,
            },
            fetchedIngredients: {},
            errors: [
               /*
               Ilyen formában töltődnek be a hibaüzenetek:

               {message: 'Test error message'},
               {message: 'Another dummy error message'}

               */
            ]
         }
      },

      computed: {
         filteredList() {
            return this.fetchedIngredients.filter(ingredient => {
               return ingredient.name.toLowerCase().includes(this.search.toLowerCase())
            })
         },
      },

      methods: {
         uploadIngredient() {
            let axiosPostTo = '';

            // Megvizsgáljuk, hogy van-e id-ja a feltöltendő ingredient modellnek

            if (this.ingredientModel.id == null) {
               // Ha nincs, akkor az új alapanyag feltöltés végpontra postolunk
               axiosPostTo = '/registernewingredient'
            } else {
               // Ha van, akkor a meglévő alapanyag módosítás végpontra posztolunk
               axiosPostTo = '/modifyexistingingredient'
            }

            this.matchSeledtedUnitName();
            this.success = false;
            this.pending = true;
            this.ingredientModel.name = this.ingredientModel.name.toLowerCase();

            axios.post(axiosPostTo, {
               ingredients: this.ingredientModel,
            })
               .then((response) => {
                  this.resetInput();
                  this.success = true;
                  this.pending = false;
                  this.modify = false;
                  this.search = '';
                  this.showList = false;
                  this.fetchIngredients();
                  this.fetchedErrors = {};
                  this.successResponse = 'Sikeres alapanyag feltöltés!';

                  console.log(response);
               })
               .catch((error) => {
                  console.log(error);
                  console.log('Backend error: ', error.response.data);
                  console.log('Statuscode: ', error.response.status);
                  console.log('Response headers: ', error.response.headers);

                  /*
                    Beküldjük a 'nyers' error objectet a fetchedErrors fieldbe, ami be van kötve az errorHandler
                    komponensbe
                   */
                  if (error.response.status == 500) {
                     this.fetchedErrors = ['Hiba történt! Kérjük vegye fel a kapcsolatot az oldal üzemeltetőjével!']
                  } else {
                     this.fetchedErrors = error.response.data;
                  }
               });
         },

         checkSearcher() {
            if (this.search.length === 0) {
               this.modify = false;
               this.resetInput();
            }

            this.filteredList.length ? this.showList = true : '';
         },


         modifyIngredient(ingredient) {
            this.modify = true;
            this.ingredientModel.id = ingredient.id;
            this.ingredientModel.name = ingredient.name;
            this.selectedUnitName = ingredient.uploaded_unit_type;
            this.ingredientModel.unit_price = ingredient.uploaded_unit_price;
            this.ingredientModel.uploaded_unit_quantity = ingredient.uploaded_unit_quantity;
            this.search = ingredient.name;
         },

         fetchIngredients() {
            axios.get('/fetchingredients')
               .then((response) => {
                  this.fetchedIngredients = response.data;
                  this.showList = true;
                  this.pending = false;
               })
               .catch((error) => {
                  console.log(error);
               });
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

         resetInput() {
            this.ingredientModel.id = null;
            this.ingredientModel.name = '';
            this.selectedUnitName = '';
            this.ingredientModel.unit_id = null;
            this.ingredientModel.type_name= null;
            this.ingredientModel.unit_category= null;
            this.ingredientModel.uploaded_unit_quantity = Number;
            this.ingredientModel.unit_price = Number;
         },

         generateSuccessResponse(message) {
            this.successResponse = message;
            this.success = true;
         },

         // Ha a segédváltozóban szereplő név megegyezik az unit objektum unit nevének egyikével,
         // akkor a feltöltendő alapanyag modellünkhöz csatoljuk az adott unit objektumot
         matchSeledtedUnitName() {
            for (let unit of this.ingredientUnitTypes) {
               if (unit.type_name === this.selectedUnitName) {
                  this.ingredientModel.unit_id = unit.id;
                  this.ingredientModel.type_name= unit.type_name;
                  this.ingredientModel.unit_category= unit.unit_category;
               }
            }
         },
      },
   }
</script>
