<template>
<Head title="Login" />
    <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">

        <div class="auth-page-content">
            <b-container>

                <b-row class="justify-content-center">
                <b-col md="8" lg="6" xl="5">
                    <b-card no-body class="mt-4">
                    <b-card-body class="p-4">
                        <div class="text-center mt-2">
                        <h5 class="text-warning">Please update your password</h5>
                        </div>
                        <div class="p-2 mt-4">
                        <b-alert v-model="authError" variant="danger" class="mt-3" dismissible>{{ authError }}</b-alert>

                        <div>

                        </div>

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
                        <b-button type="button" @click="create()" variant="primary">Submit</b-button>
                    </div>
                    
                </div>
            </form>
                        </div>
                    </b-card-body>
                    </b-card>

                </b-col>
                </b-row>
            </b-container>
        </div>

      
  </div>
</template>
<script>
export default {
    layout: null,
    data(){
        return {
            currentUrl: window.location.origin,
            form : {},
            password: '',
            confirm_password: '',
        }
    },

    methods : {
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
            this.password = '';
            this.password_confirmation = '';
        }
    }
}
</script>