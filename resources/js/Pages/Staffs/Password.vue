<template>
    <b-modal v-model="showModal" hide-footer body-class="p-0" header-class="p-3" id="myModal"
        class="v-modal-custom" title="Set Password" content-class="border-0 overflow-hidden" centered>
        <div class="modal-body">
            <form>
                <div class="row mt-0">
                    <div class="col-md-12">
                        <div class="form-group mb-2">
                            <label>Password: <span v-if="form.errors" v-text="form.errors.password" class="haveerror"></span></label>
                            <input type="password" class="form-control" v-model="password" >
                        </div>
                    </div>
                     <div class="col-md-12">
                        <div class="form-group mb-2">
                            <label>Re Password: <span v-if="form.errors" v-text="form.errors.password_confirmation" class="haveerror"></span></label>
                            <input type="password" class="form-control" v-model="password_confirmation">
                        </div>
                    </div>
                    <div class="text-end mt-3">
                        <b-button type="button"  @click="create()" variant="primary">Submit</b-button>
                    </div>
                    
                </div>
            </form>
        </div>
        
    </b-modal>
</template>
<script>
import Multiselect from '@suadelabs/vue3-multiselect';
export default {
    components : { Multiselect },
    data(){
        return {
            currentUrl: window.location.origin,
            form : {},
            id: '',
            password: '',
            confirm_password: '',
            showModal : false,
            editable: false,
        }
    },

    methods : {
        show(){
            this.user = {};
            this.editable = false;
            this.showModal = true;
        },
        create() {
            this.form = this.$inertia.form({
                id: this.id,
                password: this.password,
                password_confirmation: this.password_confirmation,
            })

            this.form.put('/home/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                }
            });
        },
        hide(){
            this.showModal = false;
            this.password = '';
            this.password_confirmation = '';
        },
        edit(data){
            this.id = data.id;
            this.editable = true;
            this.showModal = true;
        },
    }
}
</script>
<style>
.multiselect__single {
    font-size: 11px;
}
</style>