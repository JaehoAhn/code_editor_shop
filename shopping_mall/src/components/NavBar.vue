<script>
  export default {
        name : "IndexPage",
        data() {
            return {
                search_input : "*",
            }
        },

        methods : {
            update_search_result() {
                this.search_input = $("#search_box_input").val();
                this.$emit('update_search_result', this.search_input);
            },

            click_logo() {
              if (sessionStorage.getItem('is_login')) {
                window.location = "/indexlogged";

              }

              else {
                window.location = "/";
              }
            },

            click_search() {
              this.$emit('run_product_index');
              this.update_search_result();

            },

            click_account() {
              if (sessionStorage.getItem("user_no") == null) {
                window.location = "/login";
              }

              else {
                window.location = "/account";
              }
            }
        },

        created() {

            
        }
        
    };




</script>

<template>
  <div id="nav_container">
    <nav id="nav_bar" class="d-flex justify-content-around d-flex align-items-center">
      <div id="nav_left">
        <img src="./img/logo2.png" v-on:click="click_logo()"/>


        <div id="search_box">
          <input id="search_box_input" style="Search" placeholder="Search code editors">
          <img v-on:click="click_search()" id="search_box_icon" src="./img/search.png">
        </div>
      </div>

      <div id="navbarTogglerDemo02">
        <a class="nav-link" v-on:click="click_account">Account</a>
        <a class="nav-link" href="#" style="margin-left: 15px;">Orders</a>
        <a class="nav-link" href="#" style="margin-left: 15px;">Cart</a>
      </div>
    </nav>
  </div>
</template>

<style>

#nav_container {
  width: 100vw;
  height: 20vh;
  text-align: center;
  border: #f0f0f0 1px solid;
}

#nav_bar {
  widows: 100vw;
  height: 20vh;
}

img {
  width: 120px;
}

input {
    width: 300px;
    height: 35px;
    margin-left: 15px;
}

#nav_left {

  display: flex;
  align-items: center;
}

#navbarTogglerDemo02 {
    color: #151515;
    display: inline-flex;

    font-size: 15px;
    font-family: Georgia, 'Times New Roman', Times, serif;
}

#search_box {
  border: 1px solid black;
  padding: 2px;
  padding-right: 10px;
  margin-left: 20px;
}

#search_box_input {
  border: 0;
}

#search_box_icon {
  width: 25px;
}

</style>
