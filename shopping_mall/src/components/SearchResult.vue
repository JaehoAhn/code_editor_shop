<script>
export default {
  name: "SearchResult",
  data() {
    return {
      user_search: "*",
      num_result: 0,
    };
  },

  created() {
    var num;

    $.ajax({
      type: "GET",
      url:
        "http://localhost:80/shopping_mall/api/get_product.php?searchInput=" +
        "" +
        "&side_level_rate=" +
        "" +
        "&side_level_price=" +
        "",
      async: false,
      dataType: "json",
      success: function (result) {
        num = result.length;
      },
    });

    this.num_result = num;
  },

  methods: {
    set_user_search(s) {
      this.user_search = s;
    },

    set_num_result(n) {
      this.num_result = n;
    },
  },
};
</script>

<template>
  <div
    id="search_container"
    class="d-flex align-items-baseline d-flex justify-content-around"
  >
    <h1>Search result for "{{ user_search }}"</h1>

    <p v-on:click="update()">{{ num_result }} results</p>
  </div>
</template>

<style>
#search_container {
  margin-top: 20px;
}

h1 {
  font-size: 20px;
  font-family: Georgia, "Times New Roman", Times, serif;
  color: #151515;
}

p {
  font-size: 13px;
  font-family: Georgia, "Times New Roman", Times, serif;
  color: #151515;
  opacity: 70%;
  /* margin-top: 5px; */
}
</style>
