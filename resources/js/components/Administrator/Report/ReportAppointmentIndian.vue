<template>
    <div>
 
        <div class="columns is-centered">
            <div class="column is-8-desktop is-12-tablet">
                <div class="w-panel-card nprint">
                    <div class="w-panel-heading">
                        <div class="mb-2" style="font-size: 20px; font-weight: bold;">REPORT FILTER</div>
                    </div>


                    <div class="w-panel-body">
                        <div class="columns">
                            <div class="column">
                                <b-field label="Date Filter" label-position="on-border">
                                    <b-datepicker v-model="search.start_date" placeholder="Start date"></b-datepicker>
                                    <!-- <b-datepicker v-model="search.end_date" placeholder="End date"></b-datepicker> -->
                                    <p class="control">
                                        <b-button type="is-primary" icon-right="magnify" @click="loadAsyncData"/>
                                    </p>
                                </b-field>
                            </div>
                        </div> <!-- cols -->
                        
                        <div class="buttons">
                            <b-button label="Print" icon-left="printer"
                                type="is-info" class="is-outlined"
                                @click="printWindow"></b-button>

                            <b-button label="Back" icon-left="arrow-left"
                                type="is-info" class="is-outlined"
                                @click="goBackToAppointment"></b-button>
                        </div>
                        <hr>
                    </div> <!--panel body-->
                </div>

                <div style="font-weight: bold; font-size: 1em;">DATE FILTER: {{ new Date(search.start_date).toLocaleDateString() }} 
                    - 
                    CURRENT DATE ({{ new Date().toLocaleDateString() }})</div>
                
                <div style="font-weight: bold; font-size: 1em;">ACCOUNT MISSES APPOINTMENT 3 TIMES AND ABOVE
                </div>

                <table class="report-table">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Count</th>
                        <!-- <th>Status</th> -->
                    </tr>
                    <tr v-for="(i, ix) in data" :key="ix">
                        <td>{{ i.user_id }}</td>
                        <td>{{ i.lname }}, {{ i.fname }} {{ i.mname }}</td>
                        <td>
                            {{ i.count_app }}
                        </td>
                        <!-- <td>
                            <span class="status approved" v-if="i.is_served">SERVED</span>
                            <span class="status" v-else>UNSERVED</span>
                        </td> -->
                    </tr>

                </table>
                <div>No. of account misses their appointment: {{ countServed }} </div>
                <!-- <div>No. of Appointment Unserved: {{ countUnserved }} </div> -->

            </div><!--col -->
        </div><!-- cols -->

    </div>
</template>

<script>

export default{
    data() {
        return{


            global_id : 0,

            modalResetPassword: false,


            search: {
                lname: '',
                start_date: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
                //end_date: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0)
            },

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

            data: [],
        }

    },

    methods: {
        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `start=${this.$formatDate(this.search.start_date)}`,
                //`end=${this.$formatDate(this.search.end_date)}`,
            ].join('&')

            this.loading = true

            axios.get(`/get-report-appointment-indian?${params}`).then(res=>{
                this.data = res.data
            }).catch(err=>{
            })
        },

        printWindow(){
            window.print();
        },

        goBackToAppointment(){
            window.location = '/appointments'
        }
    },

    computed: {
        countServed(){
            let count = 0;
            this.data.forEach(el=>{
                count+=1;
            });
            return count;
        },

 

    },

    mounted() {
        this.loadAsyncData();
    }
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

</style>
