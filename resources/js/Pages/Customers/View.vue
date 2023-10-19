<template>
    <b-modal v-model="showModal" hide-footer body-class="p-0" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" id="myModal"
        class="v-modal-custom" title="View Customer" modal-class="zoomIn" content-class="border-0 overflow-hidden" centered>
        <div class="modal-body">
           
        </div>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            showModal : false,
        }
    },
    methods : {
        show(){
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>