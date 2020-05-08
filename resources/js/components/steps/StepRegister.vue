<template>
   <div class="mx-auto">
      <h2 class="text-center">Recept regisztrálása</h2>
      <hr>
      <div class="row">
         <div class="col col-6-lg col-2-xs">
            <div class="form-group">
               <input required type="text"
                      v-model="actualRecipeName"
                      class="form-control" id="recipe-name"
                      aria-describedby="recipe-name"
                      placeholder="Írd be az új recept nevét">
            </div>
            <spinner v-if="pending"
                     class="mt-4 col col-lg-12"
            />
            <button v-else @click="validateNewRecipeName" class="col btn btn-primary">Recept regisztrálása
            </button>
         </div>
      </div>

      <errorHandler class="my-3"
                    :fetchedErrors="fetchedErrors"
                    @errorChanged="pending = $event"
      />
   </div>
</template>

<script>
   import spinner from '../partials/loadingSpinner';
   import errorHandler from '../partials/errorHandling';


   export default {
      name: "stepRegister",

      components: {
         spinner,
         errorHandler
      },

      props: {
         newRecipeName: String
      },

      data: function () {
         return {
            pending: false,
            fetchedErrors: {},
            actualRecipeName: '',
         }
      },

      methods: {
         validateNewRecipeName() {

            //Validation ...
            //SUCCESS
            this.registerNewRecipeToDB();
         },

         /*
            Regisztráljuk az új receptet az adatbázisban
          */
         registerNewRecipeToDB(newRecipeName) {
            this.pending = true;
            axios.post('/registernewrecipe', {
               name: this.actualRecipeName,
            })
               .then((response) => {
                  this.fetchedErrors = {};
                  /*
                    Ha 200-al visszatér a backend, akkor visszaküldjük a response.datát
                   */
                  this.$emit('registerNewRecipe', response.data);
               })
               .catch((error) => {
                  /*
                    Ha hiba adódik, akkor azt itt, helyben lekezeljük az errorHandler komponensünkkel
                   */
                  if (error.response.status == 500) {
                     this.fetchedErrors = ['Hiba történt! Kérjük vegye fel a kapcsolatot az oldal üzemeltetőjével!']
                  } else {
                     this.fetchedErrors = error.response.data;
                  }
               });
         },
      }
   }
</script>
