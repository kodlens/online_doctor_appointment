<template>
    <div>
        <div class="columns">
            <div class="column">
                <b-field position="is-right"
                    label-position="on-border"
                    class="is-pulled-right-desktop">
                    <b-input type="text"
                        v-model="search.name" placeholder="Search Name"
                        @keyup.native.enter="loadAsyncData" />
                    
                    <p class="control">
                        <b-tooltip label="Search Name" type="is-success">
                            <b-button type="is-primary" icon-right="filter" @click="loadAsyncData"/>
                        </b-tooltip>
                    </p>
                </b-field>
            </div>  <!-- col -->
        </div> <!-- cols -->
        <hr>

        <b-table class="admin-tables"
            :data="data"
            :loading="loading"
            paginated
            detailed
            backend-pagination
            pagination-rounded
            :total="total"
            :per-page="perPage"
            @page-change="onPageChange"
            aria-next-label="Next page"
            aria-previous-label="Previous page"
            aria-page-label="Page"
            aria-current-label="Current page"
            backend-sorting
            :default-sort-direction="defaultSortDirection"
            @sort="onSort">

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

            <b-table-column field="appointment_id" label="ID" v-slot="props">
                {{ props.row.appointment_id }}
            </b-table-column>

            <b-table-column field="lname" label="Name" v-slot="props">
                {{ props.row.user.lname }}, {{ props.row.user.fname }} {{ props.row.user.mname }}
            </b-table-column>

            <b-table-column field="appointment_date" label="Appointment Date" v-slot="props">
                {{ new Date(props.row.appointment_date).toDateString() }}
            </b-table-column>

            <b-table-column field="time" label="Time" v-slot="props">
                {{ props.row.schedule.time_from | formatTime }} -  {{ props.row.schedule.time_end | formatTime }}
            </b-table-column>

            <b-table-column field="contact_no" label="Contact No." v-slot="props">
                {{ props.row.user.contact_no }}
            </b-table-column>

            <b-table-column field="is_arrived" label="Arrived" v-slot="props">
                <span class="status approved" v-if="props.row.is_arrived">YES</span>
                <span v-else>NO</span>
            </b-table-column>

            <b-table-column field="is_served" label="Served" v-slot="props">
                <span class="status approved" v-if="props.row.is_served">YES</span>
                <span v-else>NO</span>
            </b-table-column>


            <!-- <b-table-column field="status" label="Status" v-slot="props">
                <span class="status pending" v-if="props.row.status === 0">PENDING</span>
                <span class="status approved" v-if="props.row.status === 1">APPROVED</span>
                <span class="status cancelled" v-if="props.row.status === 2">CANCELLED</span>
            </b-table-column> -->

            <b-table-column label="Action" v-slot="props">
                <div class="is-flex">
                    <b-tooltip label="Reschedule" type="is-info">
                        <b-button class="button is-small is-info is-outlined mr-1" 
                            tag="a" 
                            icon-right="calendar"
                            :href="`/appointments/${props.row.appointment_id}/edit`"></b-button>
                    </b-tooltip>
                    <!-- <b-tooltip label="Delete" type="is-danger">
                        <b-button class="button is-small is-danger mr-1 is-outlined" icon-right="delete" @click="confirmDelete(props.row.user_id)"></b-button>
                    </b-tooltip> -->
                    <b-tooltip label="Options" type="is-info">
                        <b-dropdown aria-role="list">
                            <template #trigger="{ active }">
                                <b-button
                                    label=""
                                    type="is-primary"
                                    class="is-outliend is-small"
                                    :icon-right="active ? 'menu-up' : 'menu-down'" />
                            </template>

                            <!-- <b-dropdown-item @click="confirmApprove(props.row.appointment_id)" 
                                aria-role="listitem">Approve</b-dropdown-item> -->

                            <b-dropdown-item
                                @click="confirmArrive(props.row.appointment_id)" 
                                aria-role="listitem">Mark Arrived</b-dropdown-item>
                            <b-dropdown-item
                                @click="confirmServe(props.row.appointment_id)" 
                                aria-role="listitem">Mark Served</b-dropdown-item>

                            <b-dropdown-item 
                                v-if="props.row.is_served !== 1 && props.row.is_arrived !== 1"
                                @click="confirmCancel(props.row.appointment_id)" 
                                aria-role="listitem">Cancel Appointment</b-dropdown-item>
                            <!-- <b-dropdown-item
                                @click="confirmPending(props.row.appointment_id)" 
                                aria-role="listitem">Set to Pending</b-dropdown-item> -->
                            <b-dropdown-item
                                @click="confirmArchive(props.row.appointment_id)" 
                                aria-role="listitem">Archive</b-dropdown-item>

                            
                        </b-dropdown>
                        
                    </b-tooltip>
                </div>
            </b-table-column>

            <template #detail="props">
                <div v-if="props.row.patients">
                    <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Address</th>
                        <th>Illness</th>
                    </tr>
                    <tr v-for="(i,ix) in props.row.patients" :key="ix">
                        <td>{{ i.lname }}, {{ i.fname }} {{ i.mname }}</td>
                        <td>{{ i.age }}</td>
                        <td>
                            {{ i.provDesc }}, {{ i.citymunDesc }}, {{ i.brgyDesc }} {{ i.street }}
                        </td>
                        <td>{{ i.illness }}</td>
                    </tr>
                </div>
                
            </template>

        </b-table>

        <div class="buttons">
            <b-button type="is-info"
                @click="closeAppointments" 
                label="Close Appointments">
            </b-button>
        </div>

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

            modalResetPassword: false,


            search: {
                name:'',
                lname: '',
                //start_date: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
                //end_date: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0)
            },

            isModalCreate: false,

            fields: {},
            errors: {},
         

            btnClass: {
                'is-success': true,
                'button': true,
                'is-loading':false,
            },

            provinces: [],
            cities: [],
            barangays: [],
        }

    },

    methods: {
        /*
        * Load async data
        */
        loadAsyncData() {
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                //`start=${this.$formatDate(this.search.start_date)}`,
                //`end=${this.$formatDate(this.search.end_date)}`,
                `name=${this.search.name}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-daily-appointments?${params}`)
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

        //alert box ask for approve
        confirmApprove(id) {
            this.$buefy.dialog.confirm({
                title: 'Approve?',
                type: 'is-info',
                message: 'Are you sure you want to approve this appointment?',
                confirmText: 'Yes',
                onConfirm: () => this.approveSubmit(id)
            });
        },
        approveSubmit(id) {
            axios.post('/appointment-approve/' + id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        confirmArchive(id){
            this.$buefy.dialog.confirm({
                title: 'Archive?',
                type: 'is-info',
                message: 'Are you sure you want to archive this appointment?',
                confirmText: 'Yes',
                onConfirm: () => this.archiveSubmit(id)
            });
        },
        archiveSubmit(id){
            axios.post('/appointment-archive/' + id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },


        //alert box ask for cancel
        confirmCancel(id) {
            this.$buefy.dialog.confirm({
                title: 'Cancel?',
                type: 'is-danger',
                message: 'Are you sure you want to cancel this appointment?',
                confirmText: 'Yes',
                onConfirm: () => this.cancelSubmit(id)
            });
        },
        cancelSubmit(id) {
            axios.post('/appointment-cancel/' + id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },


        //alert box ask for cancel
        confirmPending(id) {
            this.$buefy.dialog.confirm({
                title: 'Set to Pending?',
                type: 'is-info',
                message: 'Are you sure you want to set pending this appointment?',
                confirmText: 'Yes',
                onConfirm: () => this.pendingSubmit(id)
            });
        },
        pendingSubmit(id) {
            axios.post('/appointment-pending/' + id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },


        confirmArrive(id) {
            this.$buefy.dialog.confirm({
                title: 'Set to Arrived?',
                type: 'is-info',
                message: 'Are you sure you want to set arrived this appointment?',
                confirmText: 'Yes',
                onConfirm: () => this.submitArrive(id)
            });
        },
        submitArrive(id) {
            axios.post('/appointment-set-arrived/' + id).then(res => {
                if(res.data.status === 'arrived'){
                    this.$buefy.dialog.alert({
                        title: 'Arrived.',
                        type: 'is-success',
                        message: 'Successfully set to arrived.',
                        confirmText: 'Ok',
                        onConfirm: () => {
                            this.loadAsyncData();
                        }
                    });
                }
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;

                    if(this.errors.early){
                        this.$buefy.dialog.alert({
                            title: 'Not Allowed.',
                            type: 'is-danger',
                            message: this.errors.early[0],
                            confirmText: 'Ok',
                        });
                    }
                }
            });
        },

        confirmServe(id) {
            this.$buefy.dialog.confirm({
                title: 'Set to Served?',
                type: 'is-info',
                message: 'Are you sure you want to set serve this appointment?',
                confirmText: 'Yes',
                onConfirm: () => this.submitServe(id)
            });
        },
        submitServe(id) {
            axios.post('/appointment-set-served/' + id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },



        
        closeAppointments() {
            this.$buefy.dialog.confirm({
                title: 'Close?',
                type: 'is-info',
                message: 'Are you sure you want to close all appointment this day?',
                confirmText: 'Yes',
                onConfirm: () => this.closeAppointmentsSubmit()
            });
        },
        closeAppointmentsSubmit() {
            axios.post('/appointment-set-close').then(res => {
                if(res.data.status === 'closed'){
                    this.loadAsyncData();
                }
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
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
