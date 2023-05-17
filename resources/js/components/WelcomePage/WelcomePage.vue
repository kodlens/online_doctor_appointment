<template>
    <div>
        <carousel-images></carousel-images>
        <div class="section">
            <div class="columns">
                <div class="column">
                    <div class="welcome-title">
                        Book your Appointment Online
                    </div>

                    <div class="welcome-subtitle">
                        Convenient, Fast, and Reliable
                    </div>
                    <div class="welcome-content">
                        Our clinic is a healthcare facility that is dedicated to providing high-quality medical care to our patients. 
                        We pride ourselves on delivering compassionate and personalized care that meets the unique needs of each patient. 
                        Our team of experienced healthcare professionals are committed to delivering excellent care in a comfortable and welcoming environment. 
                        We believe in patient-centered care and take the time to listen to our patients and understand their concerns. 
                        Our clinic is equipped with modern medical equipment and technology to ensure accurate diagnoses and effective treatment. 
                        We prioritize patient safety, confidentiality, and comfort, and we strive to create a positive healthcare experience for every patient. 
                        Thank you for considering our clinic for your healthcare needs.
                    </div>
                </div>
                <div class="column">
                    <div class="schedule-box" v-if="isLogin">
                        <div class="doctor-schedule">
                            <div class="doctor-schedule-header">
                                Patient Information
                            </div>
                            <div v-for="(i, ix) in fields.patients" :key="ix">
                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Patient Last Name" label-position="on-border">
                                            <b-input type="text" v-model="fields.patients.lname" placeholder="Patient Last Name">
                                            </b-input>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="Patient First Name" label-position="on-border">
                                            <b-input type="text" v-model="fields.patients.fname" placeholder="Patient First Name">
                                            </b-input>
                                        </b-field>
                                    </div>
                                </div>

                                <div class="columns">
                                    <div class="column">
                                        <b-field label="Patient Middle Name" label-position="on-border">
                                            <b-input type="text" v-model="fields.patients.lname" placeholder="Patient Middle Name">
                                            </b-input>
                                        </b-field>
                                    </div>
                                    <div class="column">
                                        <b-field label="Sex" label-position="on-border">
                                            <b-select v-model="fields.patients.sex" placeholder="Sex">
                                                <option value="MALE">MALE</option>
                                                <option value="FEMALE">FEMALE</option>
                                            </b-select>
                                        </b-field>
                                    </div>

                                    <div class="column">
                                        <b-field label="Age" label-position="on-border">
                                            <b-numerinput type="text" v-model="fields.patients.age" placeholder="Age">
                                            </b-numerinput>
                                        </b-field>
                                    </div>
                                </div>
                                
                            </div> <!--loop-->

                            <div class="buttons">
                                <b-button class="button is-small is-info" @click="addPatient">Add</b-button>
                            </div>
                            


                            <div class="doctor-schedule-header">
                                Doctor Schedule
                            </div>

                            <b-field label="Pick date" label-position="on-border">
                                <b-datepicker v-model="appointment_date"
                                    @input="loadOpenSchedules">
                                </b-datepicker>
                            </b-field>

                            <div class="schedule-item" v-for="(item, index) in schedules"
                                :key="index">
                                <div class="schedule-item-time">{{ item.time_from | formatTime }} - {{ item.time_end | formatTime }}</div>
                                <div class="schedule-item-radio">
                                    <b-radio v-model="schedule_id"
                                        name="name"
                                        :native-value=item.schedule_id>
                                    </b-radio>
                                </div>
                            </div>

                            <b-field class="mt-3" label="Illness Description/History (Optional)" label-position="on-border">
                                <b-input type="textarea" placeholder="Illness Description/History (Optional)"
                                    v-model="fields.illness_history"></b-input>
                            </b-field>

                            <div class="buttons">
                                <b-button
                                    @click="applyAppointment"
                                    :disabled="schedule_id < 1"
                                    class="mt-3"
                                    label="Apply for appointment"
                                    icon-left="calendar"
                                    type="is-primary is-outlined"></b-button>
                            </div>
                        </div>
                    </div>

                    <div class="login-box" v-else>
                        <div class="login-header">
                            <div class="login-header-text">
                                Login here
                            </div>
                            <p>
                                Or click <a href="/register-page">register here</a> to register an account.
                            </p>
                        </div>
                        
                        <div>
                            
                            <b-field label="Username"
                                :type="this.errors.username ? 'is-danger':''"
                                :message="this.errors.username ? this.errors.username[0] : ''">
                                <b-input type="text" 
                                    v-model="fields.username"
                                    required
                                    placeholder="Username"></b-input>
                            </b-field>
                            <b-field label="Password"
                                :type="this.errors.password ? 'is-danger':''"
                                :message="this.errors.password ? this.errors.password[0] : ''">
                                <b-input type="password"
                                    password-reveal 
                                    v-model="fields.password"
                                    required
                                    placeholder="Password"></b-input> 
                            </b-field>

                            <div class="buttons">
                                <b-button 
                                    :class="btnClass"
                                    label="Login"
                                    @click="submit"
                                    icon-left="login"></b-button>

                                <b-button tag="a" type="is-info is-outlined"
                                    label="Register here..."
                                    href="/register-page"
                                    icon-left="account"></b-button>
                            </div>

                            <p>
                                <a href="/forgot-password">Forgot Password</a>
                            </p>
                            
                        </div>
                       
                    </div> <!--login box -->
                </div>
            </div> <!--cols --->

            
        </div>

        <div class="section">
            <div class="columns">
                <div class="column has-text-centered">
                    <div style="font-weight: bold; font-size: 1.8em; margin: auto;
                        border-bottom: 2px solid blue; display: inline-block;">
                        SERVICE OFFERED
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="welcome-subtitle">
                        Diagnostic Services
                    </div>
                    <p>
                        Diagnostic services are medical tests and procedures that help doctors and healthcare providers diagnose health conditions and diseases.
                    </p>
                </div>

                <div class="column">
                    <div class="welcome-subtitle">
                        Preventive Care
                    </div>
                    <p>
                        This includes services aimed at preventing health conditions, such as screenings, lifestyle counseling, and immunizations.
                    </p>
                </div>

                <div class="column">
                    <div class="welcome-subtitle">
                        Pediatric Care
                    </div>
                    <p>
                        This includes care for children, such as well-child checkups, vaccinations, and treatment for common childhood illnesses.
                    </p>
                </div>

                <div class="column">
                    <div class="welcome-subtitle">
                        General Health Check-ups
                    </div>
                    <p>
                        Doctors in clinics provide general health check-ups to assess and maintain a patient's overall health. During a check-up, the doctor may take the patient's medical history, perform a physical examination, and order diagnostic tests such as blood work and urinalysis. 
                        These check-ups are typically recommended on an annual basis for adults.
                    </p>
                </div>
                        
            </div>

            

                
         </div>

    </div>
