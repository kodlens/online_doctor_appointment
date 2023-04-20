<template>
    <div>
        <carousel-images></carousel-images>
        <div class="w-container">
            <div class="columns">
                <div class="column">
                    <div class="welcome-title">
                        Book your Appointment Online
                    </div>

                    <div class="welcome-subtitle">
                        Convenient, Fast, and Reliable
                    </div>
                    <div class="welcome-content">
                        Lorem ipsum dolor sit, amet consectetur adipisicing elit. Laboriosam 
                        perspiciatis ipsa quidem laudantium voluptas nam et iure, numquam aliquid quasi quaerat est non ratione 
                        minima quod praesentium dolorum atque adipisci? Lorem ipsum, dolor sit amet consectetur adipisicing elit. 
                        Cupiditate, sint dignissimos! Eaque autem dicta in amet omnis nihil sunt. 
                        Veritatis dicta molestias tenetur incidunt hic ratione nobis, ipsam tempora ducimus?
                    </div>
                </div>
                <div class="column">
                    <div class="schedule-box" v-if="isLogin">
                        <div class="doctor-schedule">
                            <div class="doctor-schedule-header">
                                Doctor Schedule
                            </div>

                            <b-field label="Pick date">
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

                            <div class="buttons">
                                <b-button
                                    @click="applyAppointment"
                                    class="mt-5"
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
                                <b-button :class=btnClass
                                    label="Login"
                                    @click="submit"
                                    icon-left="login"></b-button>

                                <b-button tag="a" type="is-info is-outlined"
                                    label="Register here..."
                                    href="/register-page"
                                    icon-left="account"></b-button>
                            </div>
                            
                        </div>
                       
                    </div> <!--login box -->
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
            fields: {},
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

                console.log(res.data)

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
                schedule_id: this.schedule_id
             };

            axios.post('/apply-appointment', appointment).then(res=>{

            })
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
        background: rgb(233, 233, 233);
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