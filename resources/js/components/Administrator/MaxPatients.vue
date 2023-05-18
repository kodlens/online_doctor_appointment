<template>
    <div>
        <div class="section">


            <div class="columns is-centered">
                <div class="column is-4">
                    <div class="w-panel-card">
                        <div class="w-panel-heading">
                            <div class="mb-2" style="font-size: 20px; font-weight: bold;">MAX PATIENT SETTING</div>
                        </div>

                        <div class="w-panel-body">

                            <b-field label="Max No." expanded>
                                <b-numberinput v-model="fields.max"
                                    expanded
                                    placeholder="Max Patient No."
                                    controls-position="compact"
                                    controls-alignment="right"></b-numberinput>
                            </b-field>

                            <div class="buttons is-right">
                                <b-button label="Save Setting" 
                                    @click="submit"
                                    type="is-primary" class=""></b-button>
                            </div>
                        </div> <!--panel body-->
                    </div>
                </div><!--col -->
            </div><!-- cols -->


        </div>
    </div>
</template>

<script>

export default{
    props: {

        propMax:{
            type: Number,
            default: 0
        }
    },
   
    data(){
        return {
            fields: {
                max: 0,
            },
            errors: {},
        }
    },

    methods: {
        initData(){
            this.fields.max = Number(this.propMax)
        },


        submit(){
            axios.post('/update-max-patient', this.fields).then(res=>{
                if(res.data.status === 'update'){
                    this.$buefy.dialog.alert({
                        title: 'Updated!',
                        message: 'Setting successfully applied.',
                        type: 'is-success',
                        onConfirm: ()=>{
                            this.loadData()
                        }
                    });
                }
            })
        },

        loadData(){
            axios.get('/get-max-patients').then(res=>{
                this.fields.max = res.data.max
            })
        }

    },

    mounted(){
        this.initData()
    }
}
</script>