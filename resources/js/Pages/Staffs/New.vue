<template>
    <b-modal v-model="showModal" hide-footer body-class="p-0" header-class="p-3" id="myModal"
        class="v-modal-custom" :title="(editable) ? 'Edit Staff' : 'New Staff'" content-class="border-0 overflow-hidden" centered>
        <div class="modal-body">
            <form>
                <div class="row mt-0">
                    <div class="col-md-12">
                        <div class="form-group mb-2">
                            <label>Name: <span v-if="form.errors" v-text="form.errors.name" class="haveerror"></span></label>
                            <input type="text" class="form-control" v-model="user.name" style="text-transform: capitalize;">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-2">
                            <label>Email: <span v-if="form.errors" v-text="form.errors.email" class="haveerror"></span></label>
                            <input type="email" class="form-control" v-model="user.email" style="text-transform: capitalize;">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mb-2">
                            <label>Mobile No.: <span v-if="form.errors" v-text="form.errors.mobile" class="haveerror"></span></label>
                            <input type="tel" pattern="[0-9]{12}" class="form-control" v-model="user.mobile" style="text-transform: capitalize;">
                        </div>
                    </div>
                     <div class="col-md-12">
                        <div class="form-group mb-2">
                            <label>Role: <span v-if="form.errors" v-text="form.errors.mobile" class="haveerror"></span></label>
                             <select class="form-select mb-3" v-model="user.role" aria-label="Default select example">
                                <option selected>Select your role</option>
                                <option value="Receptionist">Receptionist</option>
                                <option value="Technician">Technician</option>
                                <option value="Supervisor">Supervisor</option>
                            </select>
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
            form : {},
            currentUrl: window.location.origin,
            user: {
                id: '',
                email: '',
                name: '',
                mobile: '',
                img: '',
                role: ''
            },
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
                id: this.user.id,
                name: this.user.name,
                email: this.user.email,
                mobile: this.user.mobile,
                role: this.user.role,
                img: this.user.img,
                editable: this.editable
            })

            if(!this.editable){
                this.form.post('/staffs',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            }else{
                this.form.put('/staffs/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            }
        },
        edit(data){
            this.user = data;
            this.editable = true;
            this.showModal = true;
        },
        hide(){
            this.$emit('status',true);
            this.showModal = false;
        }
    }
}
</script>
<style>
.multiselect__single {
    font-size: 11px;
}
</style>