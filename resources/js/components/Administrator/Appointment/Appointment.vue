<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-10-desktop is-8-tablet">
                    <div class="w-panel-card">
                        <div class="w-panel-heading">
                            <div class="mb-2" style="font-size: 20px; font-weight: bold;">LIST OF APPOINTMENTS</div>
                        </div>

                        <div class="w-panel-body">

                            <b-tabs v-model="activeTab">
                                <b-tab-item label="Daily Appointment">
                                    <daily-appointment></daily-appointment>
                                </b-tab-item>

                                <b-tab-item label="All Appointment">
                                    <all-appointment></all-appointment>
                                </b-tab-item>

                            
                            </b-tabs>


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
            activeTab: 0,
            
        }

    },

    methods: {
        
    },

    mounted() {
       
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
