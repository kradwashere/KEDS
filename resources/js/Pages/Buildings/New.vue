<template>
    <b-modal v-model="showModal" hide-footer body-class="p-0" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" id="myModal"
        class="v-modal-custom" :title="(editable) ? 'Edit Building' : 'New Building'" modal-class="zoomIn" content-class="border-0 overflow-hidden" centered>
        <div class="modal-body">
            <form class="customform">
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Name: <span v-if="form.errors" v-text="form.errors.name" class="haveerror"></span></label>
                            <input type="text" class="form-control" v-model="building.name" style="text-transform: capitalize;">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <label>Shortcut: <span v-if="form.errors" v-text="form.errors.shortcut" class="haveerror"></span></label>
                            <input type="text" class="form-control" v-model="building.shortcut" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group mb-2">
                            <label>Address: <span v-if="form.errors" v-text="form.errors.address" class="haveerror"></span></label>
                            <input type="text" class="form-control" v-model="building.address" style="text-transform: capitalize;">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <hr>
                    </div>

                    <div class="col-md-12">
                        <div class="row mb-1">
                            <div class="col-md-12"><hr>
                                <button @click="add" style="margin-top: -55px;" class="btn btn-sm btn-primary pull-right" type="button">Add Floor</button>
                            </div>
                        </div>
                        <div class="row mb-1" v-for="(list, index) in lists" v-bind:key="'a-'+list.id+index">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Floor: </label>
                                    <input type="text" class="form-control" v-model="list.floor" readonly>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label>Pricing:</label>
                                            <input type="text" class="form-control" v-model="list.price">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bed Capacity:</label>
                                            <input type="text" class="form-control" v-model="list.bed">
                                        </div>
                                    </div>
                                    <div class="col-md-1" style="margin-top: 10px;">
                                        <a v-if="(index+1) == lists.length"><i @click="del(index)" class='bx bx-trash font-size-22 mt-3 float-left' style="margin-left: -15px;"></i></a>
                                    </div>   
                                </div>
                            </div>                                                
                        </div>
                    </div>


                     <div class="text-end mt-3">
                        <b-button type="button" :disabled="form.processing" @click="create()" variant="primary">Submit</b-button>
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
            building: {
                id: '',
                name: '',
                shortcut: '',
                address: ''
            },
            lists: [],
            count : 0,
            showModal : false,
            editable: false,
        }
    },

    methods : {
        show(){
            this.building = {};
            this.editable = false;
            this.showModal = true;
        },
        create() {
            this.form = this.$inertia.form({
                id: this.building.id,
                name: this.building.name,
                shortcut: this.building.shortcut,
                address: this.building.address,
                lists: this.lists,
                editable: this.editable
            })

            if(!this.editable){
                this.form.post('/buildings',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            }else{
                this.form.put('/buildings/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            }
        },
        add() {
            this.count = this.count + 1;
            this.lists.push({floor: this.count})
        },
        del(index) {
            if(this.lists.length > 1){
                this.lists.splice(index, 1);
            }
        },
        edit(data){
            this.building = data;
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