<script>
export default {
  data() {
    return {
      pro_no: 0,
      name: "item name",
      uploader: "microsoft",
      price: 29.99,
      rate_in_percent: (4.5 / 5) * 100,
      description:
        "Contrary to popular belief, Lorem Ipsum is not simply random text.",
      img_url:
        "https://github.com/JaehoAhn/code_editor_shop/assets/82874416/5a2ad460-bf82-4843-a21c-25a398bd452b",
    };
  },

  methods: {
    get_product(n) {
      var curr_item = [];

      $.ajax({
        type: "GET",
        url:
          "http://localhost:80/shopping_mall/api/get_product_detail.php?pro_no=" +
          n,
        async: false,
        dataType: "json",
        success: function (result) {
          curr_item = result;
        },
      });

      this.name = curr_item["pro_name"];
      this.uploader = curr_item["pro_seller"];
      this.price = curr_item["pro_price"];
      this.rate_in_percent = ((curr_item["pro_rate"] / 5) * 100).toString();
      this.description = curr_item["pro_desc"];
      this.img_url = curr_item["pro_img_url"];
    },

    click_purchase() {
      if (sessionStorage.getItem("user_no") == null) {
        window.location = "/login";
      } else {
        const date = new Date();

        var user_no = sessionStorage.getItem("user_no");
        var pro_no = localStorage.getItem("curr_pro_no");
        var curr_date =
          date.getFullYear() +
          "/" +
          (date.getMonth() + 1) +
          "/" +
          date.getDate();
        console.log(curr_date);

        console.log("hi");

        $.ajax({
          type: "GET",
          url:
            "http://localhost:80/shopping_mall/api/add_purchase.php?user_no=" +
            user_no +
            "&pro_no=" +
            pro_no +
            "&curr_date=" +
            curr_date,
          dataType: "json",
          success: function (result) {
            if (result) {
              console.log(result);

              //Open success alert
              $('#success_alert').css('display', 'block');
            }
          },
        });
      }
    },
  },

  created() {
    this.pro_no = localStorage.getItem("curr_pro_no");
    this.get_product(this.pro_no);
  },
};
</script>

<template>
  <div id="desc_container" class="d-flex justify-content-center">
    <div id="desc_info_container">
      <div id="img_btn_purchase">
        <img id="item_img" v-bind:src="img_url" />

        <div id="purchase_buttons">
          <button id="item_add_cart_btn" class="btn btn-dark">
            Add to Cart
          </button>

          <button
            id="item_purchase_btn"
            class="btn btn-dark"
            v-on:click="click_purchase"
          >
            Purchase Now
          </button>
        </div>

        <div id="success_alert" class="alert alert-success" style="width: 500px; margin-top: 20px; display: none;">
          <strong>Success!</strong> Your item is purchased.
        </div>
      </div>

      <div id="item_detail">
        <div
          id="name_uploader"
          class="d-flex justify-content-between align-items-end"
        >
          <h1 class="item_name">{{ this.name }}</h1>
          <p
            style="
              font-family: Georgia, 'Times New Roman', Times, serif;
              font-size: 15px;
            "
          >
            @{{ this.uploader }}
          </p>
        </div>

        <h1
          style="
            font-family: Georgia, 'Times New Roman', Times, serif;
            font-size: 20px;
            margin-top: 20px;
          "
        >
          ${{ this.price }}
        </h1>

        <div id="rate_bar">
          <div class="progress">
            <div
              class="progress-bar bg-secondary"
              :style="{ width: this.rate_in_percent + '%' }"
            >
              {{ this.rate_in_percent }}%
            </div>
          </div>
        </div>

        <div
          style="
            font-family: Georgia, 'Times New Roman', Times, serif;
            font-size: 18px;
            margin-top: 20px;
            line-height: 1.8;
          "
        >
          {{ this.description }}
        </div>
      </div>
    </div>
  </div>
</template>

<style>
#name_uploader {
  display: flex;
}

#desc_info_container {
  display: flex;
  width: 70vw;
}

#item_img {
  width: 500px;
  height: auto;
}

#purchase_buttons {
  display: flex;
  justify-content: space-around;
  width: 500px;
  height: 50px;
  margin-top: 20px;
}

#item_add_cart_btn {
  width: 45%;
}

#item_purchase_btn {
  width: 45%;
}

#item_detail {
  width: 90%;
  margin-left: 30px;
}

#item_detail .item_name {
  font-family: Georgia, "Times New Roman", Times, serif;
  font-size: 30px;
}
</style>
