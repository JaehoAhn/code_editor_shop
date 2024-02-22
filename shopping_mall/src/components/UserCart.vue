<script>
var data;

export default {
  name: "UserAccount",
  data() {
    return {
      cart_name: [],
      cart_price: [],
      cart_rate: [],
      cart_uploader: [],
    };
  },

  methods: {
    click_purchase_all() {
      var pro_no = [];
      var purch_opt = [];
      var purch_quant = [];

      $.ajax({
        type: "GET",
        url:
          "http://localhost:80/shopping_mall/api/get_user_cart.php?user_no=" +
          sessionStorage.getItem("user_no"),
        async: false,
        dataType: "json",
        success: function (result) {
          for (var i = 0; i < result.length; i++) {
            pro_no.push(result[i]["pro_no"]);
            purch_opt.push(result[i]["purch_opt"]);
            purch_quant.push(result[i]["purch_quant"]);
          }
        },
      });

      var date = new Date();

      //Add to user_purch_tb.
      for (var i = 0; i < pro_no.length; i++) {
        $.ajax({
          type: "GET",
          url:
            "http://localhost:80/shopping_mall/api/add_purchase.php?user_no=" +
            sessionStorage.getItem("user_no") +
            "&pro_no=" +
            pro_no[i] +
            "&curr_date=" +
            date.getFullYear() +
            "/" +
            (date.getMonth() + 1) +
            "/" +
            date.getDate() +
            "&option=" +
            purch_opt[i] +
            "&quant=" +
            purch_quant[i],
          dataType: "json",
          success: function (result) {
            if (result) {
              console.log("success");
            }
          },
        });

        this.cart_name = [];
        this.cart_price = [];
        this.cart_rate = [];
        this.cart_uploader = [];

        $('#alert_cart').css("display","block");

        //Remove items in cart_tb
        $.ajax({
          type: "GET",
          url:
            "http://localhost:80/shopping_mall/api/remove_user_cart.php?user_no=" +
            sessionStorage.getItem("user_no"),
          dataType: "json",
          success: function (result) {
            if (result) {
              console.log("success");
            }
          },
        });
      }
    },
  },

  created() {
    var cart_name = [];
    var cart_price = [];
    var cart_rate = [];
    var cart_uploader = [];
    

    $.ajax({
      type: "GET",
      url:
        "http://localhost:80/shopping_mall/api/get_user_cart.php?user_no=" +
        sessionStorage.getItem("user_no"),
      async: false,
      dataType: "json",
      success: function (result) {
        console.log(result);
        for (var i = 0; i < result.length; i++) {
          cart_name.push(result[i]["pro_name"]);
          cart_price.push(result[i]["pro_price"]);
          cart_rate.push(result[i]["pro_rate"]);
          cart_uploader.push(result[i]["pro_seller"]);
        }
      },
    });

    this.cart_name = cart_name;
    this.cart_price = cart_price;
    this.cart_rate = cart_rate;
    this.cart_uploader = cart_uploader;

    console.log(this.cart_name);
  },
};
</script>

<template>
  <div id="my_cart">
    <h1 id="my_cart_title">My Cart</h1>

    <div id="my_cart_item_cont" class="row">
      <div id="my_cart_header">
        <p id="header_name" class="col">Name</p>
        <p id="header_price" class="col">Price</p>
        <p id="header_rate" class="col">Rate</p>
        <p id="header_seller" class="col">Uploader</p>
      </div>

      <div id="cart_content" class="row" v-for="i in this.cart_name.length" :key="i">
        <p id="cart_name" class="col">{{ this.cart_name[i - 1] }}</p>
        <p id="cart_price" class="col">${{ this.cart_price[i - 1] }}</p>
        <p id="cart_rate" class="col">{{ this.cart_rate[i - 1] }}/5</p>
        <p id="cart_seller" class="col">{{ this.cart_uploader[i - 1] }}</p>
      </div>
    </div>

    <div id="alert_cart" class="alert alert-success" style=" margin-top: 20px; display: none;">
      <strong>Success!</strong> All items are purchased.
    </div>

    <button
      type="button"
      style="margin-top: 40px; float: right"
      class="btn btn-dark"
      v-on:click="click_purchase_all"
    >
      Purchase All
    </button>
  </div>
</template>

<style>
#my_cart_title {
  font-family: Georgia, "Times New Roman", Times, serif;
  font-size: 20px;
}

#my_cart_header {
  display: flex;
  align-items: center;
}

#my_cart_header p {
  font-family: Georgia, "Times New Roman", Times, serif;
  font-size: 20px;

  color: black;
  font-weight: bold;

  margin: 10px;
}

#cart_name,
#cart_price,
#cart_rate,
#cart_seller {
  margin: 10px;
  font-family: Georgia, "Times New Roman", Times, serif;
  font-size: 18px;
}

#my_cart_item_cont {
  margin-top: 20px;

  border: 2px solid black;
  padding: 10px;

  width: 80vw;

  box-shadow: 10px 10px;
}
</style>
