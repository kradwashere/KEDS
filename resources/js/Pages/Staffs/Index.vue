<template>
<Head title="Staffs" />
    <PageHeader :title="title" :items="items" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
            
            <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px)">
                
                <div class="p-3 bg-light rounded mb-4">
                    <b-row class="g-2">
                       
                        <b-col lg>
                            <div class="search-box">
                                <input type="text" id="searchTaskList" v-model="keyword" class="form-control search"
                                    placeholder="Search name">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </b-col>
                        <b-col lg="auto">
                            <!-- <b-button variant="primary" class="createTask" type="button" data-bs-toggle="modal"
                                data-bs-target="#createTask" @click="create">
                                <i class="ri-add-fill align-bottom"></i> Add Tasks
                            </b-button> -->
                            <b-button variant="primary"  class="createTask" @click="add"> <i
                                    class="ri-add-fill align-bottom"></i> New Staff
                            </b-button>
                        </b-col>
                    </b-row>
                </div>

                <div class="todo-content position-relative px-4 mx-n4" id="todo-content">
                    <div id="elmLoader" v-if="lists.length == 0">
                        <div class="spinner-border text-primary avatar-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="todo-task" id="todo-task" v-else>
                        <div class="table-responsive">
                            <table class="table align-middle position-relative table-nowrap">
                                <thead class="table-active">
                                    <tr>
                                        <th></th>
                                        <th scope="col">Name</th>
                                        <th scope="col" class="text-center">Email</th>
                                        <th scope="col" class="text-center">Mobile</th>
                                        <th scope="col" class="text-center">Role</th>
                                        <th scope="col" class="text-center">Status</th>
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
                                        <td class="text-center">{{ list.email}}</td>
                                        <td class="text-center">{{ list.mobile}}</td>
                                        <td class="text-center">{{ list.role}}</td>
                                        <td class="text-center">
                                            <span v-if="list.is_active" class='badge bg-success'>Active</span>
                                            <span v-else class='badge bg-danger'>Inactive</span>
                                        </td>
                                        <td class="text-end">
                                            <!-- <Link :href="'students/'+list.id">
                                            <b-button variant="soft-danger" size="sm" class="remove-list me-1"><i class="ri-eye-fill align-bottom"></i></b-button>
                                            </Link> -->
                                            <b-button @click="password(list)" variant="primary" size="sm" class="edit-list me-1"><i class="ri-lock-2-fill  align-bottom"></i> </b-button>
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
       <Password ref="password" @status="message"/>
</template>
<script>
import New from './New.vue';
import Password from './Password.vue';
import Multiselect from '@suadelabs/vue3-multiselect';
import PageHeader from "@/Shared/Components/PageHeader.vue";
import flatPickr from "vue-flatpickr-component";
export default {
    components : { PageHeader, Multiselect, flatPickr, New, Password},
    page: {
        title: "List of Staffs",
        meta: [{ name: "description" }],
    },
    data() {
        return { 
            currentUrl: window.location.origin,
            title: "Staff Management",
            items: [{text: "Lists",href: "/",},{text: "Staffs",active: true,},],
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
            axios.get(this.currentUrl + '/staffs', {
                params: {
                    keyword: this.keyword,
                    status: this.status,
                    search: 'lists'
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
        password(data){
            this.$refs.password.edit(data);
        },
        message(){
            this.fetch();
        }
    }
}
</script>
