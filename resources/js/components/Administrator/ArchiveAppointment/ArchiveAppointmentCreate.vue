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
                          
                            <b-field label="Pick date" label-position="on-border">
                                <b-datepicker v-model="start_date">
                                </b-datepicker>
                            </b-field>
                            <b-field label="Pick date" label-position="on-border">
                                <b-datepicker v-model="end_date">
                                </b-datepicker>
                            </b-field>
                            <div class="buttons">
                                <b-button
                                    @click="archive"
                                    class="mt-3"
                                    label="Archive Data"
                                    icon-left="archive-arrow-down-outline"
                                    type="is-primary is-outlined"></b-button>

                                <b-button
                                    @click="restoreArchived"
                                    class="mt-3"
                                    label="Restore Data"
                                    icon-left="archive-arrow-up-outline"
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
        return{
            fields: {},
            errors: {},

            propId: 0,
            
            start_date: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
            end_date: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0),

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
     
        archive(){
            const startDate = this.start_date.getFullYear() + '-' 
                + (this.start_date.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (this.start_date.getDate()).toString().padStart(2,'0')

            const endDate = this.end_date.getFullYear() + '-' 
                + (this.end_date.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (this.end_date.getDate()).toString().padStart(2,'0')

                //yyyy-MM-dd

             let appointment = {
                start_date: startDate,
                end_date: endDate
             };


             axios.post('/archive-appointments', appointment).then(res=>{
                if(res.data.status === 'archived'){
                    this.$buefy.dialog.alert({
                        title: 'Archived!',
                        message: 'Successfully archived.',
                        type: 'is-success',
                        onConfirm: ()=>{
                            window.location = '/archive-appointments'
                        }
                    });
                    this.fields = {};
                    this.errors = {};
                }

            }).catch(err=>{
                //console.log(err.response.data.errors);
            })
            
        },


        restoreArchived(){
            const startDate = this.start_date.getFullYear() + '-' 
                + (this.start_date.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (this.start_date.getDate()).toString().padStart(2,'0')

            const endDate = this.end_date.getFullYear() + '-' 
                + (this.end_date.getMonth() + 1).toString().padStart(2, "0") + '-' 
                + (this.end_date.getDate()).toString().padStart(2,'0')

                //yyyy-MM-dd

             let appointment = {
                start_date: startDate,
                end_date: endDate
             };


             axios.post('/archive-appointments', appointment).then(res=>{
                if(res.data.status === 'restored'){
                    this.$buefy.dialog.alert({
                        title: 'Archived!',
                        message: 'Successfully restored.',
                        type: 'is-success',
                        onConfirm: ()=>{
                            window.location = '/archive-appointments'
                        }
                    });
                    this.fields = {};
                    this.errors = {};
                }

            }).catch(err=>{
                //console.log(err.response.data.errors);
            })
        },


       


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
