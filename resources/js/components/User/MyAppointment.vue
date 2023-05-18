<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="w-panel-card">
                        <div class="w-panel-heading">
                            <div class="mb-2" style="font-size: 20px; font-weight: bold;">MY APPOINTMENTS</div>
                        </div>


                        <div class="w-panel-body">

                            <div class="level">
                                <div class="level-left">
                                    
                                </div>
    
                                <div class="level-right">
                                    <div class="level-item">
                                        <b-field label="Search" label-position="on-border">
                                            <b-datepicker
                                                    v-model="search.app_date" placeholder="Search date"
                                                    @keyup.native.enter="loadAsyncData"/>
                                            <p class="control">
                                                <b-tooltip label="Clear" type="is-primary">
                                                    <b-button class="is-primary is-outlined" icon-right="brush" @click="search.app_date = null"/>
                                                 </b-tooltip>
                                            </p>
                                            <p class="control">
                                                 <b-tooltip label="Search" type="is-primary">
                                                <b-button type="is-primary" icon-right="magnify" @click="loadAsyncData"/>
                                                 </b-tooltip>
                                            </p>
                                        </b-field>
                                    </div>
                                </div>
                            </div>
    
                            <b-table class="user-tables"
                                :data="data"
                                :loading="loading"
                                paginated
                                backend-pagination
                                pagination-rounded
                                :total="total"
                                detailed
                                :per-page="perPage"
                                @page-change="onPageChange"
                                aria-next-label="Next page"
                                aria-previous-label="Previous page"
                                aria-page-label="Page"
                                aria-current-label="Current page"
                                backend-sorting
                                :default-sort-direction="defaultSortDirection"
                                @sort="onSort">
    
                                <b-table-column field="appointment_id" label="Reference No." v-slot="props">
                                    {{ props.row.appointment_id }}
                                </b-table-column>

                                <b-table-column field="appointment_date" label="APPOINTMENT DATE" v-slot="props">
                                    {{ new Date(props.row.appointment_date).toDateString() }}
                                </b-table-column>
    
                                <b-table-column field="time" label="TIME" v-slot="props">
                                    {{ props.row.schedule.time_from | formatTime }} -  {{ props.row.schedule.time_end | formatTime }}
                                </b-table-column>

                                <b-table-column field="illness_history" label="ILLNESS/HISTORY" v-slot="props">
                                    <div v-if="props.row.illness_history">
                                        <div  class="text-container">
                                            <p class="long-text">
                                                {{ props.row.illness_history }}
                                            </p>
                                        </div>
                                        <b-button
                                             class="is-small is-outlined is-info" 
                                            v-if="props.row.illness_history.length > 100" 
                                            @click="seeMore(props.row.illness_history)"
                                            label="see more..."></b-button>
                                    </div>
                                </b-table-column>
                                <b-table-column field="status" label="STATUS" v-slot="props">
                                    <span class="status pending" v-if="props.row.status === 0">PENDING</span>
                                    <span class="status approved" v-if="props.row.status === 1">APPROVED</span>
                                    <span class="status cancelled" v-if="props.row.status === 2">CANCELLED</span>
                                </b-table-column>

                                <b-table-column label="ACTION" v-slot="props">
                                    <div class="is-flex">
                                        <b-tooltip v-if="props.row.status === 0" label="Cancel Appointment" type="is-info">
                                            <b-button class="button is-small is-info is-outlined mr-1" 
                                            icon-right="cancel" 
                                            @click="cancelAppointment(props.row.appointment_id)"></b-button>
                                        </b-tooltip>
                                        
                                    </div>
                                </b-table-column>
    
                                <div class="is-flex mb-4">
                                    <b-field label="Page" label-position="on-border">
                                        <b-select v-model="perPage" @input="setPerPage">
                                            <option value="10">10 per page</option>
                                            <option value="20">20 per page</option>
                                            <option value="30">30 per page</option>
                                        </b-select>
                                        <b-select v-model="sortOrder" @input="loadAsyncData">
                                            <option value="asc">ASC</option>
                                            <option value="desc">DESC</option>
                                        </b-select>
                                    </b-field>
                                </div>
                                
                                <template #detail="props">
                                    <div v-if="props.row.patients">
                                        <tr>
                                            <th>Name</th>
                                            <th>Age</th>
                                            <th>Illness</th>
                                        </tr>
                                        <tr>
                                            <td>{{ props.row.patients.lname }}, {{ props.row.patients.fname }} {{ props.row.patients.mname }}</td>
                                            <td>{{ props.row.patients.age }}</td>
                                            <td>{{ props.row.patients.illness }}</td>
                                        </tr>
                                    </div>
                                </template>

                                
                            </b-table>
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
            data: [],
            total: 0,
            loading: false,
            sortField: 'appointment_id',
            sortOrder: 'desc',
            page: 1,
            perPage: 10,
            defaultSortDirection: 'asc',

            global_id : 0,

            search: {
                lname: '',
                app_date: null
            },
            app_date: '',

            isModalCreate: false,

            fields: {},

            errors: {},
           

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

         
        }

    },

    methods: {
        /*
        * Load async data
        */

        loadAsyncData() {

            if(!!this.search.app_date){
                let d = new Date(this.search.app_date)
                this.app_date = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate()
            }else{
                console.log('wala date');
                this.app_date = '';
            }
            

            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `appdate=${this.app_date}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-my-appointments?${params}`)
                .then(({ data }) => {
                    this.data = [];
                    let currentTotal = data.total
                    if (data.total / this.perPage > 1000) {
                        currentTotal = this.perPage * 1000
                    }

                    this.total = currentTotal
                    data.data.forEach((item) => {
                        //item.release_date = item.release_date ? item.release_date.replace(/-/g, '/') : null
                        this.data.push(item)
                    })
                    this.loading = false
                })
                .catch((error) => {
                    this.data = []
                    this.total = 0
                    this.loading = false
                    throw error
                })
        },
        /*
        * Handle page-change event
        */
        onPageChange(page) {
            this.page = page
            this.loadAsyncData()
        },

        onSort(field, order) {
            this.sortField = field
            this.sortOrder = order
            this.loadAsyncData()
        },

        setPerPage(){
            this.loadAsyncData()
        },

        openModal(){
            this.isModalCreate=true;
            this.fields = {};
            this.errors = {};
        },

        seeMore(stringN){
            this.$buefy.dialog.alert({
                title: 'Illness/History',
                message: stringN,
                type: 'is-info',
            })
        },

        

        submit: function(){
            if(this.global_id > 0){
                //update
                axios.put('/users/'+this.global_id, this.fields).then(res=>{
                    if(res.data.status === 'updated'){
                        this.$buefy.dialog.alert({
                            title: 'UPDATED!',
                            message: 'Successfully updated.',
                            type: 'is-success',
                            onConfirm: () => {
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                                this.isModalCreate = false;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                })
            }else{
                //INSERT HERE
                axios.post('/users', this.fields).then(res=>{
                    if(res.data.status === 'saved'){
                        this.$buefy.dialog.alert({
                            title: 'SAVED!',
                            message: 'Successfully saved.',
                            type: 'is-success',
                            confirmText: 'OK',
                            onConfirm: () => {
                                this.isModalCreate = false;
                                this.loadAsyncData();
                                this.clearFields();
                                this.global_id = 0;
                            }
                        })
                    }
                }).catch(err=>{
                    if(err.response.status === 422){
                        this.errors = err.response.data.errors;
                    }
                });
            }
        },


        //alert box ask for deletion
        cancelAppointment(appId) {
            this.$buefy.dialog.confirm({
                title: 'CANCEL!',
                type: 'is-danger',
                message: 'Are you sure you want to cancel this appointment?',
                cancelText: 'Cancel',
                confirmText: 'Cancel?',
                onConfirm: () => this.cancelSubmit(appId)
            });
        },
        //execute delete after confirming
        cancelSubmit(appId) {
            axios.post('/cancel-my-appointment/' + appId).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        clearFields(){
            this.fields.username = '';
            this.fields.lname = '';
            this.fields.fname = '';
            this.fields.mname = '';
            this.fields.suffix = '';
            this.fields.sex = '';
            this.fields.password = '';
            this.fields.password_confirmation = '';
            this.fields.role = '';

            this.fields.province = '';
            this.fields.city = '';
            this.fields.barangay = '';
            this.fields.street = '';
        },


        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;


            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/users/'+data_id).then(res=>{
                this.fields = res.data;
                this.fields.office = res.data.office_id;
                let tempData = res.data;
                //load city first
                axios.get('/load-cities?prov=' + this.fields.province).then(res=>{
                    //load barangay
                    this.cities = res.data;
                    axios.get('/load-barangays?prov=' + this.fields.province + '&city_code='+this.fields.city).then(res=>{
                        this.barangays = res.data;
                        this.fields = tempData;
                    });
                });
            });
        },

        openModalResetPassword(dataId){
            this.modalResetPassword = true;
            this.fields = {};
            this.errors = {};
            this.global_id = dataId;
        },

        resetPassword(){
            axios.post('/reset-password/' + this.global_id, this.fields).then(res=>{

                if(res.data.status === 'changed'){
                    this.$buefy.dialog.alert({
                        title: 'PASSWORD CHANGED',
                        type: 'is-success',
                        message: 'Password changed successfully.',
                        confirmText: 'OK',
                        onConfirm: () => {
                            this.modalResetPassword = false;
                            this.fields = {};
                            this.errors = {};
                            this.loadAsyncData()
                        }
                    });
                }

            }).catch(err=>{
                this.errors = err.response.data.errors;
            })
        },


    },

    mounted() {
        this.loadAsyncData();
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
