<template>
    <div>

        <div class="section">
            <div class="columns">
                <div class="column">
                    <p class="subtitle">Statistics</p>
                    <b-field label="Date Filter" label-position="on-border">
                        <b-datepicker v-model="search.start_date" placeholder="Start date"></b-datepicker>
                        <b-datepicker v-model="search.end_date" placeholder="End date"></b-datepicker>
                        <p class="control">
                            <b-button type="is-primary" icon-right="magnify" @click="loadAsyncData"/>
                        </p>
                    </b-field>
                </div>
            </div>
             <custom-line
                v-if="loaded"
                :options="chartOptions"
                :data="chartData"
            />
            
        </div>
       

    </div>
</template>
<script>

// 1. Import Chart.js so you can use the global Chart object
//import { Chart } from 'chart.js'
//import { Chart, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, PointElement } from 'chart.js'
import { 
    Chart, 
    LineController, 
    LineElement, 
    PointElement,
    LinearScale, 
    Title, 
    Tooltip, 
    Legend, 
    BarElement,
    CategoryScale
} from 'chart.js' ;

// 2. Import the `createTypedChart()` method to create the vue component.
import { createTypedChart } from 'vue-chartjs'
// 3. Import needed controller from Chart.js

class LineWithLineController extends LineController {

}
const CustomLine = createTypedChart('line', LineWithLineController)

Chart.register(
    LineWithLineController,
    LineElement,
    Title, 
    Tooltip, 
    Legend, 
    BarElement, 
    CategoryScale, 
    LinearScale,
    PointElement
);


export default {
    components: { CustomLine  },
    
    data() {
        return{
          

            loaded: false,
            search: {
                lname: '',
                start_date: new Date(new Date().getFullYear(), new Date().getMonth(), 1),
                end_date: new Date(new Date().getFullYear(), new Date().getMonth() + 1, 0),
                select: '',
            },

            chartData: {},

            chartOptions: {
                responsive: true
            }
        }
    },
    methods: {
        loadAsyncData() {
            const params = [
                `start=${this.$formatDate(this.search.start_date)}`,
                `end=${this.$formatDate(this.search.end_date)}`,
            ].join('&')

            axios.get(`/get-statistics?${params}`).then(res=>{
                //this.data = res.data

                let dataLabels = [];
                let count_app = [];
                let no_show = [];
                let served = [];
                let unserved = [];

                res.data.data.forEach(el => {
                    dataLabels.push(el.appointment_date)
                    count_app.push(el.no_app)
                    no_show.push(el.no_show)
                    served.push(el.served)
                    unserved.push(el.unserved)
                });

                this.chartData = {
                    labels: dataLabels,
                    datasets: [
                        {
                            label: 'No. Appointment',
                            backgroundColor: '#f87979',
                            data: count_app
                        },
                        {
                            label: 'No Show',
                            backgroundColor: '#425df5',
                            data: no_show
                        },
                        {
                            label: 'Served',
                            backgroundColor: '#24ad37',
                            data: served
                        },
                        {
                            label: 'Unserved',
                            backgroundColor: '#a36b1c',
                            data: unserved
                        }
                    ]
                }
                
                //console.log('labels' + this.dataLabels)

                this.loaded = true
            }).catch(err=>{
                this.loaded = true
            })
        },
    },
    mounted () {
        this.loaded = false;
        this.loadAsyncData()
    }
}
</script>
