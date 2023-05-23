<template>
    <div>

        <div class="section">
            <div class="columns is-centered">
                <div class="column is-4">
                    <div class="panel is-primary">
                        <div class="panel-heading">
                            TODAY'S APPOINTMENT
                        </div>
                        <div class="panel-body">
                            <div class="today-appointment">
                                {{  this.appointment }}
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
        return{
            appointment: 0
        }
    },
    methods: {

        loadTodayAppointments(){
            axios.get('/load-today-appointments').then(res=>{
                this.appointment = res.data;
            }).catch(err=>{
            
            })
        }
        
    },

    mounted() {
        this.loadTodayAppointments()
    }
}
</script>

<style scoped>
.today-appointment{
    font-weight: bold;
    font-size: 3em;
    text-align: center;
    padding: 20px;
}
</style>