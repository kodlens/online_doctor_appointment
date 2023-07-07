<template>
    <div class="login-wrapper">
        <div class="login">
            <form @submit.prevent="submit">
                <div class="panel is-primary">
                    <div class="panel-heading">
                        Request OTP
                    </div>

                    <div class="panel-body">

                        <!-- <div class="img-container">
                            <img class="img" src="/img/qrlogo.png" />
                        </div> -->
                        <b-field label="Username" label-position="on-border">
                            <b-input type="text" v-model="fields.username" placeholder="Username" required />
                        </b-field>

                        <b-field label="Mobile No." label-position="on-border">
                            <b-input type="text" v-model="fields.contact_no"  placeholder="Mobile No." required/>
                        </b-field>

                        <b-notification type="is-danger" 
                            :auto-close="false"
                            v-if="errors.otp">

                            <span v-if="errors.otp.unknown">{{ errors.otp.unknown[0] }}</span>
                            <span v-if="errors.otp.sms">{{ errors.otp.sms[0] }}</span>
                           
                        </b-notification>

                        <div class="buttons">
                            <button  :class="btnClass">SEND CODE</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
    </div>
</template>

<script>

export default {
    data(){
        return {
            fields: {
                username: '',
                contact_no: '',
            },

            notif: {
                status: false,
                msg: ''
            },

            btnClass: {
                'is-primary': true,
                'is-loading': false,
                'button': true
            },

            errors: {},

        }
    },

    methods: {
        submit: function(){
            this.btnClass['is-loading'] = true;

            axios.post('/request-otp', this.fields).then(res=>{
                this.btnClass['is-loading'] = false;

                if(res.data.status === 'otp_success'){
                    this.$buefy.dialog.alert({
                        title: 'Sent!',
                        type: 'is-success',
                        message: 'OTP successfully sent.',
                        onConfirm: ()=>{
                            window.location = '/otp-entry/' + this.fields.username
                        }
                    });
                }
            }).catch(err=>{
                this.btnClass['is-loading'] = false;

                console.log(err.response.data)
                if(err.response.status === 422){
                    //console.log('test');
                    this.errors = err.response.data.errors;
                }
            })
        }
    }
}
</script>


<style scoped>
    .login-wrapper{
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .login{
        width: 500px;
    }

    .panel-body{
        margin: 15px;
    }

    .img-container{
        display: flex;
        justify-content: center;
    }

    .img{
        height: 200px;
    }


</style>
