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
                                    class="ri-add-fill align-bottom"></i> New Building
                            </b-button>
                        </b-col>
                    </b-row>
                </div>

                <div class="todo-content position-relative px-4 mx-n4" id="todo-content">
                    <div class="todo-task" id="todo-task">
                        <div class="table-responsive">
                            <table class="table align-middle position-relative table-nowrap">
                                <thead class="table-active">
                                    <tr>
                                        <th></th>
                                        <th scope="col">Name</th>
                                        <th scope="col" class="text-center">Shortcut</th>
                                        <th scope="col" class="text-center">Address</th>
                                        <th scope="col" class="text-center">Availability</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>

                                <tbody id="task-lists">
                                    <tr v-for="(list, index) of lists" :key="index">
                                        <td>
                                            <div class="avatar-xs">
                                                <span :class="'avatar-title rounded-circle bg-'+list.g+' text-white'">{{list.name[0]}}</span>
                                            </div>
                                        </td>
                                        <td class="fw-semibold">{{ list.name}}</td>
                                        <td class="text-center">{{ list.shortcut}}</td>
                                        <td class="text-center">{{ list.address}}</td>
                                        <td class="text-center">{{ list.available}} of {{list.rooms}}</td>
                                        <td class="text-end">
                                            <Link :href="`/buildings/${list.id}`"><b-button variant="primary" v-b-tooltip.hover title="View" size="sm" class="remove-list me-1"><i class="ri-eye-fill align-bottom"></i></b-button></Link>
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
       <New ref="new" @status="message"/>
</template>
<script>
import New from './New.vue';
import Multiselect from '@suadelabs/vue3-multiselect';
import PageHeader from "@/Shared/Components/PageHeader.vue";
import flatPickr from "vue-flatpickr-component";
export default {
    components : { PageHeader, Multiselect, flatPickr, New },
    data() {
        return { 
            currentUrl: window.location.origin,
            title: "Buildings Management",
            items: [{text: "Lists",href: "/",},{text: "Building",active: true,},],
            lists : [],
            meta: {},
            links: {},
            keyword: ''
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
            axios.get(this.currentUrl + '/buildings', {
                params: {
                    keyword: this.keyword,
                    counts: ((window.innerHeight-350)/56),
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
        }
    }
}
</script>
