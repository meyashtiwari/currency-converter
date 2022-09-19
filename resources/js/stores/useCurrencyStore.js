import { defineStore } from 'pinia'

export const useCurrencyStore = defineStore('currency', {
  state: () => {
    return { baseCurrency: 'USD' };
  },

  actions: {
    updateCurrency(value) {
        this.baseCurrency = value;
    }
  }
})