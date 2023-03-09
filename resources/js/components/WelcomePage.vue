<template>
    <div>
        <div class="w-container">
            <div class="columns">
                <div class="column">
                    <div class="welcome-title">
                        DOCTOR APPOINTMENT SYSTEM
                    </div>

                    <div class="welcome-subtitle">
                        Make an appointment in just some clicks.
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
                        </div>
                    </div>

                    <div class="login-box" v-else>
                        <div class="login-header">
                            <div class="login-header-text">
                                Login here...
                            </div>
                        </div>
                        <div>
                            <b-field label="Username">
                                <b-input type="text" 
                                    v-model="fields.username"
                                    required
                                    placeholder="Username"></b-input>
                            </b-field>
                            <b-field label="Password">
                                <b-input type="password"
                                    password-reveal 
                                    v-model="fields.password"
                                    required
                                    placeholder="Password"></b-input>
                            </b-field>

                            <div class="buttons">
                                <b-button type="is-primary is-outlined"
                                    label="Login"
                                    icon-left="login"></b-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {

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


            schedules: [],
            schedule_id: 0,
        }
    },

    methods: {
        loadOpenSchedules(){
            axios.get('/load-open-schedules').then(res=>{
                this.schedules = res.data
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