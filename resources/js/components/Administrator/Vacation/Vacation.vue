<template>
    <div>
        <div class="section">
            <div class="columns is-centered">
                <div class="column is-8">
                    <div class="w-panel-card">
                        <div class="w-panel-heading">
                            <div class="mb-2" style="font-size: 20px; font-weight: bold;">VACATION LIST</div>
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
                                    <b-button href="/vacations/create" tag="a"
                                    icon-left="plus" class="is-success">NEW VACATION</b-button>
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

        
                                <b-table-column field="vacation_id" label="ID" v-slot="props">
                                    {{ props.row.vacation_id }}
                                </b-table-column>
    
                                <b-table-column field="vacation_date" label="Vacation Date" v-slot="props">
                                    {{ props.row.vacation_date }}
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



    </div>
</template>

<script>

export default{
    data() {
        return{
            data: [],
            total: 0,
            loading: false,
            sortField: 'vacation_id',
            sortOrder: 'asc',
            page: 1,
            perPage: 5,
            defaultSortDirection: 'asc',


            global_id : 0,

            search: {
                v_date: '',
            },

           
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
            axios.get(`/get-vacations?${params}`)
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
            this.fields.vacations = [];
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
