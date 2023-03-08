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
                    <div class="debug">
                        <div class="doctor-schedule">
                            <div class="doctor-schedule-header">
                                Schedule
                            </div>

                            <div class="schedule-item" v-for="(item, index) in schedules"
                                :key="index">
                                {{ item.time_from | formatTime }} - {{ item.time_end | formatTime }}

                                <div>
                                    <b-radio v-model="schedule_id"
                                        name="name"
                                        :native-value=item.schedule_id>
                                    </b-radio>
                                </div>
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
    data(){
        return {
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

    .doctor-schedule{
        max-width: 500px;
        border: 1px solid blue;
        margin: 15px auto;
    }

    .doctor-schedule-header{
        font-weight: bold;
        font-size: 1.2em;
    }

    .schedule-item{
        margin: 5px;
        background: rgb(233, 233, 233);
    }

</style>