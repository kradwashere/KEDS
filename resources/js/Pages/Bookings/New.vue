<template>
    <b-modal v-model="showModal" hide-footer body-class="p-0" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" id="myModal"
        class="v-modal-custom" :title="(editable) ? 'Edit Booking' : 'New Booking'" modal-class="zoomIn" content-class="border-0" centered>
        <div class="modal-body">
            <form class="customform">
                <div class="row mt-2">
                    
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Date: <span v-if="form.errors" v-text="form.errors.customer" class="haveerror"></span></label>
                            <div class="input-group">
                                <flat-pickr v-model="booking.date" :config="config" class="form-control border-1 dash-filter-picker shadow" style="height: 43px;"></flat-pickr>
                                <div class="input-group-text bg-primary border-primary text-white" style="height: 43px;">
                                        <i class="ri-calendar-2-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label>Check-in: <span v-if="form.errors" v-text="form.errors.checkin_at" class="haveerror"></span></label>
                            <input type="text" class="form-control" :value="checkin" readonly>
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label>Check-out: <span v-if="form.errors" v-text="form.errors.checkout_at" class="haveerror"></span></label>
                            <input type="text" class="form-control" :value="checkout" readonly>
                        </div>
                    </div>

                     <div class="col-md-12"><hr class="mb-4"/></div>

                    <!-- <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Building: <span v-if="form.errors" v-text="form.errors.building" class="haveerror"></span></label>
                             <Multiselect v-model="booking.building" :options="buildings" :allow-empty="false" track-by="id" label="name"
                            placeholder="Select Building" />
                        </div>
                    </div> -->
                    
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Room: <span v-if="form.errors" v-text="form.errors.room_id" class="haveerror"></span></label>
                             <Multiselect v-model="booking.room" :options="rooms" :allow-empty="false" track-by="id"
                             :custom-label="nameWithLang"
                            placeholder="Select Room" />
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Customer: <span v-if="form.errors" v-text="form.errors.customer_id" class="haveerror"></span></label>
                             <Multiselect v-model="booking.customer" :options="customers" :allow-empty="false" label="name" track-by="id"
                            placeholder="Select Customer" />
                        </div>
                    </div>

                    
                    <div class="col-md-12 mt-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" v-model="booking.is_paid" id="gridCheck"/>
                            <label class="form-check-label" for="gridCheck">Is paid?</label>
                        </div>
                    </div>
                     <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" v-model="booking.is_walkin" id="gridCheck1"/>
                            <label class="form-check-label" for="gridCheck1">Is walk-in?</label>
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
import { format } from 'date-fns';
import flatPickr from "vue-flatpickr-component";
import Multiselect from '@suadelabs/vue3-multiselect';
export default {
    components : { Multiselect, flatPickr },
    props: ['customers','buildings'],
    data(){
        return {
            form : {},
            currentUrl: window.location.origin,
            booking: {
                id: '',
                building: '',
                room: '',
                customer: '',
                date: '',
                is_paid: false,
                is_walkin: false
            },
            config: {
                mode: "range",
            },
            lists: [],
            rooms: [],
            count : 0,
            showModal : false,
            editable: false,
            splits: '',
            checkin: '',
            checkout: '',
            checkin2: '',
            checkout2: ''
        }
    },

    watch: {
        'booking.date'() {
            this.splits = this.booking.date.split('to');
            this.checkin =  (this.splits[0]) ? format(new Date(this.splits[0].trim()), 'MMMM d, yyyy') : '';
            this.checkout = (this.splits[1]) ? format(new Date(this.splits[1].trim()), 'MMMM d, yyyy') : '';
            this.booking.room = '';
            this.fetch();
        }
    },

    methods : {
        nameWithLang ({ number,floor,building,price }) {
            return `Bldg. ${building.name} — Floor ${floor} — Room ${number} (₱${price}))`
        },
        show(){
            this.editable = false;
            this.showModal = true;
        },
        fetch() {
            axios.get(this.currentUrl + '/bookings', {
                params: {
                    date: this.booking.date,
                    type: 'rooms'
                }
            })
            .then(response => {
                if (response) {
                    this.rooms = response.data.data;
                    this.checkin2 = response.data.checkin_at,
                    this.checkout2 = response.data.checkout_at
                }
            })
            .catch(err => console.log(err));
        },
        create() {
            this.form = this.$inertia.form({
                id: this.booking.id,
                room_id: this.booking.room.id,
                price: this.booking.room.price,
                customer_id: this.booking.customer.id,
                checkin_at: this.checkin2,
                checkout_at: this.checkout2,
                is_paid: this.booking.is_paid,
                is_walkin: this.booking.is_walkin
            })

            if(!this.editable){
                this.form.post('/bookings',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            }else{
                this.form.put('/bookings/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
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