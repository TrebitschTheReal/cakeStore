<template>
   <div class="col-12">
      <div class="col col-lg-6">
         <div class="row">
            <button class="col col-lg-6 btn btn-primary p-5"
                    @click="chooseOperation('register')"
            >Új recept regisztrálása
            </button>

            <button class="col col-lg-6 btn btn-warning p-5"
                    @click="chooseOperation('edit')"
            >Recept Módosítása
            </button>
         </div>
      </div>
      <div class="col col-lg-6">
         <div class="row">
            <button class="col col-lg-6 btn btn-success p-5"
            >Új alapanyag feltöltése
            </button>

            <button class="col col-lg-6 btn btn-danger p-5"
            >Recept törlése
            </button>
         </div>
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
