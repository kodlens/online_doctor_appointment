<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-4">
                    
                    <div class="box">
                        <div class="box-heading">
                            <div class="has-text-weight-bold">
                                ACTIVATE ACCOUNT
                            </div>
                        </div>
                        <div class="box-body">
                            <p>To activate account, click <i>ASK FOR OTP</i> button. An OTP will be sent to your mobile no and kindly provide OTP in the field.</p>
                            <b-field label="OTP"
                                :type="this.errors.otp ? 'is-danger':''"
                                :message="this.errors.otp ? this.errors.otp[0] : ''">
                                <b-input type="text" placeholder="OTP" v-model="fields.otp" icon="cellphone"></b-input>
                            </b-field>
                            <div class="buttons">
                                <button :class="btnOTP" @click="askForOTP">ASK FOR OTP</button>
                                <button class="button is-primary" @click="activateAccount">ACTIVATE</button>
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
            fields: {
                otp: '',
            },

            errors: {},

            btnOTP: {
                'button': true,
                'is-outlined': true,
                'is-loading': false
            }
        }
    },
    methods: {
        askForOTP(){
            this.btnOTP['is-loading'] = true;

            axios.post('/ask-for-otp').then(res=>{
                if(res.data.status === 'otp'){
                    this.$buefy.dialog.alert({
                        title: 'OTP SENT.',
                        type: 'is-success',
                        message: 'OTP successfully sent to your mobile number.',
                        confirmText: 'OK',
                        onConfirm :() => {
                            this.errors = {}
                            this.fields.otp = '';
                        }
                    });
                }
                this.btnOTP['is-loading'] = false; 
            }).catch(err=>{
                this.btnOTP['is-loading'] = false;

                if(err.response.data.status === 'sms_error'){
                    this.$buefy.dialog.alert({
                        title: 'ERROR!',
                        type: 'is-danger',
                        message: 'Error Sending OTP. Please try again.',
                        confirmText: 'OK',
                    });
                }
                
            })
        },

        activateAccount(){

            axios.post('/verify-otp-activate-account', this.fields).then(res=>{
                if(res.data.status === 'activated'){
                    this.$buefy.dialog.alert({
                        title: 'ACTIVATED.',
                        type: 'is-success',
                        message: 'Account successfully activated.',
                        confirmText: 'OK',
                        onConfirm :() => {
                            window.location = '/'
                        }
                    });
                }
            }).catch(err=>{
                console.log(err.response)
                if(err.response.status === 422){
                    if(err.response.data.status === 'denied'){
                        this.$buefy.dialog.alert({
                            title: 'Invalid OTP.',
                            type: 'is-danger',
                            message: 'OTP denied.',
                            confirmText: 'OK',
                           
                        });
                    }
                }
            })
        }
    }
}
</script>

<style>

</style>