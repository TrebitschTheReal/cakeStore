<template>
   <div class="col p-1 m-3">
      <div class="row">
         <button class="col-lg col-sm-12 btn btn-primary p-5 m-1"
                 @click="chooseOperation('register')"
         ><span class="h3">Új recept regisztrálása</span>
         </button>

         <button class="col-lg col-sm-12 btn btn-secondary p-5 m-1"
                 @click="chooseOperation('edit')"
         ><span class="h3">Recept Módosítása</span>
         </button>
      </div>
      <div class="row">
         <button class="col btn btn-success p-5 m-1"
                 @click="chooseOperation('ing-upload')"
         ><span class="h3">Alapanyagok kezelése</span>
         </button>
      </div>
   </div>
</template>

<script>
   export default {
      name: "stepOperationChoose",

      methods: {
         chooseOperation(operation) {
            this.$emit('choosenOperation', operation)
         }
      }
   }
</script>
