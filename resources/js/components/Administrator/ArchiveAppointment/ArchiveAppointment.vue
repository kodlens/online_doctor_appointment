<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-10-desktop is-8-tablet">
                    <div class="w-panel-card">
                        <div class="w-panel-heading">
                            <div class="mb-2" style="font-size: 20px; font-weight: bold;">ARCHIVE APPOINTMENTS</div>
                        </div>


                        <div class="w-panel-body">

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Date Filter" label-position="on-border">
                                        <b-datepicker v-model="search.start_date" placeholder="Start date"></b-datepicker>
                                        <b-datepicker v-model="search.end_date" placeholder="End date"></b-datepicker>
                                    </b-field>
                                </div>
                                <div class="column">
                                    <b-field label="Search Name" 
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

                            <div class="columns">
                                <div class="column">
                                    <div class="buttons">
                                        <b-button tag="a"
                                            href="/archive-appointments/create"
                                            icon-left="archive" class="is-success">
                                            ARCHIVE/RESTORE APPOINTMENT
                                        </b-button>
                                    </div>
                                </div>
                            </div>

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
    
                                <b-table-column field="time" label="TIME" v-slot="props">
                                    {{ props.row.schedule.time_from | formatTime }} -  {{ props.row.schedule.time_end | formatTime }}
                                </b-table-column>

                                <b-table-column field="contact_no" label="Contact No." v-slot="props">
                                    {{ props.row.user.contact_no }}
                                </b-table-column>

                               

                                <b-table-column field="status" label="Status" v-slot="props">
                                    <span class="status pending" v-if="props.row.status === 0">PENDING</span>
                                    <span class="status approved" v-if="props.row.status === 1">APPROVED</span>
                                    <span class="status cancelled" v-if="props.row.status === 2">CANCELLED</span>
                                </b-table-column>

                                <b-table-column field="is_served" label="Arrive" v-slot="props">
                                    <span class="status approved" v-if="props.row.is_arrived === 1">ARRIVED</span>
                                    <span class="status cancelled" v-if="props.row.is_arrived === 0">NO</span>
                                </b-table-column>



                                <b-table-column field="is_served" label="Serve" v-slot="props">
                                    <span class="status approved" v-if="props.row.is_served === 1">SERVED</span>
                                    <span class="status cancelled" v-if="props.row.is_served === 0">NO</span>
                                </b-table-column>

                                <b-table-column label="Action" v-slot="props">
                                    <div class="is-flex">
                                        <b-tooltip label="Restore" type="is-info">
                                            <b-button class="button is-small is-info is-outlined mr-1" 
                                                tag="a" 
                                                icon-right="archive-arrow-up-outline"
                                                @click="restoreArchive(props.row.appointment_id)"></b-button>
                                        </b-tooltip>
                                        
                                    </div>
                                </b-table-column>




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

            modalResetPassword: false,


            search: {
                name: '',
                start_date: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
                end_date: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0)
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
                `start=${this.$formatDate(this.search.start_date)}`,
                `end=${this.$formatDate(this.search.end_date)}`,
                `name=${this.search.name}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-archive-appointments?${params}`)
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
        restoreArchive(id) {
            this.$buefy.dialog.confirm({
                title: 'Restore?!',
                type: 'is-info',
                message: 'Are you sure you want to restore this?',
                confirmText: 'Yes',
                onConfirm: () => this.approveRestore(id)
            });
        },
        approveRestore(id) {
            axios.post('/archive-appointment-restore/' + id).then(res => {
                this.loadAsyncData();
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
