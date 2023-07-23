<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-4">
                    <div class="w-panel-card">
                        <div class="w-panel-heading">
                            <div class="mb-2" style="font-size: 20px; font-weight: bold;">CREATE NEW APPOINTMENT</div>
                        </div>

                        <div class="w-panel-body">
                            
                            <div class="doctor-schedule-header">
                                Patient Information
                            </div>
                            
                            <b-field
                                :type="this.errors.patients ? 'is-danger':''"
                                :message="this.errors.patients ? this.errors.patients[0] : ''"></b-field>
                            
                            <div v-for="(patient, pIndex) in fields.patients" :key="`patient${pIndex}`">
                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Patient Last Name" label-position="on-border">
                                            <b-input type="text" v-model="patient.lname" placeholder="Patient Last Name">
                                            </b-input>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="Patient First Name" label-position="on-border">
                                            <b-input type="text" v-model="patient.fname" placeholder="Patient First Name">
                                            </b-input>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Patient Middle Name" label-position="on-border">
                                            <b-input type="text" v-model="patient.mname" placeholder="Patient Middle Name">
                                            </b-input>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="Sex" label-position="on-border">
                                            <b-select v-model="patient.sex" placeholder="Sex">
                                                <option value="MALE">MALE</option>
                                                <option value="FEMALE">FEMALE</option>
                                            </b-select>
                                        </b-field>
                                    </div>

                                    <div class="column">
                                        <b-field label="Age" label-position="on-border">
                                            <b-numberinput type="text" v-model="patient.age" 
                                                placeholder="Age"
                                                :controls="false">
                                            </b-numberinput>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field class="mt-3" label="Illness Description/History (Optional)" label-position="on-border">
                                            <b-input type="textarea" placeholder="Illness Description/History (Optional)"
                                                v-model="patient.illness"></b-input>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Province" label-position="on-border" expanded>
                                            <b-select v-model="patient.province" @focus="loadProvince(pIndex)" expanded>
                                                <option v-for="(item, index) in patient.provinces" :key="index" :value="item.provCode">{{ item.provDesc }}</option>
                                            </b-select>
                                        </b-field>
                                    </div>

                                    <div class="column">
                                        <b-field label="City" label-position="on-border" expanded>
                                            <b-select v-model="patient.city" @focus="loadCities(pIndex, patient.province)" expanded>
                                                <option v-for="(item, index) in patient.cities" :key="index" :value="item.citymunCode">{{ item.citymunDesc }}</option>
                                            </b-select>
                                        </b-field>
                                    </div>
                                </div>
                                 
                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Barangay" label-position="on-border" expanded>
                                            <b-select v-model="patient.barangay" expanded @focus="loadBarangays(pIndex, patient.province, patient.city)">
                                                <option v-for="(item, index) in patient.barangays" :key="index" :value="item.brgyCode">{{ item.brgyDesc }}</option>
                                            </b-select>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="Street" label-position="on-border">
                                            <b-input v-model="patient.street"
                                                    placeholder="Street">
                                            </b-input>
                                        </b-field>
                                    </div>
                                </div>
                               
                                <div class="buttons">
                                    <b-button label="" icon-left="delete" type="is-danger"
                                        class="is-small"
                                        @click="removePatient(pIndex)"></b-button>
                                </div>
                                
                                <hr>
                            </div> <!--loop-->

                            <div class="buttons mt-5">
                                <b-button class="button is-small is-info" 
                                    @click="addPatient"
                                    :disabled="fields.patients.length >= max">Add</b-button>
                            </div>

                            <div class="doctor-schedule-header">
                                Doctor's Schedules
                            </div>

                            <b-field label="Pick date" label-position="on-border">
                                <b-datepicker v-model="fields.appointment_date"
                                    inline
                                    :min-date="minDate"
                                    :unselectable-dates="vacations"
                                    @input="loadOpenSchedules">
                                </b-datepicker>
                            </b-field>


                            <div class="schedule-item" v-for="(item, index) in schedules"
                                :key="index">
                                <div class="schedule-item-time">{{ item.time_from | formatTime }} - {{ item.time_end | formatTime }}</div>
                                <div class="schedule-item-radio">
                                    <b-radio v-model="fields.schedule_id"
                                        name="name"
                                        :native-value=item.schedule_id>
                                    </b-radio>
                                </div>
                            </div>

                            <div class="buttons">
                                <b-button
                                    @click="submit"
                                    :disabled="fields.schedule_id < 1"
                                    class="mt-3"
                                    label="Make An Appointment"
                                    icon-left="calendar"
                                    type="is-primary is-outlined"></b-button>
                            </div>
                            
                        </div> <!--panel body-->

                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->


    </div>
</template>

<script>

