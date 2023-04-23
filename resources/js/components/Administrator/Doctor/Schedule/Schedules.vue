<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="w-panel-card">
                        <div class="w-panel-heading">
                            <div class="mb-2" style="font-size: 20px; font-weight: bold;">SCHEDULES</div>
                        </div>

                        <div class="w-panel-body">

                            <div class="level">
                                <div class="level-left">
                                    
                                </div>
    
                                <div class="level-right">
                                    <div class="level-item">
                                        <b-field label="Search">
                                            <b-input type="text"
                                                     v-model="search.lname" placeholder="Search Lastname"
                                                     @keyup.native.enter="loadAsyncData"/>
                                            <p class="control">
                                                 <b-tooltip label="Search" type="is-success">
                                                <b-button type="is-primary" icon-right="account-filter" @click="loadAsyncData"/>
                                                 </b-tooltip>
                                            </p>
                                        </b-field>
                                    </div>
                                </div>
                            </div>

                           
                            <b-table
                                class="admin-tables"
                                :data="data"
                                :loading="loading"
                                paginated
                                backend-pagination
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
    
                                <div class="buttons mt-3">
                                    <b-button @click="openModal" 
                                    icon-left="plus" class="is-success">NEW SCHEDULES</b-button>
                                </div>

                                <b-field label="Page" label-position="on-border">
                                    <b-select v-model="perPage" @input="setPerPage">
                                        <option value="5">5 per page</option>
                                        <option value="10">10 per page</option>
                                        <option value="15">15 per page</option>
                                        <option value="20">20 per page</option>
                                        <option value="30">30 per page</option>
    
                                    </b-select>
                                    <b-select v-model="sortOrder" @input="loadAsyncData">
                                        <option value="asc">ASC</option>
                                        <option value="desc">DESC</option>
    
                                    </b-select>
                                </b-field>

        
                                <b-table-column field="schedule_id" label="ID" v-slot="props">
                                    {{ props.row.schedule_id }}
                                </b-table-column>
    
                                <b-table-column field="time_range" label="TIME" v-slot="props">
                                    {{ props.row.time_from | formatTime }} - {{ props.row.time_end | formatTime }}
                                </b-table-column>
    
                                <b-table-column field="max_no" label="NAME" v-slot="props">
                                    {{ props.row.max_no }}
                                </b-table-column>
    
                                <b-table-column field="day" label="DAY" v-slot="props">
                                    <span v-if="props.row.mon">M</span>
                                    <span v-if="props.row.tue">T</span>
                                    <span v-if="props.row.wed">W</span>
                                    <span v-if="props.row.thu">TH</span>
                                    <span v-if="props.row.fri">F</span>
                                    <span v-if="props.row.sat">S</span>
                                    <span v-if="props.row.sun">SU</span>
                                </b-table-column>
    
    
                                <b-table-column label="ACTION" v-slot="props">
                                    <div class="is-flex">
                                        <b-tooltip label="Edit" type="is-primary">
                                            <b-button class="button is-small is-primary mr-1 is-outlined" 
                                                tag="a" 
                                                icon-right="pencil" 
                                                @click="getData(props.row.schedule_id)"></b-button>
                                        </b-tooltip>
                                        <b-tooltip label="Delete" type="is-danger">
                                            <b-button class="button is-small is-danger mr-1 is-outlined" 
                                                icon-right="delete" 
                                                @click="confirmDelete(props.row.schedule_id)"></b-button>
                                        </b-tooltip>
                                    </div>
                                </b-table-column>
                            </b-table>
    
                            
                        </div> <!--panel body-->
                    </div>
                </div><!--col -->
            </div><!-- cols -->
        </div><!--section div-->



        <!--modal create-->
        <b-modal v-model="isModalCreate" has-modal-card
                 trap-focus
                 :width="640"
                 aria-role="dialog"
                 aria-label="Modal"
                 aria-modal>

            <form @submit.prevent="submit">
                <div class="modal-card">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Schedule Information</p>
                        <button
                            type="button"
                            class="delete"
                            @click="isModalCreate = false"/>
                    </header>

                    <section class="modal-card-body">
                        <div class="">
                            <div class="columns">
                                <div class="column">
                                    <b-field label="Time Start" label-position="on-border"
                                             :type="this.errors.time_from ? 'is-danger':''"
                                             :message="this.errors.time_from ? this.errors.time_from[0] : ''">
                                        <b-timepicker v-model="fields.time_from"
                                                 placeholder="Time Start" required>
                                        </b-timepicker>
                                    </b-field>
                                </div>
                                 <div class="column">
                                    <b-field label="Time End" label-position="on-border"
                                             :type="this.errors.time_end ? 'is-danger':''"
                                             :message="this.errors.time_end ? this.errors.time_end[0] : ''">
                                        <b-timepicker v-model="fields.time_end"
                                                 placeholder="Time End" required>
                                        </b-timepicker>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Max No" label-position="on-border"
                                        :type="this.errors.max_no ? 'is-danger':''"
                                        :message="this.errors.max_no ? this.errors.max_no[0] : ''">
                                        <b-numberinput v-model="fields.max_no"
                                            controls-alignment="right"
                                            controls-position="compact"
                                            placeholder="Max number of person in this schedule" required>
                                        </b-numberinput>
                                    </b-field>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <b-field label="Days">
                                        <b-checkbox v-model="fields.mon"
                                            :true-value="1"
                                            :false-value="0">Mon</b-checkbox>
                                        <b-checkbox v-model="fields.tue"
                                            :true-value="1"
                                            :false-value="0">Tue</b-checkbox>
                                        <b-checkbox v-model="fields.wed"
                                            :true-value="1"
                                            :false-value="0">Wed</b-checkbox>
                                        <b-checkbox v-model="fields.thu"
                                            :true-value="1"
                                            :false-value="0">Thur</b-checkbox>
                                        <b-checkbox v-model="fields.fri"
                                            :true-value="1"
                                            :false-value="0">Fri</b-checkbox>
                                        <b-checkbox v-model="fields.sat"
                                            :true-value="1"
                                            :false-value="0">Sat</b-checkbox>
                                        <b-checkbox v-model="fields.sun"
                                            :true-value="1"
                                            :false-value="0">Sun</b-checkbox>
                                    </b-field>
                                </div>
                            </div>
                            
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button
                            :class="btnClass"
                            label="Save"
                            type="is-success">SAVE</button>
                    </footer>
                </div>
            </form><!--close form-->
        </b-modal>
        <!--close modal-->

    </div>
