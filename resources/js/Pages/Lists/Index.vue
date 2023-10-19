<template>

    <Head title="Lists" />
    <PageHeader :title="title" :items="items" />


    <div class="row">

        <div class="col-md-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Room Type</h4>
                    <div class="flex-shrink-0">
                        <button @click="add('Room Type')" class="btn btn-soft-primary btn-sm" type="button">
                            <div class="btn-content"> Add Type </div>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div data-simplebar style="height: calc(100vh - 320px)">
                        <div class="table-responsive ">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted bg-soft-light">
                                    <tr>
                                        <th class="text-center" width="10%">#</th>
                                        <th  width="90%">Name</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(list, index) of rooms" :key="index">
                                        <td class="text-center">{{index+1}}</td>
                                        <td>{{list.name}}</td>
                                        <td class="text-end">
                                            <b-button @click="edit(list,'Room Type')" variant="primary" size="sm" class="edit-list"><i class="ri-pencil-fill align-bottom"></i> </b-button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-height-100">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Bed Type</h4>
                    <div class="flex-shrink-0">
                        <button @click="add('Bed Type')" class="btn btn-soft-primary btn-sm" type="button">
                            <div class="btn-content"> Add Type </div>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div data-simplebar style="height: calc(100vh - 320px)">
                        <div class="table-responsive ">
                            <table class="table table-hover table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted bg-soft-light">
                                    <tr>
                                        <th class="text-center" width="10%">#</th>
                                        <th width="90%">Name</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(list, index) of beds" :key="index">
                                        <td class="text-center">{{index+1}}</td>
                                        <td>{{list.name}}</td>
                                        <td class="text-end">
                                            <b-button @click="edit(list,'Bed Type')" variant="primary" size="sm" class="edit-list"><i class="ri-pencil-fill align-bottom"></i> </b-button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Add ref="add" />
</template>
<script>
    import Add from './Add.vue';
    import Multiselect from '@suadelabs/vue3-multiselect';
    import PageHeader from "@/Shared/Components/PageHeader.vue";
    import flatPickr from "vue-flatpickr-component";
    export default {
        components: {
            PageHeader,
            Multiselect,
            flatPickr,
            Add
        },
        page: {
            title: "List of Dropdowns",
            meta: [{
                name: "description"
            }],
        },
        props: ['dropdowns'],
        data() {
            return {
                title: "Dropdown Management",
                items: [{
                    text: "Lists",
                    href: "/",
                }, {
                    text: "Type",
                    active: true,
                }, ],
                meta: {},
                links: {},
            };
        },

        computed: {
            rooms: function () {
                return this.dropdowns.filter(x => x.type === 'Room Type');
            },
            beds: function () {
                return this.dropdowns.filter(x => x.type === 'Bed Type');
            }
        },

        methods: {
            add(data) {
                this.$refs.add.show(data);
            },
            edit(data,type){
                this.$refs.add.edit(data,type);
            },
        }
    }

</script>
