<template>
    <b-modal v-model="showModal" body-class="p-0" style="--vz-modal-width: 700px;" header-class="p-3 bg-light" id="myModal"
        class="v-modal-custom" title="View Booking" modal-class="zoomIn" content-class="border-0" centered>
        <div class="modal-body">
            <div class="row mb-2">
                <div class="col-md-6">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <td class="fw-medium">Customer</td>
                                <td>{{booking.customer.name}}</td>
                            </tr>
                            
                            <tr>
                                <td class="fw-medium">Contact</td>
                                <td>{{booking.customer.mobile}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Check-in Date</td>
                                <td>{{booking.checkin_at}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Check-out Date</td>
                                <td>{{booking.checkout_at}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Status</td>
                                <td><b-badge :class="booking.status.color">{{booking.status.name}}</b-badge></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table mb-0">
                        <tbody>
                             <tr>
                                <td class="fw-medium">Building</td>
                                <td>{{booking.room.building.name}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Room</td>
                                <td>Floor {{booking.room.floor}} - Room {{booking.room.number}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Room Type</td>
                                <td>{{booking.room.roomtype.name}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Bed Type</td>
                                <td>{{booking.room.bedtype.name}}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Price</td>
                                <td>{{booking.price}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <template v-slot:footer>
            <b-button @click="showModal = false" variant="light" block>Cancel</b-button>
            <b-button v-if="type == 'checkin'" @click="create('ok')" variant="primary"  block>Check-in</b-button>
            <b-button v-else @click="create('ok')" variant="danger"  block>Check-out</b-button>
        </template>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            booking: {
                customer: {},
                room: { building: {}, bedtype: {}, roomtype: {}},
                status: {}
            },
            type: '',
            showModal : false
        }
    },
    methods : {
        show(data,type){
            this.booking = data;
            this.type = type;
            this.showModal = true;
        },
        hide(){
            this.showModal = false;
        },
        create() {
            this.form = this.$inertia.form({
                id: this.booking.id,
                type: this.type,
                status_id: (this.booking.status.name == 'Pending') ? 2 : 3
            })

            this.form.put('/bookings/update',{
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                }
            });
        },
    }
}
</script>