</template>

<script>

export default{
    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'schedule_id',
            sortOrder: 'asc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',


            global_id : 0,

            search: {
                lname: '',
            },

            isModalCreate: false,

            fields: {},
            errors: {},
            offices: [],

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
            const params = [
                `sort_by=${this.sortField}.${this.sortOrder}`,
                `lname=${this.search.lname}`,
                `perpage=${this.perPage}`,
                `page=${this.page}`
            ].join('&')

            this.loading = true
            axios.get(`/get-schedules?${params}`)
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
            this.clearFields();
        },


        submit: function(){
            if(this.global_id > 0){
                //update
                axios.put('/schedules/'+this.global_id, this.fields).then(res=>{
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
                let timeFrom = new Date(this.fields.time_from)
                let timeTo = new Date(this.fields.time_end)

                this.fields.from = '2022-01-01 ' + timeFrom.toTimeString()
                this.fields.to = '2022-01-01 ' + timeTo.toTimeString();

                axios.post('/schedules', this.fields).then(res=>{
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
        confirmDelete(delete_id) {
            this.$buefy.dialog.confirm({
                title: 'DELETE!',
                type: 'is-danger',
                message: 'Are you sure you want to delete this data?',
                cancelText: 'Cancel',
                confirmText: 'Delete?',
                onConfirm: () => this.deleteSubmit(delete_id)
            });
        },
        //execute delete after confirming
        deleteSubmit(delete_id) {
            axios.delete('/schedules/' + delete_id).then(res => {
                this.loadAsyncData();
            }).catch(err => {
                if (err.response.status === 422) {
                    this.errors = err.response.data.errors;
                }
            });
        },

        clearFields(){
            this.fields = {
                time_from: null,
                time_end: null,
                max_no: 0,
                mon: 0,
                tue: 0,
                wed: 0,
                thu: 0,
                fri: 0,
                sat: 0,
                sun: 0
            };

            this.errors = {};
        },


        //update code here
        getData: function(data_id){
            this.clearFields();
            this.global_id = data_id;
            this.isModalCreate = true;

            //nested axios for getting the address 1 by 1 or request by request
            axios.get('/schedules/'+ data_id).then(res=>{
                console.log(res.data);
                this.fields.schedule_id = res.data.schedule_id;
                this.fields.time_from = new Date('2022-01-01 ' + res.data.time_from);
                this.fields.time_end = new Date('2022-01-01 ' + res.data.time_end);

                this.fields.max_no = res.data.max_no;
                this.fields.mon = res.data.mon;
                this.fields.tue = res.data.tue;
                this.fields.wed = res.data.wed;
                this.fields.thu = res.data.thu;
                this.fields.fri = res.data.fri;
                this.fields.sat = res.data.sat;
                this.fields.sun = res.data.sun;
            });
        },


    },

    mounted(){
        this.loadAsyncData();
    }
}
</script>


<style>


</style>
