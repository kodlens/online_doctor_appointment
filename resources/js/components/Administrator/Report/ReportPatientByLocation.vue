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
                                    <b-datepicker v-model="search.end_date" placeholder="End date"></b-datepicker>
                                </b-field>
                            </div>
                        </div> <!-- cols -->
                        

                        <div class="columns">
                            <div class="column">
                                <b-field label="Report By" label-position="on-border" expanded>
                                    <b-select v-model="search.byKey" @input="loadCity" expanded>
                                        <option value="province">Province</option>
                                        <option value="city">City</option>
                                        <option value="barangay">Barangay</option>
                                    </b-select>
                                </b-field>
                            </div>

                            
                        </div> <!-- cols -->

                        <div class="buttons">
                            <b-button type="is-primary" 
                                label="Search"
                                icon-right="magnify" @click="loadAsyncData"/>
                        </div>
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
                    {{ new Date(search.end_date).toLocaleDateString() }}
                </div>
                
                <!--if there is province -->
                <div v-if="search.province !== ''">
                    <div style="font-weight: bold; font-size: 1em;">
                        NO. OF APPOINTMENT IN PROVINCE 
                    </div>

                    <!-- if province only selected-->
                    <table class="report-table">
                        <tr>
                            <th>ID</th>
                            <th style="font-size: .8em;">Appointment Date</th>
                            <th>Account</th>
                            <th>Schedule</th>
                            <th>Status</th>
                        </tr>
                        <tr v-for="(i, ix) in data" :key="ix">
                            <td>{{ i.appointment_id }}</td>
                            <td>{{ i.appointment_date }}</td>
                            <td>
                                <span v-if="i.user.lname">
                                    {{ i.user.lname }}, {{ i.user.fname }}
                                </span>
                            </td>
                            <td style="width: 130px;">
                                <span v-if="i.schedule.time_from">
                                    {{ i.schedule.time_from | formatTime}} - {{ i.schedule.time_end | formatTime}}
                                </span>
                            </td>
                            <td>
                                <span class="status approved" v-if="i.is_served">SERVED</span>
                                <span v-else>UNSERVED</span>
                            </td>
                        </tr>
                    </table>

                    <div>No. of Appointment Served: {{ countServed }} </div>
                    <div>No. of Appointment Unserved: {{ countUnserved }} </div>
                </div>
                <!-- if there is province-->

                

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
                end_date: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0),
                byKey: '',
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
                `end=${this.$formatDate(this.search.end_date)}`,
                `bykey=${this.search.byKey}`,

            ].join('&')

            this.loading = true

            axios.get(`/get-report-patient-by-location?${params}`).then(res=>{
                this.data = res.data
            }).catch(err=>{
            
            })
            
        },

        printWindow(){
            window.print();
        },

        goBackToAppointment(){
            window.location = '/appointments'
        },



        loadProvince: function(){
            axios.get('/load-provinces').then(res=>{
                this.provinces = res.data;
            })
        },

        loadCity: function(){
            axios.get('/load-cities?prov=' + this.search.province).then(res=>{
                this.cities = res.data;
            })
        },

        loadBarangay: function(){
            axios.get('/load-barangays?prov=' + this.search.province + '&city_code='+this.search.city).then(res=>{
                this.barangays = res.data;
            })
        },

    },

    computed: {
        countServed(){
            let count = 0;
            this.data.forEach(el=>{
                if(el.is_served === 1){
                    count+=1;
                }
            });
            return count;
        },

        countUnserved(){
            let count = 0;
            this.data.forEach(el=>{
                if(el.is_served === 0){
                    count+=1;
                }
            });
            return count;
        },

        
    },

    mounted() {
        this.loadAsyncData();
        this.loadProvince()
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
