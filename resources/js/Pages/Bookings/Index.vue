<template>
<Head title="Buildings" />
    <PageHeader :title="title" :items="items" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
            
            <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px)">
                
                <div class="p-3 bg-light rounded mb-4">
                    <b-row class="g-2">
                        <b-col lg>
                            <div class="search-box">
                                <input type="text" id="searchTaskList" v-model="keyword" class="form-control search" placeholder="Search name">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </b-col>
                        <b-col lg="auto">
                            <b-button variant="primary"  class="createTask" @click="add"> <i
                                    class="ri-add-fill align-bottom"></i> New Booking
                            </b-button>
                        </b-col>
                    </b-row>
                </div>

                <div class="card-body pt-0 mt-n4">
                    <div>
                        <ul class="nav nav-tabs nav-tabs-custom nav-success mb-1" role="tablist">
                            <li class="nav-item">
                                <b-link class="nav-link active All py-3" data-bs-toggle="tab" id="All" href="#home1"
                                    role="tab" @click="changestatus('')" aria-selected="true">
                                    <i class="ri-store-2-fill me-1 align-bottom"></i> All Bookings
                                </b-link>
                            </li>
                            <li class="nav-item">
                                <b-link class="nav-link py-3" data-bs-toggle="tab" id="Pending"
                                    href="#pending" role="tab" @click="changestatus(1)">
                                    <i class="ri-time-line me-1 align-bottom"></i> For Check-in
                                </b-link>
                            </li>
                            <li class="nav-item">
                                <b-link class="nav-link py-3" data-bs-toggle="tab" id="Ongoing" href="#ongoing"
                                    role="tab" @click="changestatus(2)" aria-selected="false">
                                    <i class="ri-arrow-left-right-fill me-1 align-bottom"></i>For Check-out
                                </b-link>
                            </li>
                             <li class="nav-item">
                                <b-link class="nav-link py-3" data-bs-toggle="tab" id="Ongoing" href="#ongoing"
                                    role="tab" @click="changestatus(3)" aria-selected="false">
                                    <i class="ri-checkbox-circle-line me-1 align-bottom"></i>Completed
                                </b-link>
                            </li>
                            <li class="nav-item">
                                <b-link class="nav-link py-3" data-bs-toggle="tab" id="Ongoing" href="#ongoing"
                                    role="tab" @click="changestatus(4)" aria-selected="false">
                                    <i class="ri-close-circle-line me-1 align-bottom"></i>Cancelled
                                </b-link>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="todo-content position-relative px-4 mx-n4" id="todo-content">
                    <div class="todo-task" id="todo-task">
                        <div class="table-responsive">
                            <table class="table table-nowrap align-middle">
                                <thead class="text-muted table-light fs-11">
                                    <tr>
                                        <th scope="col">Name</th>
                                        <th scope="col" class="text-center">Room</th>
                                        <th scope="col" class="text-center">Check-in Date</th>
                                        <th scope="col" class="text-center">Check-out Date</th>
                                         <th scope="col" class="text-center">Is walk-in</th>
                                        <th scope="col" class="text-center">Price</th>
                                        <th scope="col" class="text-center">Is paid</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>

                                <tbody id="task-lists">
                                    <tr v-for="(list, index) of lists" :key="index" :class="list.color">
                                        <td class="fw-semibold">
                                            <h5 class="fs-13 mb-0 text-dark">{{list.customer.name}}</h5>
                                            <p class="fs-12 text-muted mb-0">{{list.customer.mobile}}</p>
                                        </td>
                                        <td class="text-center">
                                            <h5 class="fs-13 mb-0 text-dark">Room {{list.room.number}} - Floor {{list.room.floor}}</h5>
                                            <p class="fs-12 text-muted mb-0">Bldg. {{list.room.building.name}}</p>
                                        </td>
                                        <td class="text-center">{{ list.checkin_at}}</td>
                                        <td class="text-center">{{ list.checkout_at}}</td>
                                        <td class="text-center">
                                            <span v-if="list.is_paid" class='badge bg-warning'>Walk-in</span>
                                            <span v-else class='badge bg-info'>Reservation</span>
                                        </td>
                                        <td class="text-center">{{ list.price}}</td>
                                        <td class="text-center">
                                            <span v-if="list.is_paid" class='badge badge-soft-success'>Paid</span>
                                            <span v-else class='badge badge-soft-danger'>Unpaid</span>
                                        </td>
                                        <td class="text-end">
                                            <b-button @click="view(list,list.type)" variant="primary" size="sm" class="edit-list me-1"><i class="ri-eye-fill align-bottom"></i> </b-button>
                                            <b-button @click="edit(list)" variant="primary" size="sm" class="edit-list"><i class="ri-pencil-fill align-bottom"></i> </b-button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
       <New ref="new" :customers="customers" :buildings="buildings" @status="message"/>
        <View ref="view"/>
</template>
<script>
import View from './View.vue';
import New from './New.vue';
import Multiselect from '@suadelabs/vue3-multiselect';
import PageHeader from "@/Shared/Components/PageHeader.vue";
import flatPickr from "vue-flatpickr-component";
export default {
    props: ['customers', 'buildings'],
    components : { PageHeader, Multiselect, flatPickr, New, View},
    data() {
        return { 
            currentUrl: window.location.origin,
            title: "Bookings Management",
            items: [{text: "Lists",href: "/",},{text: "Booking",active: true,},],
            lists : [],
            meta: {},
            links: {},
            keyword: '',
            status: ''
        };
    },
    watch : {
        keyword(newVal){
            this.checkSearchStr(newVal)
        }
    },
    created(){
        this.fetch();
    },
    methods : {
        checkSearchStr: _.debounce(function(string) {
            this.fetch();
        }, 300),
        fetch() {
            axios.get(this.currentUrl + '/bookings', {
                params: {
                    keyword: this.keyword,
                    counts: ((window.innerHeight-350)/56),
                    status: this.status,
                    type: 'lists'
                }
            })
            .then(response => {
                if (response) {
                    this.lists = response.data.data;
                    this.meta = response.data.meta;
                    this.links = response.data.links;
                }
            })
            .catch(err => console.log(err));
        },
        add(){
            this.$refs.new.show();
        },
        edit(data){
            this.$refs.new.edit(data);
        },
        show(data){
            this.$refs.password.edit(data);
        },
        message(){
            this.fetch();
        },
        view(data,type){
            this.$refs.view.show(data,type);
        },
        changestatus(status){
            this.status = status;
            this.fetch();
        }
    }
}
</script>
