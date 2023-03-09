<template>
    <b-navbar>
        <template #brand>
            <b-navbar-item class="has-text-weight-bold">
                APPOINTMENT SYSTEM
            </b-navbar-item>
        </template>
        <template #start>
            <b-navbar-item href="#">
                Home
            </b-navbar-item>
            <b-navbar-item href="#">
                Documentation
            </b-navbar-item>
            <b-navbar-dropdown label="Info">
                <b-navbar-item href="#">
                    About
                </b-navbar-item>
                <b-navbar-item href="#">
                    Contact
                </b-navbar-item>
            </b-navbar-dropdown>
        </template>

        <template #end>
            <b-navbar-item tag="div">
                <div class="buttons" v-if="isLogin">
                    <b-button
                        @click="logout"
                        icon-left="logout"
                        class="button is-danger is-outlined">
                        <strong>Log out</strong>
                    </b-button>
                </div>
            </b-navbar-item>
        </template>
    </b-navbar>

</template>

<script>
export default {

     props: {
        isLogin: {
            type: Boolean,
            default: false
        }
    },

    data(){
        return{
            open: false,
            overlay: true,
            fullheight: true,
            fullwidth: false,
            right: true
        }
    },
    methods: {
        logout(){
            axios.post('/logout').then(()=>{
                window.location = '/';
            })
        }
    }
}
</script>

<style scoped>
    .logo{
        padding: 0 30px 0 30px;
        height: 90px;
    }

    .burger-div{
        width: 20px;
        height: 2px;
        background-color: #100069;
        margin: 0 0 2.5px 0;
        margin-left: auto;
        border-radius: 10px;
    }

    .burger-button{
        display: flex;
        flex-direction: column;
        margin-left: auto;
        padding: 1px;
    }


    .mynav{
        padding: 25px;
        border-bottom: 2px solid #100069;
        display: flex;
    }

    .mynav-brand{
        font-weight: bold;
        font-size: 1.2em;
    }

    @media print {
        .mynav{
            display: none;
            border: 1px solid red;
        }
    }


  /* .hero{
        background-image: url("/img/bg-hero.jpg");
        background-repeat: no-repeat;
        background-size: cover;
    } */

</style>
