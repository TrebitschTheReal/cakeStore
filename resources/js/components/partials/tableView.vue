<template>
   <div>
      <decisionModal :modalContent="modalContent"
                     @deleteConfirmed="deleteItem()"
      />
      <div v-if="recipeTable" class="table-fix-size table-responsive col mx-auto">
         <table class="table">
            <thead class="thead-dark">
            <tr>
               <th scope="col">#</th>
               <th scope="col">Recept neve</th>
               <th scope="col">Anyag költség</th>
               <th scope="col">Feltöltés dátuma</th>
               <th scope="col">Utolsó módosítás dátuma</th>
               <th colspan="2" scope="col"></th>
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
               <td>{{recipe.created_at | moment("YYYY-MM-DD-hh:mm:ss")}}</td>
               <td>{{recipe.updated_at | moment("YYYY-MM-DD-hh:mm:ss")}}</td>
               <td colspan="2" v-if="pending">
                  <spinner/>
               </td>
               <td colspan="2" v-else>
                  <button @click="modifyRecipe(recipe.id)"
                          class="col-12 mb-2 btn btn-warning">Módosítás</button>
                  <button @click="prepareToDeleteItem(recipe.id, recipe.name, 'recipe')"
                          class="col-12 btn btn-danger">Törlés</button>
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
                  <th scope="col">Feltöltött mennyiség</th>
                  <th scope="col">Feltöltött egységtípus</th>
                  <th scope="col">Feltöltött egységár</th>
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
                  <td>{{ingredient.uploaded_unit_quantity}}</td>
                  <td>{{ingredient.uploaded_unit_type}}</td>
                  <td>{{ingredient.uploaded_unit_price}} Ft</td>
                  <td>{{ingredient.created_at | moment("YYYY-MM-DD-hh:mm:ss")}}</td>
                  <td>{{ingredient.updated_at | moment("YYYY-MM-DD-hh:mm:ss")}}</td>
                  <td colspan="2" v-if="pending">
                     <spinner/>
                  </td>
                  <td colspan="2" v-else>
                     <button @click="modifyIngredient(ingredient)"
                             class="col-12 mb-2 btn btn-warning">Módosítás</button>
                     <button @click="prepareToDeleteItem(ingredient.id, ingredient.name, 'ingredient')"
                             class="col-12 btn btn-danger">Törlés</button>
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
                  <th scope="col">Név</th>
                  <th scope="col">Jogosultsági szint</th>
                  <th scope="col">E-mail</th>
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
                  <td>{{user.created_at | moment("YYYY-MM-DD-hh:mm:ss")}}</td>
                  <td>{{user.updated_at | moment("YYYY-MM-DD-hh:mm:ss")}}</td>
                  <td colspan="2" v-if="pending">
                     <spinner/>
                  </td>
                  <td colspan="2" v-else>
                     <button @click="modifyUser(user)"
                             class="col-12 mb-2 btn btn-warning">Módosítás</button>
                     <button @click="prepareToDeleteItem(user.id, user.name, 'user')"
                             class="col-12 btn btn-danger">Törlés</button>
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
   import spinner from './loadingSpinner';
   import decisionModal from "./decisionModal";

   export default {
      name: "TableView",
      components: {
         spinner,
         decisionModal
      },

      /*
         Megkapjuk propsnak a filteredListet és a tábla típusát. Ha 'recipe' akkor a recipe renderelődik ki,
         ha 'ingredient', akkor az ingredienthez kapcoslódó tábla (setTableContent)

         A modifyRecipe és a modifyIngredient azokban a komponensekben van meghívva, ahol használjuk őket.
         Egymástól teljesen függetlenek.
       */
      props: {
         filteredList: {},
         tableData: '',
         pending: Boolean,
      },

      mounted() {
         this.setTableContent();
      },

      computed: {

      },

      data() {
         return {
            recipeTable: false,
            ingredientTable: false,
            userTable: false,
            modalContent: {
               modalHeader: String,
               modalBody: String,
               modalYes: String,
               modalNo: String,
            },
            deletableItemId: Number,
            deletableItemType: String,
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

         //Felkészülünk a modal megnyitására, az elem törlésére
         prepareToDeleteItem(removeableId, removableName, removableType) {
            //Létrehozzuk a modal tartalmát
            this.createModalContent(
               'Elem törlése',
               'Biztos törölni akarod: ' + removableName +'? A törlés végleges',
               'Igen',
               'Nem');
            //Beállítjuk a törlendő elem ID-át, típusát
            this.deletableItemId = removeableId;
            this.deletableItemType = removableType;

            //Megjelenítjük a modalt
            $('#decisionModal').modal('show');
         },

         deleteItem() {
            // Létrehozunk egy változót a post végpontnak
            let deleteEndPoint;
            // És a listafrissítésnek
            let listUpdateEvent;
            //Elindítjuk a loading spinnert
            this.$emit('startPending');

            //Ha a törlendő elem típusa recept ...
            if (this.deletableItemType === 'recipe') {
               //A végpont változó az elemnek megfelelő végpontot kapja
               deleteEndPoint = '/deleterecipe';
               listUpdateEvent = 'fetchRecipes';
            } else if (this.deletableItemType === 'user') {
               deleteEndPoint = '/deleteuser';
               listUpdateEvent = 'fetchUsers';
            } else if (this.deletableItemType === 'ingredient') {
               deleteEndPoint = '/deleteingredient';
               listUpdateEvent = 'fetchIngredients';
            }

            //Elindul a törlés POST
            axios.post(deleteEndPoint, {
               removableId: this.deletableItemId,
            })
               .then((response) => {
                  //Emitelünk egy 'frissítés' eventet, amit a szülő komponens elkap, és lefrissíti a listát
                  this.$emit(listUpdateEvent);
                  //Emitelünk egy 'siker' eventet, amit a szülő oldalon elkap a parent, és lekezeli a successt
                  this.$emit('succesResponse', 'Sikeresen törölted az elemet!');
               })
               .catch((error) => {
                  if (error.response.status != 422) {
                     this.fetchedErrors = ['Hiba történt! Kérjük vegye fel a kapcsolatot az oldal üzemeltetőjével!']
                  }
                  else {
                     this.fetchedErrors = error.response.data;
                  }

                  //Emiteljük a hibaüzenetet a parentnek, ami lekezeli
                  this.$emit('fetchedErrors', this.fetchedErrors);
               });
         },

         //Létrehozzuk a felugró 'decisionModal' szöveges tartalmát
         createModalContent(header, body, yes, no) {
            this.modalContent.modalHeader = header;
            this.modalContent.modalBody = body;
            this.modalContent.modalYes = yes;
            this.modalContent.modalNo = no;
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
         },
      }
   }
</script>