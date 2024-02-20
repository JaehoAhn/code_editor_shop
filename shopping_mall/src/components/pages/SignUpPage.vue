<script>
export default {
  name: "SignUpPage",
  data() {
    return {
      is_valid: true,
      error_msg: "",
      error_loca: "",
    };
  },

  methods: {
    check_is_valid_sign_up_in_front(email, pswd) {
      var regex_email =
        /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
      var regex_pswd = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-])(?=.*[0-9]).{8,15}$/;
      if (!email.match(regex_email)) {
        console.log("email");
        this.is_valid = false;
        this.error_msg = "This is not valid email formation.";
        this.error_loca = "email";
      } else if (!pswd.match(regex_pswd)) {
        console.log("pswd");
        this.is_valid = false;
        this.error_msg =
          "Password should contain at least one numeric digit, one uppercase, and one lowercase letter.";
        this.error_loca = "pswd";
      } else {
        this.is_valid = true;
        this.error_msg = "";
        this.error_loca = "";
      }
    },

    trySignUp() {
      //front
      var email = $("#email").val();
      var pswd = $("#pswd").val();

      this.check_is_valid_sign_up_in_front(email, pswd);

      if (this.error_loca == "email") {
        $("#pswd").closest(".form-group").find(".invalid-feedback").hide();
        $("#email")
          .closest(".form-group")
          .find(".invalid-feedback")
          .html(this.error_msg);
        $("#email").closest(".form-group").find(".invalid-feedback").show();
      }

      if (this.error_loca == "pswd") {
        $("#email").closest(".form-group").find(".invalid-feedback").hide();
        $("#pswd")
          .closest(".form-group")
          .find(".invalid-feedback")
          .html(this.error_msg);
        $("#pswd").closest(".form-group").find(".invalid-feedback").show();
      }

      if (this.error_loca == "") {
        $("#pswd").closest(".form-group").find(".invalid-feedback").hide();
        console.log("Sucess sign up.");

        $.ajax({
          type: "GET",
          url:
            "http://localhost:80/shopping_mall/api/add_user.php?" +
            $("#signForm").serialize(),
          dataType: "json",
          success: function (result) {
            if (result["proceed"]) {
              window.location = "/login";
            }
          },
        });
      }
    },
  },

  mounted() {},

  created() {},
};
</script>

<template>
  <div id="signup_nav">
    <a href="/">
      <img src="../img/logo1.png" />
    </a>
  </div>

  <div
    id="signup_container"
    class="d-flex align-items-center d-flex justify-content-center"
  >
    <div id="signup_intro">
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

    <div id="signup_form">
      <p class="signup_intro">
        Hi,<br />
        Please enter your information.<br />
        It won't be shared.
      </p>

      <form method="POST" style="display: block" id="signForm">
        <div class="signup_fname form-group">
          <p>
            <b> First Name </b>
          </p>
          <input
            name="first_name"
            id="first_name"
            type="text"
            placeholder="Enter first name"
            required
          />
        </div>

        <div class="signup_lname">
          <p>
            <b> Last Name </b>
          </p>

          <input
            name="last_name"
            id="last_name"
            type="text"
            placeholder="Enter last name"
            required
          />
        </div>

        <div class="signup_email form-group">
          <p>
            <b> Email </b>
          </p>

          <input
            name="email"
            id="email"
            type="email"
            placeholder="Enter email"
            required
          />
          <div class="invalid-feedback"></div>
        </div>

        <div class="signup_pswd form-group">
          <p>
            <b> Password </b>
          </p>

          <input
            name="pswd"
            id="pswd"
            type="password"
            placeholder="Enter password"
            required
          />
          <div class="invalid-feedback"></div>
        </div>

        <a>
          <button
            type="button"
            id="signup_btn"
            style="margin-top: 20px"
            class="btn btn-dark"
            v-on:click="trySignUp()"
          >
            Sign Up
          </button>
        </a>
      </form>
    </div>
  </div>
</template>

<style>
#signup_nav {
  height: 20vh;
  /* border: 2px solid red; */

  margin-top: 10vh;
  padding-left: 15vw;
}

#signup_nav img {
  height: 100%;
  width: auto;
}

#signup_container {
  display: flex;
  height: 50vh;

  /* margin-top: 5vh; */
}

#signup_intro {
  text-align: center;
  /* border: 2px solid blue; */

  width: 30vw;
}

#signup_intro .intro {
  font-size: 25px;
  font-family: Georgia, "Times New Roman", Times, serif;

  color: #151515;
}

#signup_form {
  padding: 10px;
  width: 330px;
  height: auto;

  border: 1px solid black;
  box-shadow: 10px 10px;
}

#signup_form p {
  margin: 0;
  margin-top: 20px;
}

#signup_form input {
  margin: 0;
}

.signup_intro {
  font-size: 20px;
  font-family: Georgia, "Times New Roman", Times, serif;
  color: #151515;
}

#signup_btn {
  width: 100%;
}
</style>
