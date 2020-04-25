<template>
   <div class="modal fade"
        id="decisionModal"
        aria-labelledby="decisionModal"
        aria-hidden="true"
        tabindex="-1"
        role="dialog">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <h5 class="font-weight-bold modal-title">{{modalContent.modalHeader}}</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <h5 class="text-center justify-content-center">{{modalContent.modalBody}}</h5>
            </div>
            <div class="modal-footer">
               <button @click="yesClicked()"
                       type="button"
                       data-dismiss="modal"
                       class="btn btn-success">{{modalContent.modalYes}}</button>
               <button type="button"
                       class="btn btn-danger"
                       data-dismiss="modal">{{modalContent.modalNo}}</button>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
   export default {
      name: "decisionModal",
      props: {
         modalContent: {
            modalHeader: String,
            modalBody: String,
            modalYes: String,
            modalNo: String,
         }
      },

      data() {
         return {

         }
      },

      methods: {
         yesClicked() {
            this.$emit('deleteConfirmed');
         }
      }
   }
</script>