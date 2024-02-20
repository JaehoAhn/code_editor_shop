<script>
export default {
  name: "TestPage",
  data() {
    return {};
  },

  methods: {
    trySignUp() {
      $.ajax({
        type: "POST",
        url: "http://localhost:80/shopping_mall/api/add_user.php",
        data: $("#signForm").serialize(),
        async: true,
        dataType: "json",
        success: function (result) {
          console.log(result);
        },
      });
    },
  },

  mounted() {},

  created() {},
};
</script>


<template>
  <form method="POST" style="display: block" id="signForm">
    <div class="signup_fname">
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

    <div class="signup_email">
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
    </div>

    <div class="signup_email">
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
</template>
