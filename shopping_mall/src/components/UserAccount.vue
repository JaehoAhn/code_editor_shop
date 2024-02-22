<script>
var data;

export default {
  name: "UserAccount",
  data() {
    return {
      first_name: sessionStorage.getItem("first_name"),
      last_name: sessionStorage.getItem("last_name"),
      user_email: sessionStorage.getItem("email"),

      uploaded_name: ["vs code student edition", "sublime text"],
      uploaded_price: [29.99, 15.0],

      purchased_name: [],
      purchased_price: [],
    };
  },

  created() {
    var purchased_name = [];
    var purchased_price = [];

    $.ajax({
      type: "GET",
      url:
        "http://localhost:80/shopping_mall/api/get_user.php?user_no=" +
        sessionStorage.getItem("user_no"),
      async: false,
      dataType: "json",
      success: function (result) {
        for (var i = 0; i < result.length; i++) {
          purchased_name.push(result[i]['pro_name']);
          purchased_price.push(result[i]['pro_price']);
        }
      },
    });

    this.purchased_name = purchased_name;
    this.purchased_price = purchased_price;

    console.log(this.purchased_name);

    purchased_name = [];
    purchased_price = [];
  },
};
</script>

<template>
  <div id="user_account_container">
    <h1 id="user_info_title">User Information</h1>

    <div id="user_info">
      <div id="user_info_first_name">
        <h2 id="user_info_category">First name</h2>

        <p id="user_info_content">{{ first_name }}</p>
      </div>

      <div id="user_info_last_name">
        <h2 id="user_info_category">Last name</h2>

        <p id="user_info_content">{{ last_name }}</p>
      </div>

      <div id="user_info_email">
        <h2 id="user_info_category">Email</h2>

        <p id="user_info_content">{{ user_email }}</p>
      </div>
    </div>

    <h1 id="history_title">History</h1>

    <div id="history_info">
      <div id="history_purchased">
        <h2 id="history_category">Purchased items</h2>

        <div id="history_content" v-for="i in purchased_name.length" :key="i">
          <p id="history_name">{{ purchased_name[i - 1] }}</p>
          <p id="history_price">${{ purchased_price[i - 1] }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
#user_info_title {
  font-size: 20px;
  font-family: Georgia, "Times New Roman", Times, serif;
}

#user_info,
#history_info {
  display: grid;
  grid-template-columns: 1fr 1fr;

  padding: 20px;

  margin-top: 20px;

  width: 60vw;

  border: 1px solid black;
  box-shadow: 10px 10px;
}

#user_info_category,
#history_category {
  font-size: 20px;
  font-family: Georgia, "Times New Roman", Times, serif;
}

#history_content {
  width: 70%;
  display: flex;
  justify-content: space-between;
}

#user_info_content,
#history_name,
#history_price {
  font-size: 18px;
  font-family: Georgia, "Times New Roman", Times, serif;
}

#history_title {
  margin-top: 70px;
}
</style>
