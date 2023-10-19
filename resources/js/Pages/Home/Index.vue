<template>

    <Head title="Dashboard" />
    <PageHeader :title="title" :items="items" />

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-light text-primary rounded-circle fs-3"><i class="ri-money-dollar-circle-fill align-middle"></i></span></div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Total Customers</p>
                            <h4 class="mb-0"><span class="counter-value">{{info[0]}}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-light text-primary rounded-circle fs-3"><i class="ri-arrow-up-circle-fill align-middle"></i></span></div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">For Check-in</p>
                            <h4 class="mb-0"><span class="counter-value">{{info[1]}}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0"><span class="avatar-title bg-light text-primary rounded-circle fs-3"><i class="ri-arrow-up-circle-fill align-middle"></i></span></div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">For Check-out</p>
                            <h4 class="mb-0"><span class="counter-value">{{info[2]}}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm flex-shrink-0"><span  class="avatar-title bg-light text-primary rounded-circle fs-3"><i class="ri-arrow-down-circle-fill align-middle"></i></span></div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-12 text-muted mb-1">Total Income</p>
                            <h4 class="mb-0"><span class="counter-value">{{info[3]}}</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="d-grid gap-2 mb-3" >
                <b-button @click="book()" variant="primary">Book Now</b-button>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row g-2">
                        
                        <b-alert show variant="warning" class="alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                            <i class="ri-user-smile-line label-icon"></i><strong>Availability</strong> today
                        </b-alert>
                        <div class="px-2 py-2 mt-1">
                            <div v-for="(list, index) of beds" :key="index" class="mb-2">
                                <p class="mb-1">{{list.bed}} Bed<span class="float-end">{{list.not_available}} out of {{list.count}} occupied</span></p>
                                <b-progress striped :value="list.percentage" class="mt-2 progress-lg" variant="success" />
                            </div>
                        </div>
                        <hr class="bg-light"/>
                        <div class="px-2 py-2 mt-1">
                            <div v-for="(list, index) of rooms" :key="index" class="mb-2">
                                <p class="mb-1">{{list.room}} Room<span class="float-end">{{list.not_available}} out of {{list.count}} occupied</span></p>
                                <b-progress striped :value="list.percentage" class="mt-2 progress-lg" variant="success" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <b-card-header class="align-items-center d-flex">
                    <b-card-title class="fs-13 mb-0 flex-grow-1 text-success">Today's Check-in</b-card-title>
                </b-card-header>
                <div class="card-body" style="height: 400px;">
                    <div class="table-responsive table-card">
                        <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                            <tbody>
                                <tr v-for="(list, index) of checkins" :key="index" style="cursor: pointer;" @click="view(list,'checkin')">
                                    <td>
                                        <h5 class="fs-13 mb-0 text-dark">Floor {{list.room.floor}} - Room {{list.room.number}}</h5>
                                        <p class="fs-12 text-muted mb-0">{{list.room.building.name}}</p>
                                    </td>
                                    <td class="text-end">
                                        <h5 class="fs-13 mb-0 text-dark">{{list.customer.name}} </h5>
                                        <p class="fs-12 text-muted mb-0">{{list.customer.mobile}}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <b-card-header class="align-items-center d-flex">
                    <b-card-title class="fs-13 mb-0 flex-grow-1 text-danger">Today's Check-out</b-card-title>
                </b-card-header>
                <div class="card-body" style="height: 400px;">
                    <div class="table-responsive table-card">
                        <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                            <tbody>
                                <tr v-for="(list, index) of checkouts" :key="index" style="cursor: pointer;" @click="view(list,'checkout')">
                                    <td>
                                        <h5 class="fs-13 mb-0 text-dark">Floor {{list.room.floor}} - Room {{list.room.number}}</h5>
                                        <p class="fs-12 text-muted mb-0">{{list.room.building.name}}</p>
                                    </td>
                                    <td class="text-end">
                                        <h5 class="fs-13 mb-0 text-dark">{{list.customer.name}} </h5>
                                        <p class="fs-12 text-muted mb-0">{{list.customer.mobile}}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <b-card-header class="align-items-center d-flex">
                    <b-card-title class="fs-13 mb-0 flex-grow-1 text-primary">Current Bookings</b-card-title>
                </b-card-header>
                <div class="card-body" style="height: 400px;">
                    <div class="table-responsive table-card">
                        <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                            <tbody>
                                <tr v-for="(list, index) of currents" :key="index" style="cursor: pointer;" @click="view(list,'current')">
                                    <td>
                                        <h5 class="fs-13 mb-0 text-dark">Floor {{list.room.floor}} - Room {{list.room.number}}</h5>
                                        <p class="fs-12 text-muted mb-0">{{list.room.building.name}}</p>
                                    </td>
                                    <td class="text-end">
                                        <h5 class="fs-13 mb-0 text-dark">{{list.customer.name}} </h5>
                                        <p class="fs-12 text-muted mb-0">{{list.customer.mobile}}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Book ref="book" :customers="customers" @status="message"/>
    <View ref="view"/>
</template>
<script>
import View from '../Bookings/View.vue';
import Book from '../Bookings/New.vue';
import { VueDraggableNext } from 'vue-draggable-next';
import PageHeader from "@/Shared/Components/PageHeader.vue";
export default {
    props: ['info','beds','rooms','customers','checkins','checkouts','currents'],
    components: {
        PageHeader,
        draggable: VueDraggableNext,
        Book, View
    },
    data() {
        return {
            currentUrl: window.location.origin,
            title: "Dashboard",
            items: [{
                text: "Lists",
                href: "/",
            }, {
                text: "Service",
                active: true,
            }, ],
            meta: {},
            links: {},
            enabled: true,
            dragging: false,
            searched: [],
            keyword: ''
        };
    },
    methods: {
        book(){
            this.$refs.book.show();
        },
        message(){

        },
        view(data,type){
            this.$refs.view.show(data,type);
        }
    }
}
</script>
