<script setup>
    import NavBar from "../NavBar.vue";
    import UserAccout from "../UserAccount.vue";
</script>

<template>
    <NavBar></NavBar>

    <div id="user_account_container" class="d-flex justify-content-center">
        <UserAccout></UserAccout>
    </div>
</template>

<style scoped>

    #user_account_container {
        padding: 20px;


    }

</style>