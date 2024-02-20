<script setup>
    import NavBar from "../NavBar.vue";
    import SearchResult from '../SearchResult.vue';
    import ProductIndex from '../ProductIndex.vue';
    import ProductDesc from '../ProductDesc.vue';
</script>

<script>
    import ProductIndex from '../ProductIndex.vue';
    export default {
        data() {
            return {

            }
        },

        components : {
            NavBar,
            ProductIndex,
            ProductDesc
        },

        methods: {

            
        }
    }

</script>

<template>
    <NavBar></NavBar>
    <ProductDesc style="margin-top: 5%;"></ProductDesc>
</template>

<style scoped>

</style>