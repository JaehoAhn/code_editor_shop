
<script>
import ProductShow from "./ProductShow.vue";
import SideBar from "./SideBar.vue";

export default {
  name: "IndexPage",

  components: {
    ProductShow,
    SideBar,
  },

  data() {
    return {
      product_no: [],
      product_name: [],
      uploader: [],
      price: [],
      rate: [],
      img_url: [],
    };
  },
  methods: {
    get_products_list() {
      var searchInput = $("#search_box_input").val();
      var side_level_rate = $("input[name='level_rate']:checked").val();
      var side_level_price = $("input[name='level_price']:checked").val();

      var data;

      $.ajax({
        type: "GET",
        url: "http://localhost:80/shopping_mall/api/get_product.php?searchInput=" + searchInput + "&side_level_rate=" + side_level_rate + "&side_level_price=" + side_level_price,
        async: false,
        dataType: "json",
        success: function (result) {
          data = result;
        },
      });

      this.product_no = [];
      this.product_name = [];
      this.uploader = [];
      this.price = [];
      this.rate = [];
      this.img_url = [];

      for (var i = 0; i < data.length; i++) {
        this.product_no.push(data[i]["pro_no"]);
        this.product_name.push(data[i]["pro_name"]);
        this.uploader.push(data[i]["pro_seller"]);
        this.price.push(data[i]["pro_price"]);
        this.rate.push(data[i]["pro_rate"]);
        this.img_url.push(data[i]["pro_img_url"]);
      }


    },

    click_product_show(n) {
      localStorage.setItem("curr_pro_no", n);
      window.location = '/shop_detail'
    },

    get_num_data() {
      return this.product_no.length;
    }

    
  },
  
  created() {
    this.get_products_list();
  }
};
</script>

<template>
  <div id="product_container" class="d-flex justify-content-center">
    <div id="side_container">
      <SideBar @call_product_index="get_products_list"></SideBar>
    </div>

    <div id="prods_container">
      <div id="products">
        <!-- 수정 -->
        <ProductShow
          v-for="i in product_no.length"
          :key="i"
          :product_name="product_name[i - 1]"
          :uploader="uploader[i - 1]"
          :price="price[i - 1]"
          :rate="rate[i - 1]"
          :url="img_url[i - 1]"
          v-on:click="this.click_product_show(product_no[i - 1])"
        ></ProductShow>
      </div>
    </div>
  </div>
</template>
<style>
#side_container {
  margin-left: 5vw;
}

#prods_container {
  /* border: 2px solid blue; */
  height: auto;
}

#products {
  /* display: flex;
        flex-wrap: wrap; */
  width: 60vw;
  margin-left: 5vw;

  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  row-gap: 1em;
}
</style>
