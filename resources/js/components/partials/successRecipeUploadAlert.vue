<template>
   <div class="mx-auto text-center">
      <div class="alert alert-success">
         <h2>{{recipeSuccessMessage}}</h2>
      </div>
   </div>
</template>

<script>
   export default {
      props:{
         recipeSuccessMessage: String
      }
   }
</script>
