<script setup>
    import NavBar from "../NavBar.vue";
    import UserCart from "../UserCart.vue";
</script>

<template>
    <NavBar></NavBar>

    <div id="user_account_container" class="d-flex justify-content-center">
        <UserCart></UserCart>
    </div>
</template>

<style scoped>

    #user_account_container {
        padding: 20px;


    }

</style>