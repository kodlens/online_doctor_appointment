<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-6">

                    <div class="w-panel-card">
                        <div class="w-panel-heading">
                            <div class="mb-2" style="font-size: 20px; font-weight: bold;">CREATE NEW VACATION</div>
                        </div>
                        
                        <div class="w-panel-body">

                            <div v-if="propId > 0">
                                <b-field label="Pick date" label-position="on-border">
                                    <b-datepicker v-model="data.vacation_date">
                                    </b-datepicker>
                                </b-field>
                            </div>
                            <div v-else>
                                <div v-for="(i,ix) in vacations" :key="ix" class="m-2">
                                    <b-field label="Pick date" label-position="on-border">
                                        <b-datepicker v-model="i.vacation_date">
                                        </b-datepicker>
                                    </b-field>
                                </div>
                                <div class="buttons">
                                    <b-button type="is-info" class="is-small mx-2"
                                        icon-left="plus"
                                        @click="newVacationDate"></b-button>
                                </div>
                            </div>
                            
                      
                            
                            <div class="buttons">
                                <b-button
                                    @click="submit"
                                    class="mt-3"
                                    label="Save Vacation"
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
    props: {
        propId: {
            type: Number,
            default: 0
        },
        propData: {
            type: Object,
            default: '',
        },
    },
    

    data() {
        return{

            data: {
                vacation_date: new Date()
            },
            
            fields: {
                vacations: []
            },
            errors: {},

            appointment_date: new Date(),


            vacations: [],
            btnClass: {
                'is-primary': true,
                'is-outlined': true,
                'is-loading': false,
                'button': true
            },

            appointment: {},
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
                if(this.propId > 0){
                    this.schedule_id = this.appointment.schedule_id;
                }
            })
        },


        submit(){

             this.vacations.forEach(el => {
                this.fields.vacations.push({
                    vacation_date: this.$formatDate(el.vacation_date)
                });
             });

            
            if(this.propId > 0){
                //logic

                this.data.vacation_date = this.$formatDate(this.data.vacation_date);

                axios.put('/vacations/' + this.propId, this.data).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'Saved!',
                            message: 'Reservation successfully saved.',
                            type: 'is-success',
                            onConfirm: ()=>{
                                window.location = '/vacations'
                            }
                        });
                    }

                }).catch(err=>{
                    //console.log(err.response.data.errors);

                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;

                        if(this.errors.max){
                            this.$buefy.dialog.alert({
                                title: 'Limit!',
                                message: this.errors.max[0],
                                type: 'is-danger'
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
            }else{
                axios.post('/vacations', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'Saved!',
                            message: 'Reservation successfully saved.',
                            type: 'is-success',
                            onConfirm: ()=>{
                                window.location = '/vacations'
                            }
                        });
                        this.fields = [];
                        this.errors = {};
                    }
                }).catch(err=>{
                    //console.log(err.response.data.errors);
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                        if(this.errors.vacations){
                            this.$buefy.dialog.alert({
                                title: 'No date!',
                                message: this.errors.vacations[0],
                                type: 'is-danger'
                            });
                        }
                    }
                })
            }
        },


        getData(){
            if(this.propId > 0){
                const nData = this.propData
                this.data.vacation_date = new Date(nData.vacation_date)
                //this.data.vacation_date = new Date();
                //console.log(this.data);
            }
        },

        newVacationDate(){
            this.vacations.push({
                vacation_id: 0,
                vacation_date: new Date()
            });
        }
    },

    mounted() {
        this.getData();
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
