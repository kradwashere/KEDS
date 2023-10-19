<template>
    <b-modal v-model="showModal" hide-footer :hide-header="!this.$page.props.auth.data.is_active" body-class="p-0" header-class="p-3" id="myModal"
        class="v-modal-custom" title="New Password" content-class="border-0 overflow-hidden" no-close-on-backdrop centered>
        <div class="modal-body">
            <form>
                <div class="row mt-0">
                    <div class="col-md-12 mb-4" v-if="!this.$page.props.auth.data.is_active">
                        <div class="alert alert-danger mb-xl-0" role="alert"><strong>Please update your password.</strong> Thanks</div>
                    </div>
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
                        <b-button type="button" @click="create()" variant="primary">Submit</b-button>
                    </div>
                    
                </div>
            </form>
        </div>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            form : {},
            password: '',
            confirm_password: '',
            showModal : false,
            editable: false,
        }
    },

    mounted(){
       (!this.$page.props.auth.data.is_active) ? this.showModal = true : '';
    },

    methods : {
        show(data){
            this.type = data;
            this.showModal = true;
        },
        create() {
            this.form = this.$inertia.form({
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
        }
    }
}
</script>