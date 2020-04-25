<template>
   <div class="mx-auto">
      <div class="m-lg-4 m-xs-2">
         <h2 class="text-center">Felhasználók kezelése</h2>
         <hr>
         <transition name="bounce" mode="out-in">
            <div class="form-group">
               <div class="">
                  <form class="row"
                        v-on:submit.prevent="uploadManager">
                     <div class="col col-lg-6 col-xs-6 text-center">
                        <p>Felhasználói név</p>
                        <input required
                               class="form-control"
                               :disabled="pending"
                               v-model="userModel.name"
                               type="text">
                     </div>
                     <div class="col col-lg-3 col-xs-2 text-center">
                        <p>Jogosultsági szint</p>
                        <select class="form-control"
                                :disabled="pending"
                                name=""
                                id=""
                                required
                                v-model="userModel.role"
                        >
                           <option v-for="type in userRoleTypes"
                                   :value="type"
                           >{{type}}
                           </option>
                        </select>
                     </div>
                     <div class="col col-lg-3 col-xs-1 text-center">
                        <p>E-mail cím</p>
                        <input required
                               :disabled="pending"
                               class="form-control"
                               type="text"
                               v-model="userModel.email"
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
                               value="Meglévő felhasználó módosítása"
                        />

                        <input v-else
                               :disabled="pending"
                               class="mt-4 col col-lg-12 btn btn-success"
                               type="submit"
                               value="Új felhasználói fiók létrehozása"
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

      <h2 class="text-center">Felhasználó kereső</h2>
      <hr>

      <div class="col-lg-6 col-xs-12 form-group mx-auto">
         <input required
                type="text"
                v-model="search"
                class="form-control" id="recipe-name"
                aria-describedby="recipe-name"
                @keyup="checkSearcher"
                placeholder="Kezd el írni az alapanyag nevét">
      </div>
      <tableView v-if="showList"
                 :filteredList="this.filteredList"
                 :tableData="'user'"
                 :pending="pending"
                 @fetchUsers="fetchUsers"
                 @succesResponse="generateSuccessResponse($event)"
                 @fetchedErrors="fetchedErrors = $event"
                 @startPending="pending = true"
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
               role: '',
               email: '',
            },
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
            this.userModel.name = this.userModel.name.toLowerCase();

            axios.post('/registernewuser', {
               users: this.userModel,
            })
               .then((response) => {
                  this.resetInput();
                  this.success = true;
                  this.pending = false;
                  this.modify = false;
                  this.search = '';
                  this.showList = false;
                  this.fetchUsers();
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

         uploadModifiedUser() {
            this.success = false;
            this.pending = true;
            this.userModel.name = this.userModel.name.toLowerCase();

            axios.post('/modifyexistinguser', {
               users: this.userModel,
            })
               .then((response) => {
                  this.resetInput();
                  this.success = true;
                  this.pending = false;
                  this.modify = false;
                  this.search = '';
                  this.showList = false;
                  this.fetchUsers();
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


         modifyUser(user) {
            this.modify = true;
            this.userModel.id = user.id;
            this.userModel.name = user.name;
            this.userModel.email = user.email;

            if(user.roles.length !== 0) {
               console.log(user.roles.length);
               this.userModel.role = user.roles[0].name;
            }

            console.log(user.roles);
            console.log(user.roles[0]);
            console.log(user.roles[0].name);
            this.search = user.name;
         },

         fetchUsers() {
            axios.get('/getuserlist')
               .then((response) => {
                  this.fetchedUsers = response.data;
                  this.showList = true;
                  this.pending = false;
               })
               .catch((error) => {
                  console.log(error);
               });
         },

         resetInput() {
            this.userModel.name = '';
            this.userModel.unit_type = 'típus';
            this.userModel.unit_price = null;
         },

         uploadManager() {
            this.modify ? this.uploadModifiedUser() : this.uploadUser();
         },

         generateSuccessResponse(message) {
            this.successResponse = message;
            this.success = true;
         },
      },
   }
</script>
