<template>
   <div class="mx-auto">
      <div class="m-lg-4 m-xs-2">
         <h2 class="text-center">Recept módosítása</h2>
         <hr>
      </div>
      <div class="col col-lg-12 form-group">
         <input required
                type="text"
                v-model="search"
                class="form-control" id="recipe-name"
                aria-describedby="recipe-name"
                @keyup="testMethod"
                placeholder="Kezd el írni a recept nevét">
      </div>

      <template v-if="showList">
         <transition-group name="bounce" tag="div">
            <div v-for="cake in filteredList" :key="cake.id" class="card my-2">
               <div class="text-center btn btn-info"
                    @click="modifyRecipe(cake.id)"
               >{{cake.name}}
               </div>
            </div>
         </transition-group>
      </template>
   </div>
</template>

<script>
   export default {
      name: "stepModify",

      mounted() {
         this.fetchRecipes();
      },

      data() {
         return {
            modifiableRecipeId: null,
            fetchedRecipes: {},
            showList: false,
            search: '',
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
         modifyRecipe(cakeId) {
            this.$emit('modifyRecipe', cakeId)
         },


         fetchRecipes() {
            axios.get('/cakelist')
               .then((response) => {
                  this.fetchedRecipes = response.data;
               })
               .catch((error) => {
                  console.log(error);
               });
         },

         testMethod() {
            this.filteredList.length ? this.showList = true : '';
         }
      }
   }
</script>

