<template>
    <div>

        <div class="columns is-centered">
            <div class="column is-4">
                <div class="panel">
                    <div class="panel-heading">
                        Reschedule
                    </div>

                    <div class="panel-body">  

                        <b-field label="Pick date" label-position="on-border">
                            <b-datepicker v-model="fields.appointment_date"
                                :unselectable-dates="vacations"
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
                                @click="rescheduleAppointment"
                                :disabled="schedule_id < 1"
                                class="mt-3"
                                label="Reschedule"
                                icon-left="calendar"
                                type="is-primary is-outlined"></b-button>
                        </div>

                    </div> <!-- p-body-->
                </div> <!--panel-->
            </div> <!--col-->
        </div><!--cols-->
    </div><!--root div-->


</template>

<script>
export default {
    props: {
        propData: {
            type: Object,
            default: {}
        }
    },
    data(){
        return{

            data: [],
            appointment_date: null,
            vacations: [],
            max: 0,

            schedules: [],
            schedule_id: 0,


            fields: {},

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

            //this.schedule_id = 0;
            //this.vacations = [];

            const appdate = this.$formatDate(this.fields.appointment_date);
            //yyyy-MM-dd

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
            axios.get(`/load-open-schedules?${params}`).then(res=>{
                this.schedules = res.data
                this.schedule_id = this.data.schedule_id
            })
        },

        rescheduleAppointment(){

             const appdate = this.$formatDate(this.fields.appointment_date);
            //yyyy-MM-dd

             let appointment = {
                appointment_date: appdate,
                schedule_id: this.schedule_id,
             };

            axios.put('/my-appointment-reschedule/' + this.data.appointment_id, appointment).then(res=>{
                if(res.data.status === 'saved'){
                    this.$buefy.dialog.alert({
                        title: 'Saved!',
                        message: 'Reservation successfully saved.',
                        type: 'is-success'
                    });
                    
                    this.fields = {
                        patients: []
                    };

                    this.errors = {};
                }

            }).catch(err=>{
                //console.log(err.response.data.errors);

                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                    if(this.errors.patients){
                        this.errors.patients[0] = 'Please add patient.';
                    }

                    if(this.errors.max){

                        //save the new schedule found
                        let msg = this.errors.max[0] + ' Do you want to move schedule to <b>' + this.errors.max[2] + ', ' 
                            + this.$formatTime(this.errors.max[1].time_from) + ' - ' + this.$formatTime(this.errors.max[1].time_end) + '?</b><br>';
                        
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
        
        loadMaxPatient(){
            axios.get('/load-max-no').then(res=>{
                this.max = res.data.max
            })
        },

        getData(){
            this.data = this.propData;

            //console.log(data.appointment_date);
            //appointment_date
            this.fields.appointment_date = new Date(this.data.appointment_date);
            
            this.loadOpenSchedules()
        }

    },

    mounted(){

        this.getData();

       
        this.loadMaxPatient();
    }



}
</script>



<style scoped>
    .panel{
        margin: 30px;
    }

    .panel-body{
        padding: 15px;
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