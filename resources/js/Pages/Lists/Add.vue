<template>
    <b-modal v-model="showModal" hide-footer body-class="p-0" header-class="p-3" id="myModal"
        class="v-modal-custom" :title="(editable) ? 'Edit '+type : 'Add ' +type" content-class="border-0 overflow-hidden" centered>
        <div class="modal-body">
            <form>
                <div class="row mt-0">
                    <div class="col-md-12">
                        <div class="form-group mb-2">
                            <label>Name: <span v-if="form.errors" v-text="form.errors.name" class="haveerror"></span></label>
                            <input type="text" class="form-control" v-model="name" style="text-transform: capitalize;">
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
            id: '',
            name: '',
            type: '',
            showModal : false,
            editable: false,
        }
    },

    methods : {
        show(data){
            this.type = data;
            this.editable = false;
            this.showModal = true;
        },
        create() {
            this.form = this.$inertia.form({
                id: this.id,
                name: this.name,
                type: this.type,
                editable: this.editable
            })

            if(!this.editable){
                this.form.post('/lists',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            }else{
                this.form.put('/lists/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            }
        },
        edit(data,type){
            this.id = data.id;
            this.name = data.name;
            this.type = type;
            this.editable = true;
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
            this.name = '';
        }
    }
}
</script>