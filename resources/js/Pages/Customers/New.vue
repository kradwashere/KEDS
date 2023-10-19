<template>
    <b-modal v-model="showModal" hide-footer body-class="p-0" style="--vz-modal-width: 600px;" header-class="p-3 bg-light" id="myModal"
        class="v-modal-custom" :title="(editable) ? 'Edit Customer' : 'New Customer'" modal-class="zoomIn" content-class="border-0 overflow-hidden" centered>
        <div class="modal-body">
            <form class="customform">
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Fullname: <span v-if="form.errors" v-text="form.errors.name" class="haveerror"></span></label>
                            <input type="text" class="form-control" v-model="customer.name" style="text-transform: capitalize;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Mobile: <span v-if="form.errors" v-text="form.errors.shortcut" class="haveerror"></span></label>
                            <input type="text" class="form-control" v-model="customer.mobile" style="text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Birthday: <span v-if="form.errors" v-text="form.errors.address" class="haveerror"></span></label>
                            <input type="date" class="form-control" v-model="customer.birthday">
                        </div>
                    </div>

                     <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Gender: <span v-if="form.errors" v-text="form.errors.gender" class="haveerror"></span></label>
                              <Multiselect v-model="customer.gender" :options="genders" :allow-empty="false"
                            placeholder="Select Gender" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Nationality: <span v-if="form.errors" v-text="form.errors.shortcut" class="haveerror"></span></label>
                             <Multiselect v-model="customer.nationality" :options="nationalities" :allow-empty="false"
                            placeholder="Select Nationality" />
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label>Identification: <span v-if="form.errors" v-text="form.errors.identification" class="haveerror"></span></label>
                            <input type="text" class="form-control" v-model="customer.identification" style="text-transform: capitalize;">
                        </div>
                    </div> -->
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Address: <span v-if="form.errors" v-text="form.errors.address" class="haveerror"></span></label>
                            <input type="text" class="form-control" v-model="customer.address" style="text-transform: capitalize;">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group mb-2">
                            <label class="form-label">Identification<span class="text-danger">*</span><span v-if="form.errors" v-text="form.errors.indentification" class="haveerror"></span></label>
                            <input class="form-control" type="file" id="formFileMultiple" multiple @input="customer.identification = $event.target.files">
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
import Multiselect from '@suadelabs/vue3-multiselect';
export default {
    components : { Multiselect },
    data(){
        return {
            form : {},
            currentUrl: window.location.origin,
            customer: {
                id: '',
                name: '',
                gender: '',
                birthday: '',
                mobile: '',
                nationality: '',
                identification: '',
                address: ''
            },
            genders: [
                "Female", "Male"
            ],
            nationalities: [
                "Afghan",
                "Albanian",
                "Algerian",
                "American",
                "Andorran",
                "Angolan",
                "Antiguans",
                "Argentinean",
                "Armenian",
                "Australian",
                "Austrian",
                "Azerbaijani",
                "Bahamian",
                "Bahraini",
                "Bangladeshi",
                "Barbadian",
                "Barbudans",
                "Batswana",
                "Belarusian",
                "Belgian",
                "Belizean",
                "Beninese",
                "Bhutanese",
                "Bolivian",
                "Bosnian",
                "Brazilian",
                "British",
                "Bruneian",
                "Bulgarian",
                "Burkinabe",
                "Burmese",
                "Burundian",
                "Cambodian",
                "Cameroonian",
                "Canadian",
                "Cape Verdean",
                "Central African",
                "Chadian",
                "Chilean",
                "Chinese",
                "Colombian",
                "Comoran",
                "Congolese",
                "Costa Rican",
                "Croatian",
                "Cuban",
                "Cypriot",
                "Czech",
                "Danish",
                "Djibouti",
                "Dominican",
                "Dutch",
                "East Timorese",
                "Ecuadorean",
                "Egyptian",
                "Emirian",
                "Equatorial Guinean",
                "Eritrean",
                "Estonian",
                "Ethiopian",
                "Fijian",
                "Filipino",
                "Finnish",
                "French",
                "Gabonese",
                "Gambian",
                "Georgian",
                "German",
                "Ghanaian",
                "Greek",
                "Grenadian",
                "Guatemalan",
                "Guinea-Bissauan",
                "Guinean",
                "Guyanese",
                "Haitian",
                "Herzegovinian",
                "Honduran",
                "Hungarian",
                "I-Kiribati",
                "Icelander",
                "Indian",
                "Indonesian",
                "Iranian",
                "Iraqi",
                "Irish",
                "Israeli",
                "Italian",
                "Ivorian",
                "Jamaican",
                "Japanese",
                "Jordanian",
                "Kazakhstani",
                "Kenyan",
                "Kittian and Nevisian",
                "Kuwaiti",
                "Kyrgyz",
                "Laotian",
                "Latvian",
                "Lebanese",
                "Liberian",
                "Libyan",
                "Liechtensteiner",
                "Lithuanian",
                "Luxembourger",
                "Macedonian",
                "Malagasy",
                "Malawian",
                "Malaysian",
                "Maldivan",
                "Malian",
                "Maltese",
                "Marshallese",
                "Mauritanian",
                "Mauritian",
                "Mexican",
                "Micronesian",
                "Moldovan",
                "Monacan",
                "Mongolian",
                "Moroccan",
                "Mosotho",
                "Motswana",
                "Mozambican",
                "Namibian",
                "Nauruan",
                "Nepalese",
                "New Zealander",
                "Nicaraguan",
                "Nigerian",
                "Nigerien",
                "North Korean",
                "Northern Irish",
                "Norwegian",
                "Omani",
                "Pakistani",
                "Palauan",
                "Panamanian",
                "Papua New Guinean",
                "Paraguayan",
                "Peruvian",
                "Polish",
                "Portuguese",
                "Qatari",
                "Romanian",
                "Russian",
                "Rwandan",
                "Saint Lucian",
                "Salvadoran",
                "Samoan",
                "San Marinese",
                "Sao Tomean",
                "Saudi",
                "Scottish",
                "Senegalese",
                "Serbian",
                "Seychellois",
                "Sierra Leonean",
                "Singaporean",
                "Slovakian",
                "Slovenian",
                "Solomon Islander",
                "Somali",
                "South African",
                "South Korean",
                "Spanish",
                "Sri Lankan",
                "Sudanese",
                "Surinamer",
                "Swazi",
                "Swedish",
                "Swiss",
                "Syrian",
                "Taiwanese",
                "Tajik",
                "Tanzanian",
                "Thai",
                "Togolese",
                "Tongan",
                "Trinidadian or Tobagonian",
                "Tunisian",
                "Turkish",
                "Tuvaluan",
                "Ugandan",
                "Ukrainian",
                "Uruguayan",
                "Uzbekistani",
                "Venezuelan",
                "Vietnamese",
                "Welsh",
                "Yemenite",
                "Zambian",
                "Zimbabwean"
            ],
            lists: [],
            count : 0,
            showModal : false,
            editable: false,
        }
    },

    methods : {
        show(){
            this.customer = {};
            this.editable = false;
            this.showModal = true;
        },
        create() {
            this.form = this.$inertia.form({
                id: this.customer.id,
                name: this.customer.name,
                birthday: this.customer.birthday,
                mobile: this.customer.mobile,
                nationality: this.customer.nationality,
                identification: this.customer.identification,
                gender: this.customer.gender,
                address: this.customer.address,
                editable: this.editable
            })

            if(!this.editable){
                this.form.post('/customers',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            }else{
                this.form.put('/buildings/update',{
                    preserveScroll: true,
                    onSuccess: (response) => {
                        this.hide();
                    }
                });
            }
        },
        add() {
            this.count = this.count + 1;
            this.lists.push({floor: this.count})
        },
        del(index) {
            if(this.lists.length > 1){
                this.lists.splice(index, 1);
            }
        },
        edit(data){
            this.customer = data;
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