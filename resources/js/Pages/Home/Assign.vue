<template>
    <b-modal v-model="showModal" hide-footer body-class="p-0" header-class="p-3" id="myModal"
        class="v-modal-custom" title="Choose Technician" content-class="border-0" no-close-on-backdrop centered>
        <div class="modal-body">
            <form>
                <div class="row mt-n4">
                   <b-row class="g-2">
                    <b-col lg="12" v-for="(data, index) of technicians" :key="index">
                      <div data-bs-toggle="collapse" data-bs-target="#paymentmethodCollapse.show" aria-expanded="false"
                        aria-controls="paymentmethodCollapse">
                        <div class="form-check card-radio">
                          <input v-model="technician" :id="'t'+index" name="paymentMethod" :value="data.id" type="radio" class="form-check-input" />
                          <label class="form-check-label" :for="'t'+index">
                            <span class="fs-16 text-muted me-2"><img :src="currentUrl+'/images/avatars/'+data.avatar" alt="" class="avatar-xs rounded-circle me-2"></span>
                            <span class="fs-14 float-end mt-1">{{ data.task}}</span>
                            <span class="fs-14 text-wrap">{{ data.name}}</span>
                          </label>
                        </div>
                      </div>
                    </b-col>
                  </b-row>
                    
                </div>
                <div class="text-end mt-3">
                    <b-button type="button" @click="update()" variant="primary">Assign</b-button>
                </div>
            </form>
        </div>
    </b-modal>
</template>
<script>
import Multiselect from '@suadelabs/vue3-multiselect';
export default {
    components : { Multiselect },
    props: ['staffs'],
    data(){
        return {
            currentUrl: window.location.origin,
            form : {},
            id: '',
            technician: '',
            showModal: false
        }
    },

     computed: {
        technicians: function () {
            return this.staffs.data.filter(x => x.role === 'Technician');
        },
    },

    methods : {
        show(id){
            this.id = id;
            this.showModal = true;
        },
        update(id, status) {
            this.form = this.$inertia.form({
                id: this.id,
                status_id: 1,
                technician_id: this.technician
            });

            this.form.put('/jobs/update', {
                preserveScroll: true,
                onSuccess: (response) => {
                    this.hide();
                    this.$emit('status', true);
                }
            });
        },
        hide(){
            this.showModal = false;
            this.technician = '';
        }
    }
}
</script>