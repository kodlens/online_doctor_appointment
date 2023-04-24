<template>
    <div>
        <div class="section">

            <div class="columns is-centered">
                <div class="column is-6">
                    <div class="box">
                        <div class="box-header">
                            <div class="box-header-text">
                                Register here...
                            </div>

                            <div class="box-header-right m-flex-left">
                                <b-button icon-left="home" tag="a"
                                    type="is-info is-outlined"
                                    href="/"></b-button>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <b-field label="Username" label-position="on-border"
                                        :type="this.errors.username ? 'is-danger':''"
                                        :message="this.errors.username ? this.errors.username[0] : ''">
                                    <b-input v-model="fields.username"
                                        placeholder="Username" required>
                                    </b-input>
                                </b-field>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <b-field label="Password" label-position="on-border"
                                            :type="this.errors.password ? 'is-danger':''"
                                            :message="this.errors.password ? this.errors.password[0] : ''">
                                    <b-input type="password" password-reveal v-model="fields.password"
                                                placeholder="Password" required>
                                    </b-input>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="Confirm Password" label-position="on-border"
                                            :type="this.errors.password_confirmation ? 'is-danger':''"
                                            :message="this.errors.password_confirmation ? this.errors.password_confirmation[0] : ''">
                                    <b-input type="password" v-model="fields.password_confirmation"
                                                placeholder="Confirm Password" required>
                                    </b-input>
                                </b-field>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <b-field label="Last Name" label-position="on-border"
                                            :type="this.errors.lname ? 'is-danger':''"
                                            :message="this.errors.lname ? this.errors.lname[0] : ''">
                                    <b-input v-model="fields.lname"
                                                placeholder="Last Name" required>
                                    </b-input>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="First Name" label-position="on-border"
                                            :type="this.errors.fname ? 'is-danger':''"
                                            :message="this.errors.fname ? this.errors.fname[0] : ''">
                                    <b-input v-model="fields.fname"
                                                placeholder="First Name" required>
                                    </b-input>
                                </b-field>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <b-field label="Middle Name" label-position="on-border"
                                            :type="this.errors.mname ? 'is-danger':''"
                                            :message="this.errors.mname ? this.errors.mname[0] : ''">
                                    <b-input v-model="fields.mname"
                                                placeholder="Middle Name">
                                    </b-input>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="Extension" label-position="on-border"
                                    :type="this.errors.extension ? 'is-danger':''"
                                    :message="this.errors.extension ? this.errors.extension[0] : ''"
                                >
                                    <b-input type="text" 
                                        v-model="fields.extension"
                                        placeholder="Extension">
                                    </b-input>
                                </b-field>
                            </div>
                        </div>
                        <div class="columns">
                            <div class="column">
                                <b-field label="Sex" label-position="on-border" expanded
                                    :type="this.errors.sex ? 'is-danger':''"
                                    :message="this.errors.sex ? this.errors.sex[0] : ''"
                                >
                                    <b-select v-model="fields.sex" expanded>
                                        <option value="MALE">MALE</option>
                                        <option value="FEMALE">FEMALE</option>
                                    </b-select>
                                </b-field>
                            </div>
                            <div class="column">
                                <b-field label="Contact No" label-position="on-border"
                                            :type="this.errors.contact_no ? 'is-danger':''"
                                            :message="this.errors.contact_no ? this.errors.contact_no[0] : ''">
                                    <b-input type="number" v-model="fields.contact_no"
                                                placeholder="Contact No" required>
                                    </b-input>
                                </b-field>
                            </div>
                        </div>
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
                        </div><!-- cols -->

                        <div class="buttons">
                            <b-button
                                label="Register"
                                @click="submit"
                                type="is-primary is-outlined"
                                icon-left="account">
                            </b-button>
                        </div>

                    </div> <!--box -->
                </div> <!--col-->
            </div> <!--cols --> 
            

        </div> <!--section-->
    </div><!--root div -->
</template>

<script>
export default {
    data(){
        return{
            fields: {},
            errors: {},

            provinces: [],
            cities: [],
            barangays: [],
            street: '',


        }
    },
    methods:{

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

        submit(){
            axios.post('/register-page', this.fields).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'Saved!',
                        message: 'Account successfully register. You can login using the account.',
                        type: 'is-success'
                    })
                }
            }).catch(err=>{
                if(err.response.status === 422){
                    this.errors = err.response.data.errors
                }
            })
        },

    },

    mounted(){
        this.loadProvince()
    }
}
</script>

<style>

    
</style>