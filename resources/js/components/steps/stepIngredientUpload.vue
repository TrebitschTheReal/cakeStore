<template>
   <div class="mx-auto">
      <div class="m-lg-4 m-xs-2">
         <h2 class="text-center">Alapanyagok kezelése</h2>
         <hr>
         <!-- TODO: valahol itt ki lehessen választani az alapanyagot, mint a recept módosításnál, és töltse fel a visszakapott adat a newIngredient objektumot és viszlát -->
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
                                v-model="ingredientModel.unitType"
                        >
                           <option v-for="type in ingredientUnitTypes"
                                   :value="type"
                           >{{type}}
                           </option>
                        </select>
                     </div>
                     <div class="col col-lg-3 col-xs-1 text-center">
                        <p>Ár / {{ingredientModel.unitType}}</p>
                        <input required
                               :disabled="pending"
                               class="form-control"
                               type="number"
                               v-model="ingredientModel.unitPrice"
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

      <div class="alert-response" v-if="errors.length">
         <transition-group name="bounce" tag="h3">
            <h3 v-for="(error, index) in errors"
                class="alert alert-danger text-center"
                @click="deleteError(error.message)"
                :key="index"
            >{{error.message}}</h3>
         </transition-group>
      </div>

      <h2 class="text-center">Alapanyag kereső</h2>
      <hr>

      <div class="col col-lg-12 form-group">
         <input required
                type="text"
                v-model="search"
                class="form-control" id="recipe-name"
                aria-describedby="recipe-name"
                @keyup="testMethod"
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

   export default {
      name: "stepIngredientUpload",

      components: {
         spinner
      },

      mounted() {
         this.fetchIngredients();
      },

      data() {
         return {
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
               unitType: 'típus',
               unitPrice: null,
            },
            fetchedIngredients: {},
            errors: [
               {message: 'Test error message'},
               {message: 'Another dummy error message'}
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
               newIngredient: this.ingredientModel,
            })
               .then((response) => {
                  this.resetInput();
                  this.success = true;
                  this.pending = false;
                  this.modify = false;
                  this.search = '';
                  this.showList = false;
                  this.fetchIngredients();
                  this.successResponse = 'Sikeres alapanyag feltöltés!';

                  console.log(response);
               })
               .catch((error) => {
                  console.log(error);
                  console.log('Backend error: ', error.response.data);
                  console.log('Statuscode: ', error.response.status);
                  console.log('Response headers: ', error.response.headers);
                  this.handleErrors(error.response.data)
               });
         },

         handleErrors(errors) {
            let errorsMessage = errors['newIngredient.name'][0];
            let alreadyHaveThisError = this.errors.filter(e => e.message === errorsMessage).length > 0;

            if(!alreadyHaveThisError) {
               this.errors.push({message: errorsMessage});
            }

            this.resetInput();
            this.pending = false;
         },

         uploadModifiedIngredient() {
            this.success = false;
            this.pending = true;
            this.ingredientModel.name = this.ingredientModel.name.toLowerCase();

            axios.post('/modifyexistingingredient', {
               ingredient: this.ingredientModel,
            })
               .then((response) => {
                  this.resetInput();
                  this.success = true;
                  this.pending = false;
                  this.modify = false;
                  this.search = '';
                  this.showList = false;
                  this.fetchIngredients();
                  this.successResponse = 'Sikeres alapanyag módosítás!';
                  console.log(response);
               })
               .catch((error) => {
                  console.log(error);
                  console.log('Backend error: ', error.response.data);
                  console.log('Statuscode: ', error.response.status);
                  console.log('Response headers: ', error.response.headers);
               });
         },

         checkSearcher() {
            if(this.search.length === 0) {
               this.modify =  false;
               this.resetInput();
            }
         },

         modifyIngredient(ingredient) {
            this.modify = true;

            this.ingredientModel.id = ingredient.id;
            this.ingredientModel.name = ingredient.name;
            this.ingredientModel.unitType = ingredient.unit_type;
            this.ingredientModel.unitPrice = ingredient.unit_price;
            this.search = ingredient.name;
         },

         deleteError(key) {
            this.errors = this.errors.filter(function( obj ) {
               return obj.message !== key;
            });
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

         testMethod() {
            this.checkSearcher();
            this.filteredList.length ? this.showList = true : '';
         },

         resetInput() {
            this.ingredientModel.name = '';
            this.ingredientModel.unitType = 'típus';
            this.ingredientModel.unitPrice = null;
         },

         uploadManager() {
            this.modify ? this.uploadModifiedIngredient() : this.uploadIngredient();
         }

      },
   }
</script>
