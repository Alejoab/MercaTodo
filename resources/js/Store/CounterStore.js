import {defineStore} from "pinia";

export const useCartCounter = defineStore('counter', {
    state: () => ({
        count: 0
    }),
    actions: {
        increment() {
            this.count++;
        },
        async syncCartCount() {
            let response = await axios(route('cart.count'))
            this.count = response.data;
        }
    }
})
