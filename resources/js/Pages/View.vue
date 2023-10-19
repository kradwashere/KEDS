<template>
    <b-modal v-model="showModal" hide-footer body-class="p-0" size="lg" header-class="p-3" id="myModal"
        class="v-modal-custom" content-class="border-0 overflow-hidden" centered>
        <div class="modal-body">
             <div class="modal-body">
            <b-row class="gx-lg-5 mt-n3">
                
                <b-col xl="12">
                    <div class="mt-xl-0">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <span :class="'fs-12 float-end badge bg-'+list.status.color"><i class="fs-12 mdi mdi-circle-medium"></i> {{list.status.name}}</span>
                                <h4>#{{list.code}} </h4>
                                <div class="hstack gap-3 flex-wrap">
                                    <div class="text-muted">
                                       Client : <span class="text-success fw-medium">{{list.client.name}}</span>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="text-muted">
                                       Technician : <span class="text-success fw-medium">{{list.technician.name}}</span>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="text-muted">
                                        Unit : <span class="text-body fw-medium">{{list.unit.name}}</span>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="text-muted">
                                        Brand : <span class="text-body fw-medium">{{list.brand.name}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <b-row class="mt-4">
                            <b-col lg="4">
                                <div class="p-2 border border-dashed rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                <i class="ri-rotate-lock-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1">Model :</p>
                                            <h6 class="mb-0">{{ list.model }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                            <b-col lg="4">
                                <div class="p-2 border border-dashed rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                <i class="ri-barcode-box-line"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1">Serial Number :</p>
                                            <h6 class="mb-0">{{ list.serial_no }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                            <b-col lg="4">
                                <div class="p-2 border border-dashed rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <div class="avatar-title rounded bg-transparent text-success fs-24">
                                                <i class=" ri-price-tag-2-line "></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="text-muted mb-1">Grand Total :</p>
                                            <h6 class="mb-0">₱{{ list.total }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </b-col>
                        </b-row>

                        <b-row>
                            <b-col sm="12">
                                <b-progress class="mt-4 animated-progress custom-progress progress-label">
                                    <b-progress-bar :value="(list.done/list.count) * 100" :variant="(((list.done/list.count) * 100) == 100) ? 'success' : 'warning'">
                                    <div class="label">{{(list.done/list.count) * 100}}%</div>
                                    </b-progress-bar>
                                </b-progress>
                                <div class="border border-dashed rounded p-3 mt-3">
                                    <h5 class="fs-14">Services :</h5>
                                    
                                    <ul class="list-group list-group-flush border-dashed px-3">
                                        <li class="list-group-item ps-0" v-for="(service, index) of list.services" :key="index">
                                            <div class="d-flex align-items-start">
                                            <div class="form-check ps-0 flex-sharink-0">
                                                <input v-on:change="check(service,'service')" type="checkbox" v-model="service.is_completed" class="form-check-input ms-0" :id="service.id" :disabled="true"/>
                                            </div>
                                            <div class="flex-grow-1">
                                                <label class="form-check-label mb-0 ps-2" :for="service.id">{{ service.service.name }}</label>
                                            </div>
                                            <div class="flex-shrink-0 ms-2">
                                                <p class="text-muted fs-12 mb-0">₱{{ service.amount }}</p>
                                            </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </b-col>

                            <b-col sm="12" v-if="list.parts.length > 0">
                                <div class="border border-dashed rounded p-3 mt-3">
                                    <h5 class="fs-14">Parts :</h5>
                                    <ul class="list-group list-group-flush border-dashed px-3">
                                        <li class="list-group-item ps-0" v-for="(part, index) of list.parts" :key="index">
                                            <div class="d-flex align-items-start">
                                            <div class="form-check ps-0 flex-sharink-0">
                                                <input v-on:change="check(part,'part')" type="checkbox" v-model="part.is_installed" class="form-check-input ms-0" :id="part.id" :disabled="true"/>
                                            </div>
                                            <div class="flex-grow-1">
                                                <label class="form-check-label mb-0 ps-2" :for="part.id">{{ part.name }}</label>
                                            </div>
                                            <div class="flex-shrink-0 ms-2">
                                                <p class="text-muted fs-12 mb-0">₱{{ part.amount }}</p>
                                            </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </b-col>
                        </b-row>

                        
                    </div>

                    <div class="alert alert-secondary mt-4" role="alert">For questions, you can contact <strong> (062) 975-3373 </strong> or <strong>  (0927) 717-5421 </strong></div>
                </b-col>
            </b-row>
        </div>
        </div>
    </b-modal>
</template>
<script>
export default {
    data(){
        return {
            currentUrl: window.location.origin,
            showModal : false,
            list: { 
                    unit : {}, brand: {}, client: {}, technician: {}, images: [], services: [], parts: [], status : {}
                },
        }
    },

    methods : {
        show(data){
            this.list = data[0];
            console.log(this.list);
            this.showModal = true;
        },
       
        hide(){
            this.showModal = false;
        }
    }
}
</script>