<template>
   <div>
      <div v-if="recipeTable" class="table-fix-size table-responsive col mx-auto">
         <table class="table">
            <thead class="thead-dark">
            <tr>
               <th scope="col">#</th>
               <th scope="col">Recept neve</th>
               <th scope="col">Anyag költség</th>
               <th scope="col">Feltöltés dátuma</th>
               <th scope="col">Utolsó módosítás dátuma</th>
               <th scope="col"></th>
               <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <!--
                        <transition-group name="fade" mode="out-in" tag="tr">
            -->
            <tr v-for="recipe in filteredList" :key="recipe.id">
               <th scope="row">{{recipe.id}}</th>
               <td>{{recipe.name}}</td>
               <td>{{recipe.ingredients_price_sum}} Ft</td>
               <td>{{recipe.created_at}}</td>
               <td>{{recipe.updated_at}}</td>
               <td>
                  <button @click="modifyRecipe(recipe.id)" class="btn btn-warning">Módosítás</button>
               </td>
               <td>
                  <button class="btn btn-danger">Törlés</button>
               </td>
            </tr>
            <!--
               <transition-group/>
            -->
            </tbody>
         </table>
      </div>
      <div v-if="ingredientTable">
         <div class="table-fix-size table-responsive col mx-auto">
            <table class="table">
               <thead class="thead-dark">
               <tr>
                  <th scope="col">#</th>
                  <th scope="col">Alapanyag neve</th>
                  <th scope="col">Egységtípus</th>
                  <th scope="col">Egységár</th>
                  <th scope="col">Feltöltés dátuma</th>
                  <th scope="col">Utolsó módosítás dátuma</th>
                  <th scope="col"></th>
                  <th scope="col"></th>
               </tr>
               </thead>
               <tbody>
               <!--
                           <transition-group name="fade" mode="out-in" tag="tr">
               -->
               <tr v-for="ingredient in filteredList" :key="ingredient.id">
                  <th scope="row">{{ingredient.id}}</th>
                  <td>{{ingredient.name}}</td>
                  <td>{{ingredient.unit_type}}</td>
                  <td>{{ingredient.unit_price}}</td>
                  <td>{{ingredient.created_at}}</td>
                  <td>{{ingredient.updated_at}}</td>
                  <td>
                     <button @click="modifyIngredient(ingredient)" class="btn btn-warning">Módosítás</button>
                  </td>
                  <td>
                     <button class="btn btn-danger">Törlés</button>
                  </td>
               </tr>
               </tbody>
               <!--
                  <transition-group/>
               -->
            </table>
         </div>
      </div>

      <div v-if="userTable">
         <div class="table-fix-size table-responsive col mx-auto">
            <table class="table">
               <thead class="thead-dark">
               <tr>
                  <th scope="col">#</th>
                  <th scope="col">Felhasználó neve</th>
                  <th scope="col">Jogosultsági szint</th>
                  <th scope="col">E-mail címe</th>
                  <th scope="col">Regisztráció dátuma</th>
                  <th scope="col">Utolsó módosítás dátuma</th>
                  <th scope="col"></th>
                  <th scope="col"></th>
               </tr>
               </thead>
               <tbody>
               <!--
                           <transition-group name="fade" mode="out-in" tag="tr">
               -->
               <tr v-for="user in filteredList" :key="user.id">
                  <th scope="row">{{user.id}}</th>
                  <td>{{user.name}}</td>
                  <td>{{user.roles[0].name}}</td>
                  <td>{{user.email}}</td>
                  <td>{{user.created_at}}</td>
                  <td>{{user.updated_at}}</td>
                  <td>
                     <button @click="modifyUser(user)" class="btn btn-warning">Módosítás</button>
                  </td>
                  <td>
                     <button class="btn btn-danger">Törlés</button>
                  </td>
               </tr>
               </tbody>
               <!--
                  <transition-group/>
               -->
            </table>
         </div>
      </div>

   </div>

</template>

<script>
   export default {
      name: "TableView",

      /*
         Megkapjuk propsnak a filteredListet és a tábla típusát. Ha 'recipe' akkor a recipe renderelődik ki,
         ha 'ingredient', akkor az ingredienthez kapcoslódó tábla (setTableContent)

         A modifyRecipe és a modifyIngredient azokban a komponensekben van meghívva, ahol használjuk őket.
         Egymástól teljesen függetlenek.
       */
      props: {
         filteredList: {},
         tableData: '',
      },

      mounted() {
         this.setTableContent();
      },

      data() {
         return {
            recipeTable: false,
            ingredientTable: false,
            userTable: false,
         }
      },

      methods: {
         modifyRecipe(recipeId) {
            this.$emit('modifyRecipe', recipeId)
         },

         modifyIngredient(ingredient) {
            this.$emit('modifyIngredient', ingredient)
         },

         modifyUser(user) {
            this.$emit('modifyUser', user)
         },

         setTableContent() {
            if (this.tableData === 'recipe') {
               this.recipeTable = true;
            }

            if (this.tableData === 'ingredient') {
               this.ingredientTable = true;
            }

            if (this.tableData === 'user') {
               this.userTable = true;
            }
         }
      }
   }
</script>