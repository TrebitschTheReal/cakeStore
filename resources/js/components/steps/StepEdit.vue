<template>
   <div class="mx-auto col">
      <div class="m-lg-4 m-xs-2">
         <h2 class="text-center">Recept módosítása</h2>
         <hr>
      </div>
      <div class="col-lg-6 col-xs-12 form-group mx-auto">
         <input required
                type="text"
                v-model="search"
                class="form-control" id="recipe-name"
                aria-describedby="recipe-name"
                placeholder="Kezd el írni a recept nevét">
      </div>
      <transition name="bounce">
         <div class="alert-response" v-if="success">
            <h3 class="alert alert-success text-center"
                @click="success = false"
            >{{successResponse}}</h3>
         </div>
      </transition>
      <errorHandler :fetchedErrors="fetchedErrors"
                    @errorChanged="pending = $event"
      />
      <tableView v-if="showList"
                 :filteredList="this.filteredList"
                 :tableData="'recipe'"
                 :pending="pending"
                 @modifyRecipe="modifyRecipe($event)"
                 @fetchRecipes="fetchRecipes"
                 @succesResponse="generateSuccessResponse($event)"
                 @fetchedErrors="fetchedErrors = $event"
                 @startPending="pending = true"
      />
      <spinner v-else/>
   </div>
</template>

<script>
   import spinner from '../partials/loadingSpinner';
   import tableView from '../partials/tableView';
   import errorHandler from '../partials/errorHandling';

   export default {
      name: "stepModify",

      mounted() {
         this.fetchRecipes();
      },

      components: {
         spinner,
         tableView,
         errorHandler
      },

      data() {
         return {
            fetchedErrors: {},
            modifiableRecipeId: null,
            fetchedRecipes: {},
            showList: false,
            search: '',
            success: false,
            pending: false,
         }
      },

      computed: {
         filteredList() {
            return this.fetchedRecipes.filter(cake => {
               return cake.name.toLowerCase().includes(this.search.toLowerCase())
            })
         },
      },

      methods: {
         modifyRecipe(recipeId) {
            this.$emit('modifyRecipe', recipeId)
         },

         generateSuccessResponse(message) {
            this.successResponse = message;
            this.success = true;
         },

         fetchRecipes() {
            axios.get('/cakelist')
               .then((response) => {
                  this.fetchedRecipes = response.data;
                  this.showList = true;
                  this.pending = false;
               })
               .catch((error) => {
                  this.fetchedErrors = ['Hiba történt! Kérjük vegye fel a kapcsolatot az oldal üzemeltetőjével!']
               });
         },
      }
   }
</script>

