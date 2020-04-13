<template>
   <div class="mx-auto">
      <div class="m-lg-4 m-xs-2">
         <h2 class="text-center">Alapanyagok kezelése</h2>
         <hr>
         <transition name="bounce" mode="out-in">
            <div class="card-body form-group">
               <div class="">
                  <form class="row"
                        v-on:submit.prevent="uploadManager">
                     <div class="col col-lg-6 col-xs-6 text-center">
                        <p>Alapanyag neve</p>
                        <input required
                               class="form-control"
                               :disabled="pending || modify"
                               v-model="ingredientModel.name"
                               type="text">
                     </div>
                     <div class="col col-lg-3 col-xs-2 text-center">
                        <p>Egységtípusa</p>
                        <select class="form-control"
                                :disabled="pending || modify"
                                name=""
                                id=""
                                required
                                v-model="ingredientModel.unit_type"
                        >
                           <option v-for="type in ingredientUnitTypes"
                                   :value="type"
                           >{{type}}
                           </option>
                        </select>
                     </div>
                     <div class="col col-lg-3 col-xs-1 text-center">
                        <p>Ár / {{ingredientModel.unit_type}}</p>
                        <input required
                               :disabled="pending"
                               class="form-control"
                               type="number"
                               v-model="ingredientModel.unit_price"
                        >
                     </div>
                     <spinner v-if="pending"
                              class="mt-4 col col-lg-12"
                     />
                     <template v-else>
                        <input v-if="modify"
                               :disabled="pending"
                               type="submit"
                               class="mt-4 col col-lg-12 btn btn-warning"
                               value="Meglévő alapanyag módosítása"
                        />

                        <input v-else
                               :disabled="pending"
                               class="mt-4 col col-lg-12 btn btn-success"
                               type="submit"
                               value="Új alapanyag feltöltése"
                        />
                     </template>
                  </form>
               </div>
            </div>
         </transition>
      </div>

      <transition name="bounce">
         <div class="alert-response" v-if="success">
            <h3 class="alert alert-success text-center"
                @click="success = false"
            >{{successResponse}}</h3>
         </div>
      </transition>

      <!-- A fetchedErrors bindelve van a child komponens propjaként, és ott watcholjuk a változást -->
      <!-- Ha a child komponens emitel egy errorChanged eventet, akkor a visszaérkező paraméter ($event) értékével tesszük egyenlővé a pendinget -->
      <errorHandler :fetchedErrors="fetchedErrors"
                    @errorChanged="pending = $event"
      />

      <h2 class="text-center">Alapanyag kereső</h2>
      <hr>

      <div class="col col-lg-12 form-group">
         <input required
                type="text"
                v-model="search"
                class="form-control" id="recipe-name"
                aria-describedby="recipe-name"
                @keyup="checkSearcher"
                placeholder="Kezd el írni az alapanyag nevét">
      </div>

      <template v-if="showList">
         <transition-group name="bounce" tag="div">
            <div v-for="ingredient in filteredList"
                 :key="ingredient.id" class="card my-2">
               <div class="text-center btn btn-info"
                    @click="modifyIngredient(ingredient)"
               >{{ingredient.name}}
               </div>
            </div>
         </transition-group>
      </template>

   </div>
</template>

<script>
   import spinner from '../loadingSpinner'
   import errorHandler from "../ErrorHandling";

   export default {
      name: "stepIngredientUpload",

      components: {
         spinner,
         errorHandler
      },

      mounted() {
         this.fetchIngredients();
      },

      data() {
         return {
            fetchedErrors: {},
            successResponse: '',
            modify: false,
            showList: false,
            success: false,
            pending: false,
            ingredientUnitTypes: ['g', 'dkg', 'kg', 'ml', 'cl', 'l', 'db'],
            search: '',
            ingredientModel: {
               id: null,
               name: '',
               unit_type: 'típus',
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
            this.success = false;
            this.pending = true;
            this.ingredientModel.name = this.ingredientModel.name.toLowerCase();

            axios.post('/registernewingredient', {
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
                  this.fetchedErrors = error.response.data;
               });
         },

         uploadModifiedIngredient() {
            this.success = false;
            this.pending = true;
            this.ingredientModel.name = this.ingredientModel.name.toLowerCase();

            axios.post('/modifyexistingingredient', {
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
                  this.successResponse = 'Sikeres alapanyag módosítás!';
                  console.log(response);
               })
               .catch((error) => {
                  console.log(error);
                  console.log('Backend error: ', error.response.data);
                  console.log('Statuscode: ', error.response.status);
                  console.log('Response headers: ', error.response.headers);
                  this.fetchedErrors = error.response.data;
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
            this.ingredientModel.unit_type = ingredient.unit_type;
            this.ingredientModel.unit_price = ingredient.unit_price;
            this.search = ingredient.name;
         },

         fetchIngredients() {
            axios.get('/fetchingredients')
               .then((response) => {
                  this.fetchedIngredients = response.data;
               })
               .catch((error) => {
                  console.log(error);
               });
         },

         resetInput() {
            this.ingredientModel.name = '';
            this.ingredientModel.unit_type = 'típus';
            this.ingredientModel.unit_price = null;
         },

         uploadManager() {
            this.modify ? this.uploadModifiedIngredient() : this.uploadIngredient();
         }

      },
   }
</script>
