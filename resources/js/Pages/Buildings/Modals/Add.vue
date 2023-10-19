<template>
    <b-modal v-model="showModal" body-class="p-0" header-class="p-3" id="myModal"
        class="v-modal-custom" title="Add Room" content-class="border-0"  modal-class="zoomIn" no-close-on-backdrop centered>
        <div class="modal-body">
            <form class="customform">
                <div class="col-md-12">
                    <div class="form-group mb-2">
                    <label>Floor: <span v-if="form.errors" v-text="form.errors.floor" class="haveerror"></span></label>
                        <multiselect 
                            v-model="room.floor" 
                            :options="rooms"
                            :allow-empty="false"
                            :show-labels="true"
                            label="floor" track-by="floor"
                            placeholder="Select Floor">
                        </multiselect>
                    </div>
                </div>
                 <div class="col-md-12">
                    <div class="form-group mt-2 mb-3">
                        <label>Number: <span v-if="form.errors" v-text="form.errors.number" class="haveerror"></span></label>
                        <input type="text" class="form-control" v-model="room.number" readonly>
                    </div>
                </div>
                <div class="col-md-12">
                        <div class="form-group mt-2 mb-2">
                        <label>Room Type: <span v-if="form.errors" v-text="form.errors.room_type" class="haveerror"></span></label>
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
                        <label>Bed Type: <span v-if="form.errors" v-text="form.errors.bed_type" class="haveerror"></span></label>
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
                            <label>Price: <span v-if="form.errors" v-text="form.errors.price" class="haveerror"></span></label>
                            <input type="text" class="form-control" v-model="room.price">
                        </div>
                    </div>

            </form>
        </div>
         <template v-slot:footer>
            <b-button @click="showModal = false" variant="light" block>Cancel</b-button>
            <b-button @click="create()" variant="primary" block>Save</b-button>
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
                number: '',
                price: '',
                floor: '',
                bedtype: '',
                roomtype: '',
                building_id: ''
            },
            rooms: []
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
    watch: {
        'room.floor'() {
            console.log(this.room.floor);
            this.room.number = this.room.floor.total + 1;
        }
    },
    methods : {
        show(data,id){
            this.rooms = data;
            this.room.building_id = id;
            this.showModal = true;
        },
        create() {
            this.form = this.$inertia.form({
                id: this.room.id,
                number: this.room.number,
                floor: this.room.floor.floor,
                room_type: this.room.roomtype.id,
                bed_type: this.room.bedtype.id,
                building_id: this.room.building_id,
                price: this.room.price
            })

            this.form.post('/rooms',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                }
            });
        },

        hide(){
            this.room.number = '';
            this.room.price = '';
            this.room.floor = '';
            this.room.bedtype = '';
            this.room.roomtype = '';
            this.room.building_id = '';
            this.showModal = false;
        }
    }
}
</script>