</template>

<script>
import CarouselImages from './CarouselImages.vue';

export default {
  components: { CarouselImages },

    props: {
        isLogin: {
            type: Boolean,
            default: false
        }
    },

    data(){
        return {
            fields: {
                patients: [],
            },
            errors: {},

            appointment_date: new Date(),


            schedules: [],
            schedule_id: 0,

            

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
            this.schedule_id = 0;
            const appdate = this.appointment_date.getFullYear() + '-' 
                + (this.appointment_date.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (this.appointment_date.getDate()).toString().padStart(2,'0')

                //yyyy-MM-dd

            const params = [
                `appdate=${appdate}`,
            ].join('&')

            axios.get(`/load-open-schedules?${params}`).then(res=>{
                this.schedules = res.data
            })
        },

        submit(){
            this.btnClass['is-loading'] = true;

            axios.post('/login', this.fields).then(res=>{
            this.btnClass['is-loading'] = false;
                if(res.data.role === 'ADMINISTRATOR'){
                    window.location = '/dashboard';
                }

                if(res.data.role === 'USER'){
                    window.location = '/';
                }
                
            }).catch(err=>{
                this.btnClass['is-loading'] = false;
                this.errors = err.response.data.errors;

              
            })
        },


        applyAppointment(){

             const appdate = this.appointment_date.getFullYear() + '-' 
                + (this.appointment_date.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (this.appointment_date.getDate()).toString().padStart(2,'0')

                //yyyy-MM-dd

             let appointment = {
                appointment_date: appdate,
                schedule_id: this.schedule_id,
                illness_history: this.fields.illness_history
             };

            axios.post('/apply-appointment', appointment).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'Saved!',
                        message: 'Reservation successfully saved.',
                        type: 'is-success'
                    });
                    this.fields = {};
                    this.errors = {};
                }

            }).catch(err=>{
                //console.log(err.response.data.errors);

                if(err.response.status === 422){
                    this.errors = err.response.data.errors;

                    if(this.errors.max){

                        //save the new schedule found
                        let msg = this.errors.max[0] + ' Do you want to move schedule to <b>' + this.errors.max[2] + ', ' 
                            + this.$formatTime(this.errors.max[1].time_from) + ' - ' + this.$formatTime(this.errors.max[1].time_end) + '.</b><br>';
                        
                        if(this.errors.max[3]){
                            msg  +=  '<br><span style="font-weight:bold;color:red;">Are you willing to accept the schedule? The date was change from the original.</span>';
                        }

                        this.$buefy.dialog.confirm({
                            title: 'Limit!',
                            message: msg ,
                            type: 'is-info',
                            confirmText: 'Proceed schedule',
                            onConfirm: () => {
                                this.appointment_date = new Date(this.errors.max[2]);
                                const newData = this.errors.max[1];
                                this.schedule_id = newData.schedule_id;

                                this.applyAppointment() //recursive
                            }
                        });
                    }

                    if(this.errors.exists){
                        this.$buefy.dialog.alert({
                            title: 'Exist!',
                            message: this.errors.exists[0],
                            type: 'is-danger'
                        });
                    }
                }
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
            });
        }
    },

    mounted(){
        this.loadOpenSchedules()
    }

}
</script>



<style scoped>
    .welcome-title{
        font-size: 3em;
        font-weight: bolder;
    }

    .welcome-subtitle{
        font-size: 1.5em;
        font-weight: bold;
    }

    .welcome-content{
        margin: 50px 0;
        font-size: 1.4em;
        text-align: justify;
    }


    .schedule-box{
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        max-width: 600px;
        margin: auto;
        padding: 25px;
    }

    .doctor-schedule{
        max-width: 500px;
        margin: 15px auto;
    }

    .doctor-schedule-header{
        font-weight: bold;
        font-size: 1.2em;
        margin: 15px 0;
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



    .login-box{
        box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
        max-width: 600px;
        margin: auto;
        padding: 25px;
    }

    .login-header{
        border-bottom: 1px solid blue;
        padding: 0 0 15px 0;
        margin: 0 0 10px 0;
    }
    .login-header-text{
        font-weight: bold; 
        font-size: 1.2em;
    }

</style>