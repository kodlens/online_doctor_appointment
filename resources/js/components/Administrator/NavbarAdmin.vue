<template>
    <b-navbar>
        <template #brand>
            <b-navbar-item class="has-text-weight-bold">
                ADMINISTRATOR
            </b-navbar-item>
        </template>
        <template #start>
            <b-navbar-item href="#">
                Home
            </b-navbar-item>
            <b-navbar-item href="#">
                Documentation
            </b-navbar-item>
            <b-navbar-dropdown label="Setting">
                <b-navbar-item href="/schedules">
                    Setup Schedule
                </b-navbar-item>
                <b-navbar-item href="#">
                    Contact
                </b-navbar-item>
            </b-navbar-dropdown>
        </template>

        <template #end>
            <b-navbar-item tag="div">
                <div class="buttons">
                    <b-button class="button is-danger" 
                        v-if="isAuth == 1"
                        @click="logout">
                        <strong>Log out</strong>
                    </b-button>
                    <b-button v-else class="button is-primary">
                        <strong>Log in</strong>
                    </b-button>
                </div>
            </b-navbar-item>
        </template>
    </b-navbar>

</template>

<script>
export default {

    props: {
        isAuth: Number,
        default: 0
    },

    data(){
        return{
           
        }
    },
    methods: {
        logout(){
            axios.post('/logout').then(()=>{
                window.location = '/';
            })
        }
    },

    mounted(){
        console.log(this.isAuth);
    }
}
</script>

<style scoped>
    

</style>
