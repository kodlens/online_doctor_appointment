<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-6-desktop is-8-tablet">
                    <div class="panel is-primary">
                        <div class="panel-heading is-size-6">My Profile</div>

                        <div class="p-4 mt-5">
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Last Name" label-position="on-border">
                                        <b-input type="text"
                                            v-model="fields.lname"
                                            placeholder="Last Name" required></b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="First Name" label-position="on-border">
                                        <b-input type="text"
                                            v-model="fields.fname"
                                            placeholder="First Name" required></b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Middle Name" label-position="on-border">
                                        <b-input type="text"
                                            v-model="fields.mname"
                                            placeholder="Middle Name" required></b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Extension" label-position="on-border">
                                        <b-input type="text"
                                            v-model="fields.extension"
                                            placeholder="Extension" required></b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Middle Name" label-position="on-border">
                                        <b-input type="text"
                                            v-model="fields.mname"
                                            placeholder="Middle Name" required></b-input>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Extension" label-position="on-border">
                                        <b-input type="text"
                                            v-model="fields.extension"
                                            placeholder="Extension" required></b-input>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Sex" label-position="on-border" expanded>
                                        <b-select v-model="fields.sex" expanded
                                            placeholder="Sex" required>
                                            <option value="MALE">MALE</option>
                                            <option value="FEMALE">FEMALE</option>
                                        </b-select>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Contact No." label-position="on-border">
                                        <b-input type="text"
                                            v-model="fields.contact_no"
                                            placeholder="Contact No." required></b-input>
                                    </b-field>
                                </div>
                            </div>
                            <hr>


                            <div class="columns">
                                <div class="column">
                                    <b-field label="Province" label-position="on-border" expanded
                                             :type="this.errors.province ? 'is-danger':''"
                                             :message="this.errors.province ? this.errors.province[0] : ''">
                                        <b-select v-model="fields.province" @input="loadCity" expanded>
                                            <option v-for="(item, index) in provinces" :key="index" :value="item.provCode">{{ item.provDesc }}</option>
                                        </b-select>
                                    </b-field>
                                </div>

                                <div class="column">
                                    <b-field label="City" label-position="on-border" expanded
                                             :type="this.errors.city ? 'is-danger':''"
                                             :message="this.errors.city ? this.errors.city[0] : ''">
                                        <b-select v-model="fields.city" @input="loadBarangay" expanded>
                                            <option v-for="(item, index) in cities" :key="index" :value="item.citymunCode">{{ item.citymunDesc }}</option>
                                        </b-select>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Barangay" label-position="on-border" expanded
                                             :type="this.errors.barangay ? 'is-danger':''"
                                             :message="this.errors.barangay ? this.errors.barangay[0] : ''">
                                        <b-select v-model="fields.barangay" expanded>
                                            <option v-for="(item, index) in barangays" :key="index" :value="item.brgyCode">{{ item.brgyDesc }}</option>
                                        </b-select>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Street" label-position="on-border">
                                        <b-input v-model="fields.street"
                                                 placeholder="Street">
                                        </b-input>
                                    </b-field>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </div> <!--section-->
    </div>
</template>

<script>

export default{
    data(){
        return {
            fields: {},
            errors: {},

            provinces: [],
            cities: [],
            barangays: [],
            street: '',
        }
    },

    methods: {
        loadProvince: function(){
            axios.get('/load-provinces').then(res=>{
                this.provinces = res.data;
            })
        },

        loadCity: function(){
            axios.get('/load-cities?prov=' + this.fields.province).then(res=>{
                this.cities = res.data;
            })
        },

        loadBarangay: function(){
            axios.get('/load-barangays?prov=' + this.fields.province + '&city_code='+this.fields.city).then(res=>{
                this.barangays = res.data;
            })
        },
    },

    mounted(){
        this.loadProvince();
    }
}
</script>