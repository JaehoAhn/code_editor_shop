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

    click_purchase_now() {
      if (sessionStorage.getItem("user_no") == null) {
        window.location = "/login";
      } else {
        $("#purchaseModal").modal("show");
      }
    },

    click_add_cart() {
      if (sessionStorage.getItem("user_no") == null) {
        window.location = "/login";
      } else {
        $("#cartModal").modal("show");
      }
    },

    click_cart() {
      const date = new Date();

      var user_no = sessionStorage.getItem("user_no");
      var pro_no = localStorage.getItem("curr_pro_no");
      var option = $("#cart_option").find(":selected").val();
      var quant = $("#cart_quantity").val();

      $.ajax({
        type: "GET",
        url:
          "http://localhost:80/shopping_mall/api/add_user_cart.php?user_no=" +
          user_no +
          "&pro_no=" +
          pro_no +
          "&option=" +
          option +
          "&quant=" +
          quant,
        dataType: "json",
        success: function (result) {
          if (result) {
            console.log("success");

            $("#cartModal").modal("hide");

            //Open success alert
            $("#success_alert_cart").css("display", "block");
          }
        },
      });
    },

    click_purchase() {
      const date = new Date();

      var user_no = sessionStorage.getItem("user_no");
      var pro_no = localStorage.getItem("curr_pro_no");
      var curr_date =
        date.getFullYear() + "/" + (date.getMonth() + 1) + "/" + date.getDate();
      var option = $("#purchase_option").find(":selected").val();
      var quant = $("#purchase_quantity").val();

      $.ajax({
        type: "GET",
        url:
          "http://localhost:80/shopping_mall/api/add_purchase.php?user_no=" +
          user_no +
          "&pro_no=" +
          pro_no +
          "&curr_date=" +
          curr_date +
          "&option=" +
          option +
          "&quant=" +
          quant,
        dataType: "json",
        success: function (result) {
          if (result) {
            console.log("success");

            $("#purchaseModal").modal("hide");

            //Open success alert
            $("#success_alert_purchase").css("display", "block");
          }
        },
      });
    },
  },

  created() {
    this.pro_no = localStorage.getItem("curr_pro_no");
    this.get_product(this.pro_no);
  },
};
</script>

<template>
  <div
    id="desc_container"
    class="d-flex justify-content-center"
    style="margin-top: 40px"
  >
    <div id="desc_info_container">
      <div id="img_btn_purchase">
        <img id="item_img" v-bind:src="img_url" />

        <div id="purchase_buttons">
          <button
            id="item_add_cart_btn"
            class="btn btn-dark"
            v-on:click="click_add_cart"
          >
            Add to Cart
          </button>

          <button
            id="item_purchase_btn"
            class="btn btn-dark"
            v-on:click="click_purchase_now"
          >
            Purchase Now
          </button>
        </div>

        <div
          id="success_alert_purchase"
          class="alert alert-success"
          style="width: 500px; margin-top: 20px; display: none"
        >
          <strong>Success!</strong> Your item is purchased.
        </div>

        <div
          id="success_alert_cart"
          class="alert alert-success"
          style="width: 500px; margin-top: 20px; display: none"
        >
          <strong>Success!</strong> Your item is in your cart.
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

  <!-- The Modal - Purchase Now -->
  <div class="modal" id="purchaseModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Option & Quantitiy</h4>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
          ></button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          <div style="display: flex">
            <h1>Options</h1>
            <select
              id="purchase_option"
              class="form-select"
              style="width: 50%; margin-left: 25px"
            >
              <option>Option 1</option>
              <option>Option 2</option>
              <option>Option 3</option>
              <option>Option 4</option>
            </select>
          </div>

          <div style="display: flex; margin-top: 20px">
            <h1>Quantitiy</h1>
            <input
              id="purchase_quantity"
              type="number"
              class="form-control"
              style="width: 50%"
            />
          </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-dark"
            data-bs-dismiss="modal"
            v-on:click="click_purchase"
          >
            Purchase
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- The Modal - Purchase Now -->
  <div class="modal" id="cartModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Option & Quantitiy</h4>
          <button
            type="button"
            class="btn-close"
            data-bs-dismiss="modal"
          ></button>
        </div>

        <!-- Modal body Add Cart-->
        <div class="modal-body">
          <div style="display: flex">
            <h1>Options</h1>
            <select
              id="cart_option"
              class="form-select"
              style="width: 50%; margin-left: 25px"
            >
              <option>Option 1</option>
              <option>Option 2</option>
              <option>Option 3</option>
              <option>Option 4</option>
            </select>
          </div>

          <div style="display: flex; margin-top: 20px">
            <h1>Quantitiy</h1>
            <input
              id="cart_quantity"
              type="number"
              class="form-control"
              style="width: 50%"
            />
          </div>
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-dark"
            data-bs-dismiss="modal"
            v-on:click="click_cart"
          >
            Add to Cart
          </button>
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
