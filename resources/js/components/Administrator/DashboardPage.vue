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

        }
    },
    methods: {

        loadTodayAppointments(){
            axios.get('/load-today-appointments').then(res=>{
                this.appointment = res.data;
            }).catch(err=>{
            
            })
        }
        
    }
}
</script>