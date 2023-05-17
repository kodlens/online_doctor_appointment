<template>
    <div class="login-wrapper">
        <div class="login">
            <form @submit.prevent="submit">
                <div class="panel is-primary">
                    <div class="panel-heading">
                        Reset Password
                    </div>

                    <div class="panel-body">

                        <!-- <div class="img-container">
                            <img class="img" src="/img/qrlogo.png" />
                        </div> -->
          
                        <b-field label="OTP" label-position="on-border"
                            :type="this.errors.otp ? 'is-danger':''"
                            :message="this.errors.otp ? this.errors.otp[0] : ''">
                            <b-input type="text" v-model="fields.otp"  placeholder="OTP" />
                        </b-field>

                        <b-field label="Password" label-position="on-border"
                            :type="this.errors.password ? 'is-danger':''"
                            :message="this.errors.password ? this.errors.password[0] : ''">
                            <b-input type="password" password-reveal v-model="fields.password"  placeholder="Password" />
                        </b-field>

                        <b-field label="Retype Password" label-position="on-border"
                            :type="this.errors.password_confirmation ? 'is-danger':''"
                            :message="this.errors.password_confirmation ? this.errors.password_confirmation[0] : ''">
                            <b-input type="password" password-reveal v-model="fields.password_confirmation"  placeholder="Retype Password" />
                        </b-field>

                        <!-- <b-notification type="is-danger" 
                            :auto-close="false"
                            :active.sync="isActiveNotif"
                            v-if="this.errors.otp">
                            {{ errors.otp.unknown[0] }}
                        </b-notification> -->

                        <div class="buttons">
                            <button  :class="btnClass">RESET PASSWORD</button>
                        </div>
                        
                    </div>
                </div>
            </form>
        </div>
        
    </div>
</template>

<script>

export default {

    props: {
        propUser: {
            type: String,
            default: '',
        }
    },


    data(){
        return {
            fields: {
                username: '',
                password: '',
                otp: '',
                password_confirmation: '',
            },

            isActiveNotif: false,

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

            this.fields.username = this.propUser;

            
            axios.post('/reset-password-otp', this.fields).then(res=>{
            this.btnClass['is-loading'] = false;
                
                if(res.data.status === 'reset'){
                    this.$buefy.dialog.alert({
                        title: 'Reset!',
                        type: 'is-success',
                        message: 'Password reset successfully.',
                        onConfirm: ()=>{
                            window.location = '/'
                        }
                    });
                }
                
            }).catch(err=>{
                this.btnClass['is-loading'] = false;
                if(err.response.status === 422){
                    this.errors = err.response.data.errors;
                    //console.log(this.errors);
                    if(this.errors.otp){
                        //this.isActiveNotif = true;
                    }
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
