import {defineStore} from "pinia";

export const useCartCounter = defineStore('counter', {
    state: () => ({
        count: 0
    }),
    actions: {
        async increment() {
            let response = await axios(route('cart.count'))
            this.count = response.data;
        },
        async syncCartCount() {
            let response = await axios(route('cart.count'))
            this.count = response.data;
        }
    }
})
