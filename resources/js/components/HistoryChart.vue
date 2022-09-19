<template>
  <div class="container-fluid">
    <div class="card">
        <div class="d-flex justify-content-between card-header">
          History Chart
          <input type="date" id="datePicker" v-model="date" />
        </div>
        <div class="card-body">
          <canvas id="myChart"></canvas>
        </div>
    </div>
  </div>
</template>

<script>
import Chart from 'chart.js/auto';
import { useCurrencyStore } from '../stores/useCurrencyStore.js';  
import { mapStores, mapActions, mapState } from 'pinia';
export default {
    name: 'HistoryChart',
    data: () => ({
      api_url: '/api/fetch_History',
      labels: [],
      data_set: [],
      date: '',
      myChart: null
    }),
    computed: {
      ...mapStores(useCurrencyStore),
      ...mapState(useCurrencyStore, ['baseCurrency']),
    },
    methods: {
      async getData() {
        const response = await axios.get(this.api_url + `?sCode=${this.baseCurrency}&date=${this.date}`);
        this.labels = Object.keys(response.data.rates);
        this.data_set = Object.values(response.data.rates);
        this.renderLineChart();
      },
      renderLineChart: function() {
        this.myChart.data.datasets[0].data = this.data_set;
        this.myChart.data.labels = this.labels;
        this.myChart.update();
      }
    },
    watch: {
      date(newDate, oldDate) {
        this.getData();
      },
      baseCurrency(newValue, oldValue) {
        this.getData();
      }
    },
    mounted() {
      const ctx = document.getElementById('myChart');
      document.getElementById('datePicker').valueAsDate = new Date();
      this.getData(this.api_url);
      this.myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: this.labels,
          datasets: [{
            label: 'History',
            backgroundColor: 'rgb(255, 99, 132)',
            borderColor: 'rgb(255, 99, 132)',
            data: this.data_set,
          }],
        }
      });
    }
}
</script>