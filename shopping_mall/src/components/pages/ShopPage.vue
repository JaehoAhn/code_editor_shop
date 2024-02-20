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
                comp : "ProductIndex"
            }
        },

        components : {
            NavBar,
            ProductIndex,
            ProductDesc
        },

        methods: {
            run_product_index() {
                this.$refs.product_index.get_products_list();
            },

            run_search_result(s) {
                // console.log(s);
                this.$refs.search_result.set_user_search(s);

                var num_data = this.$refs.product_index.get_num_data();
                this.$refs.search_result.set_num_result(num_data);
            },
            
        }
    }

</script>

<template>
    <NavBar @run_product_index="run_product_index" @update_search_result="run_search_result"></NavBar>
    <SearchResult ref="search_result"></SearchResult>
    <ProductIndex ref="product_index"/>
</template>

<style scoped>

</style>