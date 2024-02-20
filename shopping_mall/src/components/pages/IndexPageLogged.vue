<script>
export default {
  name: "IndexPage",
  data() {
    return {
      search_input: "*",
      last_name: sessionStorage.getItem("last_name")
    };
  },

  methods: {
    update_search_input() {
      if (document.querySelector("input").value == "") {
        this.search_input = "*";
      } else {
        this.search_input = document.querySelector("input").value;
      }
      localStorage.setItem("search_result_user_search", this.search_input);
    },

    click_sign_out() {
      sessionStorage.clear();
      window.location = "/";
    }
  },

  mounted() {
    localStorage.setItem("search_result_user_search", "*");
  },

  created() {
    var src1 =
      "https://github.com/JaehoAhn/code_editor_shop/assets/82874416/28b1cf9c-8d73-4b0e-a9f6-2b5a09ed5032";
    var src2 =
      "https://github.com/JaehoAhn/code_editor_shop/assets/82874416/43cd8579-9903-4ee1-a3b8-d9cca6ed9e85";
    var src3 =
      "https://github.com/JaehoAhn/code_editor_shop/assets/82874416/5a2ad460-bf82-4843-a21c-25a398bd452b";

    var sources = [src1, src2, src3];

    var count = 0;
    setInterval(function () {
      $("#adImg").attr("src", sources[count]);

      count = count + 1;

      if (count == 2) {
        count = 0;
      }
    }, 2000);
  },
};
</script>

<template>
  <div id="nav_container">
    <div
      id="nav"
      class="d-flex justify-content-between d-flex align-items-center"
    >
      <img src="../img/logo1.png" />

      <div id="buttons">
        <h1>
          Hello {{last_name}}.
        </h1>

        <h1 style="margin-left: 40px;" v-on:click="click_sign_out">
          Sign out
        </h1>
      </div>
    </div>
  </div>

  <div id="mainAd">
    <a href="/shop">
      <img id="adImg" src="../img/vs_code.jpg" />
    </a>
  </div>

  <div
    id="form_container"
    class="d-flex justify-content-center d-flex align-items-center"
  >
    <input id="search_input" type="search" placeholder="Search for an editor" />
    <a href="/shop">
      <button
        id="search_btn"
        type="button"
        class="btn btn-dark"
        v-on:click="update_search_input()"
      >
        Search
      </button>
    </a>
  </div>
</template>

<style scoped>
#nav_container {
  width: 100vw;
  height: 20vh;
  margin-top: 20px;
}

#nav {
  display: flex;
  margin: auto;
  width: 80%;

  margin-top: 20px;
}

#nav img {
  width: 10%;
  height: auto;

  margin-left: 5%;
}

/* margin 말고 다른방법? */
#nav #buttons {
  display: flex;
}

#nav #buttons button {
  width: 130px;
  height: 50px;

  border-radius: 2px;

  font-family: Georgia, "Times New Roman", Times, serif;

  margin-left: 15px;
}

#mainAd {
  /* border: red 2px solid; */

  width: 100vw;
  text-align: center;
  margin-top: 40px;
}

#mainAd img {
  height: 40vh;
  width: 40vh;
}

#form_container {
  widows: 100vw;
  margin-top: 80px;
}

#form_container input {
  width: 40%;
  height: 50px;

  font-family: Georgia, "Times New Roman", Times, serif;

  position: relative;
}

#search_btn {
  height: 50px;
  width: 130px;
  border-radius: 2px;
  font-family: Georgia, "Times New Roman", Times, serif;
  margin-left: 20px;
}
</style>
