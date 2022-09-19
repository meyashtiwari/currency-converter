<template>
  <div class="container-fluid">
    <div class="card">
        <div class="d-flex justify-content-between card-header">
          Min, Max and Average
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Min</th>
                        <th scope="col">Max</th>
                        <th scope="col">Average</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ currencyData.minRate }}</td>
                        <td>{{ currencyData.maxRate }}</td>
                        <td>{{ currencyData.avgRate }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  </div>
</template>


<script>
import { useCurrencyStore } from '../stores/useCurrencyStore.js';  
import { mapStores, mapActions, mapState } from 'pinia';
export default {
    name: 'MinMaxAverage',
    data: () => ({
      api_url: '/api/fetch_CurrencyMMA',
      currencyData: null
    }),
    computed: {
      ...mapStores(useCurrencyStore),
      ...mapState(useCurrencyStore, ['baseCurrency']),
    },
    methods: {
      async getData() {
        const response = await axios.get(this.api_url + `?sCode=${this.baseCurrency}`);
        this.currencyData = response.data[0];
      },
    },
    watch: {
      baseCurrency(newValue, oldValue) {
        this.getData();
      }
    },
    mounted() {
      this.getData(this.api_url);
    }
}
</script>