export default{

    

    data() {
        const today = new Date();

        return{
            fields: {
                patients: [],
                appointment_date: null,
                schedule_id: 0,
            },

            errors: {},

            vacations: [],
            

            minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),

            max: 0,
            doctorVacations: [],

            schedules: [],

            btnClass: {
                'is-primary': true,
                'is-outlined': true,
                'is-loading': false,
                'button': true
            },
        }

    },

    methods: {
     
        loadOpenSchedules(){
            this.fields.schedule_id = 0;
            //this.vacations = [];
            
            const appdate = this.fields.appointment_date.getFullYear() + '-' 
                + (this.fields.appointment_date.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (this.fields.appointment_date.getDate()).toString().padStart(2,'0')

                //yyyy-MM-dd
            

            const params = [
                `appdate=${appdate}`,
            ].join('&')


            axios.get(`/load-open-schedules?${params}`).then(res=>{
                this.schedules = res.data
            })
        },


        submit(){
            const appdate = this.fields.appointment_date.getFullYear() + '-' 
                + (this.fields.appointment_date.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (this.fields.appointment_date.getDate()).toString().padStart(2,'0')

                this.fields.formatted_appointment_date = appdate;

                axios.post('/walkin', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'Saved!',
                            message: 'Appointment successfully saved.',
                            type: 'is-success',
                            onConfirm: () =>{
                                window.location = '/appointments'
                            }
                        });
                    }

                    //clear variable after execution
                    this.fields.formatted_appointment_date = null

                }).catch(err=>{
                    this.errors = err.response.data.errors;

                    if(this.errors.patients){
                        this.errors.patients[0] = 'Please add patient.';
                    }

                    //clear variable after execution
                    this.fields.formatted_appointment_date = null
                })
        },

        addPatient(){
            this.fields.patients.push({
                lname: '',
                fname: '',
                mname: '',
                sex: '',
                age: 0,
                illness: '',
                province: '',
                city: '',
                barangay: '',
                street: '',
                provinces: [],
                cities: [],
                barangays: [],
                street: '',
            });

         
        },

        removePatient(index){
            this.$buefy.dialog.confirm({
                title: 'DELETE?',
                message: 'Are you sure you want to remove this?',
                onConfirm: ()=>{
                    
                }
            });
        },
        clearPatientForm(){
            //alert('fire')
            this.fields.patients = [];
            // this.fields.partients.forEach(element, ix => {
            //     this.fields.patients.splice(index, ix);
            // });
            
        },

        loadProvince: function(index){
            axios.get('/load-provinces').then(res=>{
                this.fields.patients[index].provinces = res.data;
            })
        },

        loadCities: function(index, prov){
            axios.get('/load-cities?prov=' + prov).then(res=>{
                this.fields.patients[index].cities = res.data;
            })
        },

        loadBarangays: function(index, prov, city){
            axios.get('/load-barangays?prov=' + prov + '&city_code='+ city).then(res=>{
                this.fields.patients[index].barangays = res.data;
            })
        },

        loadMaxPatient(){
            axios.get('/load-max-no').then(res=>{
                this.max = res.data.max
            })
        },
        
        loadDoctorVacations(){
            axios.get('/load-doctor-vacations').then(res=>{
                this.doctorVacations = res.data
            })
        },

        loadVacations(){

            const dDate = new Date();
            const appdate = dDate.getFullYear() + '-' 
                + (dDate.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (dDate.getDate()).toString().padStart(2,'0')

            const params = [
                `appdate=${appdate}`,
            ].join('&')

            axios.get(`/load-vacations?${params}`).then(res=>{
                //this.vacations = res.data
                res.data.forEach(element => {
                    const d = new Date(element.vacation_date)
                    this.vacations.push(d)
                });
                console.log(this.vacations);
                //tiwasonun and ma deact ang date..
            })
        },


    },

    mounted() {
        this.loadVacations()
        this.loadDoctorVacations()
        this.loadMaxPatient();
    },

}
</script>


    <style scoped>
    .text-container {
        max-width: 200px; /* Set the width of the container to limit the text length */
        overflow: hidden; /* Hide overflowing text */
        white-space: nowrap; /* Prevent text from wrapping to new lines */
        text-overflow: ellipsis; /* Show ellipsis (...) when text overflows */
    }

    .long-text {
        max-height: 3em; /* Set the maximum height of the text to limit the number of lines */
        margin: 0; /* Reset margin */
        padding: 0; /* Reset padding */
    }


    
    .schedule-item{
        display: flex;
        padding: 15px;
        margin: 5px;
        border: 1px solid lightgray;
        border-radius: 10px;
        transition: all 0.5s;
    }

    .schedule-item:hover{
        border: 1.5px solid rgb(58, 176, 223);
    }

    .schedule-item-radio{
        margin-left: auto;
    }

</style>
