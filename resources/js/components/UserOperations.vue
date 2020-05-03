<template>
   <div class="col-12">
      <h2 class="text-center">Felhasználók kezelése</h2>
      <hr>
      <transition name="bounce" mode="out-in">
         <form class="row"
               @submit.prevent="uploadUser">
            <div class="col-lg-4">
               <label class="control-label">Felhasználói név</label>
               <input required
                      class="form-control"
                      :disabled="pending"
                      v-model="userModel.name"
                      type="text">
            </div>
            <div class="col-lg-4">
               <label>Jogosultsági szint</label>
               <select class="form-control"
                       :disabled="pending"
                       name=""
                       id=""
                       required
                       v-model="userModel.role_name">
                  <option v-for="type in userRoleTypes"
                          :value="type"
                  >{{type}}
                  </option>
               </select>
            </div>
            <div class="col-lg-4">
               <label>E-mail cím</label>
               <input required
                      :disabled="pending"
                      class="form-control"
                      type="email"
                      v-model="userModel.email"
               >
            </div>
            <spinner v-if="pending"
                     class=""
            />
            <template v-else>
               <input :disabled="pending"
                      :class="modify ? 'submit-button-margin col-xs-12 col-lg-6 mt-3 mx-auto btn btn-warning' : 'submit-button-margin col-xs-12 col-lg-6 mt-3 mx-auto btn btn-block btn-success'"
                      type="submit"
                      :value="modify ? 'Meglévő felhasználó módosítása' : 'Új felhasználói fiók létrehozása'"
               />
            </template>
         </form>
      </transition>

      <transition name="bounce">
         <div class="my-3 alert-response" v-if="success">
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

      <h2 class="mt-3 text-center">Felhasználó kereső</h2>
      <hr>
      <div class="form-group">
         <input required
                type="text"
                v-model="search"
                class="form-control mx-auto col-lg-8"
                id="recipe-name"
                aria-describedby="recipe-name"
                @keyup="checkSearcher"
                placeholder="Kezd el írni a felhasználó nevét">
      </div>
      <tableView v-if="showList"
                 class="p-lg-5"
                 :filteredList="this.filteredList"
                 :tableData="'user'"
                 :pending="pending"
                 @fetchUsers="fetchUsers"
                 @succesResponse="generateSuccessResponse($event)"
                 @fetchedErrors="fetchedErrors = $event"
                 @startPending="pending = true"
                 @modifyUser="modifyUser($event)"
      />

   </div>
</template>

<script>
   import spinner from './partials/loadingSpinner';
   import errorHandler from "./partials/errorHandling";
   import tableView from './partials/tableView';

   export default {
      name: "stepUserUpload",

      components: {
         spinner,
         errorHandler,
         tableView
      },

      mounted() {
         this.fetchRoles();
         this.fetchUsers();
      },

      data() {
         return {
            fetchedErrors: {},
            successResponse: '',
            modify: false,
            showList: false,
            success: false,
            pending: false,
            userRoleTypes: ['Site admin', 'Manager', 'Regisztrált felhasználó'],
            search: '',
            userModel: {
               id: null,
               name: '',
               role_id: null,
               role_name: '',
               email: '',
               role: {}
            },
            fetchedRoles: {},
            fetchedUsers: {},
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
            return this.fetchedUsers.filter(user => {
               return user.name.toLowerCase().includes(this.search.toLowerCase())
            })
         },
      },

      methods: {
         uploadUser() {
            this.success = false;
            this.pending = true;

            this.matchRoles();
            console.log(this.userModel);

            axios.post('/modifyUser', {
               user: this.userModel,
            })
               .then((response) => {
                  this.resetInput();
                  this.success = true;
                  this.pending = false;
                  this.modify = false;
                  this.search = '';
                  this.showList = false;
                  this.fetchRoles();
                  this.fetchUsers();
                  this.fetchedErrors = {};
                  this.successResponse = 'Sikeres művelet!';

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
                  if(error.response.status == 500) {
                     this.fetchedErrors = ['Hiba történt! Kérjük vegye fel a kapcsolatot az oldal üzemeltetőjével!']
                  }
                  else {
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


         modifyUser(user) {
            this.modify = true;
            this.userModel.id = user.id;
            this.userModel.name = user.name;
            this.userModel.email = user.email;

            if (user.roles.length !== 0) {
               console.log(user.roles.length);
               this.userModel.role_name = user.roles[0].name;
               this.userModel.role_id = user.roles[0].id;

               console.log(this.userModel);
            }

            console.log(user.roles);
            console.log(user.roles[0]);
            console.log(user.roles[0].name);
            this.search = user.name;
         },

         fetchUsers() {
            axios.get('/getuserlist')
               .then((response) => {
                  console.log(response.data);
                  this.fetchedUsers = response.data;
                  this.showList = true;
                  this.pending = false;
               })
               .catch((error) => {
                  console.log(error);
               });
         },

         fetchRoles() {
            axios.get('/getroles')
               .then((response) => {
                  this.fetchedRoles = response.data;
                  console.log(this.fetchedRoles);
               })
               .catch((error) => {
                  console.log(error);
               });
         },

         resetInput() {
            this.userModel.id = null;
            this.userModel.name = null;
            this.userModel.role_name = null;
            this.userModel.email = null;
         },

         generateSuccessResponse(message) {
            this.successResponse = message;
            this.success = true;
         },

         matchRoles() {
            for (let role of this.fetchedRoles) {
               if (role.name == this.userModel.role_name) {
                  this.userModel.role_id = role.id;
               }
            }
         }
      },
   }
</script>
