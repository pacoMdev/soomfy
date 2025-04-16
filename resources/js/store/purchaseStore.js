import { defineStore } from 'pinia';

export const usePurchaseStore = defineStore('purchase', {
  state: () => ({
    purchaseData: null,
  }),
  actions: {
    setPurchase(data) {
      this.purchaseData = data;
    },
    clearPurchase() {
      this.purchaseData = null;
    },
  },
}, 
{persist: true});