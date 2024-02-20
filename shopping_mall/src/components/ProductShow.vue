<script setup>


</script>


<script>
export default {
  name: "ProductShow",

  props: ["product_name", "uploader", "price", "rate", "url"],

  data() {
    return {
      test : "#",

    };
  },

  methods: {
    get_all_products() {
        console.log("hello")
      $.ajax({
        type: "GET",
        url: "http://localhost:80/shopping_mall/api/get_product.php",
        async: false,
        dataType: "json",
        success: function (result) {
            console.log(result);
        },
      });
    },
  },

};

</script>

<template>
  <div id="item_container">
    <div
      id="header"
      class="d-flex justify-content-around d-flex align-items-end"
    >
      <h3>
        {{ product_name }}
      </h3>

      <p>@{{ uploader }}</p>
    </div>

    <img v-on:click="get_all_products()" id="sample_img" :src="url"/>

    <div id="info" class="d-flex justify-content-around">
      <div id="extra_info">
        <p>Price</p>

        <h4>${{ price }}</h4>
      </div>

      <div id="extra_info">
        <p>Rating</p>

        <h4>{{ rate }}/5.0</h4>
      </div>
    </div>
  </div>
</template>

<style>
#item_container {
  background-color: #f0f0f0;
  width: 280px;
  height: 370px;
  display: block;

  text-align: center;
}

#header {
  height: 15%;
  width: 100%;
}

#header h3 {
  font-size: 15px;
  font-family: Georgia, "Times New Roman", Times, serif;
  font-weight: bold;
}

#sample_img {
  width: 230px;
  height: 230px;

  margin: 0;
}

#info {
  margin: 0;
  margin-top: 20px;

  display: flex;
}

#info #extra_info {
  text-align: left;
}

#extra_info p {
  margin: 0;
  font-family: Georgia, "Times New Roman", Times, serif;
}

#extra_info h4 {
  margin: 0;

  font-size: 20px;
  font-family: Georgia, "Times New Roman", Times, serif;
}
</style>
