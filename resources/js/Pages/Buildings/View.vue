<template>
<b-row>
        <b-col lg="12">
            <b-card no-body class="mt-n4 mx-n4">
                <div class="bg-soft-warning">
                    <b-card-body class="pb-0 px-4">
                        <b-row class="mb-3">
                            <b-col md>
                                <b-row class="align-items-center g-3">
                                    <b-col md="auto">
                                        <div class="avatar-md">
                                            <span :class="'avatar-title rounded-circle bg-primary text-white fs-24'">{{selected.name[0]}}</span>
                                        </div>
                                    </b-col>
                                    <b-col md>
                                        <div>
                                            <h4 class="fw-bold">{{selected.name}}</h4>
                                            <div class="hstack gap-3 flex-wrap">
                                                <div><i class="ri-building-line align-bottom me-1"></i>
                                                    {{ selected.shortcut }}
                                                </div>
                                                <div class="vr"></div>
                                                <div><i class="ri-map-pin-2-fill align-bottom me-1"></i>
                                                    {{ selected.address }}
                                                </div>
                                                <div class="vr"></div>
                                                <div><i class="ri-home-6-line align-bottom me-1"></i>
                                                    {{ selected.available }} out of {{ selected.rooms }}
                                                </div>
                                            </div>
                                        </div>
                                    </b-col>
                                </b-row>
                            </b-col>
                            <b-col md="auto">
                                <div class="hstack gap-1 flex-wrap">
                                    <button @click="addFloor()" class="btn btn-primary btn-sm" type="button">
                                        <div class="btn-content"> Add Floor </div>
                                    </button>
                                </div>
                            </b-col>
                        </b-row>

                        <ul class="nav nav-tabs-custom border-bottom-0" role="tablist">
                            <li class="nav-item" v-for="(list, index) of rooms" :key="index">
                                <b-link class="nav-link fw-semibold" :class="(index == 0) ? 'active' : ''" data-bs-toggle="tab" v-bind:href="'#info'+list.floor" role="tab">
                                    Floor no.{{list.floor}}
                                </b-link>
                            </li>
                        </ul>
                    </b-card-body>
                </div>
            </b-card>
        </b-col>
    </b-row>
    <b-row>
       <b-col lg="12">
            <div class="tab-content text-muted">
                <div v-for="(list, index) of rooms" :key="index" class="tab-pane fade" :class="(index == 0) ? 'show active' : ''" :id="'info'+list.floor" role="tabpanel">
                     <b-col lg="12">
                        <b-col lg="12">
                            <b-card>
                                <b-card-body style="height: calc(100vh - 355px)">
                                    <div class="table-responsive mt-4">
                                        <table class="table table-bordered mb-0">
                                            <tbody>
                                                <tr v-for="(bed,a) in list.beds" v-bind:key="a" style="cursor: pointer;">
                                                    <td class="text-center" v-for="(r,index) in bed" v-bind:key="index" @click="show(r)">
                                                        <span class="font-size-11">Room {{ r.number }}</span> <br>
                                                        <i class='bx bxs-check-circle text-success font-size-14' v-if="r.is_available == 1"></i>
                                                        <i class='bx bxs-x-circle text-danger font-size-14' v-else></i>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </b-card-body>
                            </b-card>
                        </b-col>
                    </b-col>
                </div>
            </div>
       </b-col>
    </b-row>
    <Add :dropdowns="dropdowns" ref="add"/>
    <Show :dropdowns="dropdowns" ref="show"/>
</template>
<script>
import Show from './Modals/Show.vue';
import Add from './Modals/Add.vue';
export default {
    props: ['building','rooms','dropdowns'],
    components : {Add, Show},
    data() {
        return { 
            currentUrl: window.location.origin,
            title: "Buildings Management",
            items: [{text: "Lists",href: "/",},{text: "Building",active: true,},],
            lists : [],
            meta: {},
            links: {},
            keyword: '',
            selected: {},
            floors: ['test 1','test 2']
        };
    },
    created(){
        this.selected = this.building.data;
    },
    methods : {
        addFloor(){
            this.$refs.add.show(this.rooms,this.selected.id);
        },
        show(data){
            this.$refs.show.show(data);
        }
    }
}
</script>
