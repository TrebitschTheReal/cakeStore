<template>
   <div class="mx-auto">
      <div class="m-lg-4 m-xs-2">
         <h2 class="text-center">Alapanyag feltöltése</h2>
         <hr>
         <div class="card-body form-group">
            <div class="">
               <form class="row"
                     v-on:submit.prevent="uploadIngredient">
                  <div class="col col-lg-6 col-xs-6 text-center">
                     <p>Alapanyag neve</p>
                     <input required
                            class="form-control"
                            :disabled="pending"
                            v-model="newIngredient.name"
                            type="text">
                  </div>
                  <div class="col col-lg-3 col-xs-2 text-center">
                     <p>Egységtípusa</p>
                     <select class="form-control"
                             :disabled="pending"
                             name=""
                             id=""
                             required
                             v-model="newIngredient.unitType"
                     >
                        <option v-for="type in ingredientUnitTypes"
                                :value="type"
                        >{{type}}</option>
                     </select>
                  </div>
                  <div class="col col-lg-3 col-xs-1 text-center">
                     <p>Ár / {{newIngredient.unitType}}</p>
                     <input required
                            :disabled="pending"
                            class="form-control"
                            type="number"
                            v-model="newIngredient.unitPrice"
                     >
                  </div>
                  <spinner v-if="pending"
                           class="mt-4 col col-lg-12"
                  />

                  <input v-else
                         :disabled="pending"
                         class="mt-4 col col-lg-12 btn btn-success"
                         type="submit"
                         value="Alapanyag feltöltése"
                  />
               </form>

            </div>
         </div>
      </div>

      <transition name="bounce">
         <div class="alert-response" v-if="success">
            <h3 class="alert alert-success text-center"
                @click="success = false"
            >Sikeres alapanyag feltöltés</h3>
         </div>
      </transition>

   </div>
</template>

<script>
   import spinner from '../loadingSpinner'

   export default {
      name: "stepIngredientUpload",

      components: {
        spinner
      },

      data() {
         return {
            success: false,
            pending: false,
            ingredientUnitTypes: ['g', 'dkg', 'kg', 'ml', 'cl', 'l', 'db'],
            newIngredient: {
               name: '',
               unitType: 'típus',
               unitPrice: null,
            }
         }
      },

      methods: {
         uploadIngredient() {
            this.success = false;
            this.pending = true;
            this.newIngredient.name = this.newIngredient.name.toLowerCase();

            axios.post('/registernewingredient', {
               newIngredient: this.newIngredient,
            })
               .then((response) => {
                  this.resetInput();
                  this.success = true;
                  this.pending = false;
                  console.log(response);
               })
               .catch((error) => {
                  console.log(error);
                  console.log('Backend error: ', error.response.data);
                  console.log('Statuscode: ', error.response.status);
                  console.log('Response headers: ', error.response.headers);
               });
         },

         resetInput() {
            this.newIngredient.name = '';
            this.newIngredient.unitType = 'típus';
            this.newIngredient.unitPrice = null;
         }

      },
   }
</script>
