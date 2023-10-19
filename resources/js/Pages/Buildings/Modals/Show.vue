<template>
    <b-modal v-model="showModal" body-class="p-0" header-class="p-3" id="myModal"
        class="v-modal-custom" title="Show Room" content-class="border-0"  modal-class="zoomIn" no-close-on-backdrop centered>
        <div class="modal-body">
            <div class="row mb-2" v-if="status">
                <div class="col-md-12">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-medium">Room no.</td>
                                <td>{{room.number}}</td>
                            </tr>
                            
                            <tr>
                                <td class="fw-medium">Price</td>
                                <td>{{room.price}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Room Type</td>
                                <td>{{room.roomtype.name}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Bed Type</td>
                                <td>{{room.bedtype.name}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mb-2" v-else>
                <form class="customform">
                    <div class="col-md-12">
                        <div class="form-group mt-2 mb-3">
                            <label>Number: <span v-if="form.errors" v-text="form.errors.number" class="haveerror"></span></label>
                            <input type="text" class="form-control" v-model="room.number" readonly>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mt-2 mb-2">
                        <label>Room Type: <span v-if="form.errors" v-text="form.errors.roomtype" class="haveerror"></span></label>
                            <multiselect 
                                v-model="room.roomtype" 
                                :options="roomtypes"
                                :allow-empty="false"
                                :show-labels="true"
                                label="name" track-by="id"
                                placeholder="Select Room Type">
                            </multiselect>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mt-2 mb-2">
                        <label>Bed Type: <span v-if="form.errors" v-text="form.errors.bedtype" class="haveerror"></span></label>
                            <multiselect 
                                v-model="room.bedtype" 
                                :options="bedtypes"
                                :allow-empty="false"
                                :show-labels="true"
                                label="name" track-by="id"
                                placeholder="Select Bed Type">
                            </multiselect>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group mt-2 mb-3">
                            <label>Price: <span v-if="form.errors" v-text="form.errors.shortcut" class="haveerror"></span></label>
                            <input type="text" class="form-control" v-model="room.price">
                        </div>
                    </div>
                </form>
            </div>
            {{lists}}
        </div>
        <template v-slot:footer>
            <b-button @click="showModal = false" variant="light" block>Cancel</b-button>
            <b-button v-if="status" @click="update()" variant="primary" block>Update</b-button>
            <b-button v-else @click="create()" variant="primary" block>Save</b-button>
        </template>
    </b-modal>
</template>
<script>
import Multiselect from '@suadelabs/vue3-multiselect';
export default {
    components : { Multiselect },
    props: ['dropdowns'],
    data(){
        return {
            currentUrl: window.location.origin,
            showModal: false,
            form: {},
            room: {
                bedtype: {}, roomtype: {}
            },
            status: true,
        }
    },
    computed: {
        roomtypes: function () {
            return this.dropdowns.filter(x => x.type === 'Room Type');
        },
        bedtypes: function () {
            return this.dropdowns.filter(x => x.type === 'Bed Type');
        },
    },
    methods : {
        show(data){
            this.status = true;
            this.room = data;
            this.showModal = true;
        },
        update(){
            this.status = false;
        },
        create() {
            this.form = this.$inertia.form({
                id: this.room.id,
                room_type: this.room.roomtype.id,
                bed_type: this.room.bedtype.id,
                price: this.room.price
            })

            this.form.put('/rooms/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                }
            });
        },
        hide(){
            this.showModal = false;
        }
    }
}
</script>