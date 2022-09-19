<template>
  <div class="container">
        <div class="card">
          <div class="d-flex justify-content-between card-header">
            Currencies
            <BaseCurrencySelector :symbols="currencies_symbols" :baseCurrency="baseCurrency" @updateBaseCurrency="updateBaseCurrency"/>
          </div>
          <div class="card-body">
            <table v-if="currencies" class="table table-bordered">
              <thead>
                <tr>
                  <th>Symbol</th>
                  <th>Rate</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="currency in currencies">
                  <td>{{ currency.symbol }}</td>
                  <td>{{ currency.rate }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- Pagination -->
          <Pagination :meta="meta" @pageChange="updateData" class="mt-8" />
        </div>
      </div>
  </div>
</template>

<script>
import Pagination from './Pagination.vue';
import BaseCurrencySelector from './BaseCurrencySelector.vue';
import { useCurrencyStore } from '../stores/useCurrencyStore.js';  
import { mapStores, mapActions, mapState } from 'pinia';

export default {
  name: 'Currencies',
  components: {
    Pagination,
    BaseCurrencySelector
  },
  data: () => ({
    currencies: null,
    meta: null,
    api_url: '/api/currency_nominal?sCode=',
    currencies_symbols: null,
  }),
  computed: {
    ...mapStores(useCurrencyStore),
    ...mapState(useCurrencyStore, ['baseCurrency']),
    get_api_url() {
      return this.api_url + `${this.baseCurrency}`;
    }
  },
  methods: {
    ...mapActions(useCurrencyStore, ['updateCurrency']),
    updateData(link) {
      this.getData(link + `&sCode=${this.baseCurrency}`);
    },
    updateBaseCurrency(symbol) {
      this.updateCurrency(symbol);
      this.syncDataForNewCurrency(symbol);
      this.getData(this.get_api_url);
    },
    async getData(api_url) {
      const response = await axios.get(api_url);
      const { data: { data } } = response;
      this.meta = response.data;
      this.currencies = data;
    },
    async syncDataForNewCurrency(sCode) {
      const response = await axios.get(`/api/currency_new/${sCode}`);
    }
  },
  async mounted() {
    this.getData(this.get_api_url);
    const currenciesResponse = await axios.get('/api/all-currencies');
    this.currencies_symbols = currenciesResponse.data;
  },
}
</script>
