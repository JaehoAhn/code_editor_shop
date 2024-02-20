<script>
export default {
  name: "LoginPage",
  data() {
    return {};
  },

  methods: {
    get_users() {
      $.ajax({
        type: "GET",
        url: "http://localhost:80/shopping_mall/api/login_session.php?" + $("#login_form_datas").serialize(),
        async: false,
        dataType: "json",
        success: function (result) {

          //Login information store in session storage.
          if (result['proceed']) {
            sessionStorage.setItem("user_no", result['uno']);
            sessionStorage.setItem("first_name", result['ufname']);
            sessionStorage.setItem("last_name", result['ulname']);
            sessionStorage.setItem("is_login", result['proceed']);
            sessionStorage.setItem("email", result['uemail']);

            window.location = "/indexlogged"
          }
        },
      });
    },
  },

  mounted() {},

  created() {
  },
};
</script>

<template>
  <div id="login_nav">
    <a href="/">
      <img src="../img/logo1.png" />
    </a>
  </div>

  <div
    id="login_container"
    class="d-flex align-items-center d-flex justify-content-center"
  >
    <div id="login_intro">
      <p class="intro">
        Lorem ipsum dolor sit amet, <br />
        consectetur adipiscing elit. <br />
        Donec eu gravida sem. <br />
      </p>

      <p class="intro">
        ###-###-#### <br />
        ###-###-#### <br />
        ###-###-#### <br />
      </p>
    </div>

    <div id="login_form">
      <p class="welcome">
        Welcome Back,<br />
        Please login to your account.
      </p>

      <form method="post" id="login_form_datas">
        <div class="username">
          <p><b>Username</b></p>
          <input
            type="text"
            placeholder="Enter Email"
            id="uemail"
            name="uemail"
            required
          />
        </div>

        <div class="password">
          <p><b>Password</b></p>
          <input
            type="password"
            placeholder="Enter Password"
            id="upswd"
            name="upswd"
            required
          />
        </div>

        <button
          v-on:click="get_users()"
          type="button"
          id="login_btn"
          class="btn btn-dark"
        >
          Login
        </button>
      </form>
    </div>
  </div>
</template>

<style>
#login_nav {
  height: 20vh;
  /* border: 2px solid red; */

  margin-top: 10vh;
  padding-left: 15vw;
}

#login_nav img {
  height: 100%;
  width: auto;
}

#login_container {
  display: flex;
  /* border: 2px solid purple; */
  height: 50vh;

  /* margin-top: 5vh; */
}

#login_intro {
  text-align: center;
  /* border: 2px solid blue; */

  width: 30vw;
}

#login_intro .intro {
  font-size: 25px;
  font-family: Georgia, "Times New Roman", Times, serif;

  color: #151515;
}

#login_form {
  border: 1px solid black;
  padding: 10px;
  box-shadow: 10px 10px;
}

#login_form .welcome {
  font-size: 20px;
  font-family: Georgia, "Times New Roman", Times, serif;
  color: #151515;
}

#login_form .password {
  margin-top: 30px;
}

#login_form form {
  margin-top: 50px;
}

#login_form p {
  font-size: 15px;
  margin: 0;
}

#login_form input {
  margin: 0;
}

#login_btn {
  margin-top: 20px;
  width: 100%;
}
</style>
