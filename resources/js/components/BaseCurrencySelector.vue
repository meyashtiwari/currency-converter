<template>
    <div class="dropdown">
        <select v-if="symbols" name="baseCurrency" @change="handler">
            <option :value="baseCurrency" selected>{{ baseCurrency }}</option>
            <option v-for="symbol in symbols" v-if="symbol.symbol !== baseCurrency" :value="symbol.symbol">{{ symbol.symbol }}</option>
        </select>
    </div>
</template>

<script>
export default {
    name: 'BaseCurrencySelector',
    props: ['symbols', 'baseCurrency'],
    emits: ['updateBaseCurrency'],

    methods: {
        handler(event) {
            this.$emit('updateBaseCurrency', event.target.value);
        },
    }
}
</script>