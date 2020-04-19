<template>
   <div class="alert-response">
      <transition-group name="bounce" tag="h3">
         <h3 v-for="(error, index) in errors"
             class="alert alert-danger text-center"
             @click="deleteError(index)"
             :key="error + '-' + index"
         >{{error.message}}</h3>
      </transition-group>
   </div>
</template>

<script>
   export default {
      name: "errorHandling",

      /*
       Megkapjuk a parent komponenstől a hibát 'nyers' objektum formában
       */
      props: {
         fetchedErrors: {},
      },

      /*
        Figyeljük a fetchedErrors prop változását. Ha változik, rögtön lefuttatjuk a handleErrors() methodot.
       */
      watch: {
         fetchedErrors() {
            this.handleErrors();
         }
      },

      data() {
         return {
            errors: [],
         }
      },

      methods: {
         deleteError(index) {
            this.errors.splice(index, 1);
         },

         handleErrors() {

            /*
             * Reseteljuk a jelenlegi errors tömböt
             *
             */

            this.errors = [];

            /*
            * Átalakítjuk a kapott objektumokat egy asszociatív tömbbé
            */
            let errors = Object.values(this.fetchedErrors);

            /*
            * Az asszociatív tömböt egy sima tömbbé alakítjuk (ha már úgy is egyedi hibaüzeneteket generálunk backenden,
            * akkor teljesen felesleges tovább bonyolítani
            *
            */
            errors = errors.flat();

            /*
            * Végigiterálunk a hibaüzeneteken, és ha még nem szerepel a Vue errors tömbjében,
            * akkor beletesszük.
            */
            for (let error of errors) {
               let alreadyHaveThisError = this.errors.filter(e => e.message === error).length > 0;
               if (!alreadyHaveThisError) {
                  this.errors.push({message: error});
               }
            }

            this.$emit('errorChanged', false);
         }
      }
   }
</script>
