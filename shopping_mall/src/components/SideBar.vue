<script>
export default {
  name: "SideBar",
  data() {
    return {};
  },

  methods: {
    click_level() {
        console.log("hi")
      this.$emit("call_product_index");
    },
  },
};
</script>

<template>
  <div id="sidebar_container">
    <div id="filt_rate">
      <h3>Sort by rate</h3>

      <div id="level">
        <div
          id="check_boxes"
          class="d-flex align-items-center justify-content-between"
        >
          <label for="low_to_high">Low to high</label>
          <input
            type="radio"
            id="low_to_high"
            name="level_rate"
            value="low_high_rate"
            v-on:click="click_level()"
          />
        </div>

        <div
          id="check_boxes"
          class="d-flex align-items-center justify-content-between"
        >
          <label for="high_to_low">High to low</label>
          <input
            type="radio"
            id="high_to_low"
            name="level_rate"
            value="high_low_rate"
            v-on:click="click_level()"
          />
        </div>
      </div>
    </div>

    <div id="filt_price">
      <h3>Sort by price</h3>

      <div id="level">
        <div
          id="check_boxes"
          class="d-flex align-items-center justify-content-between"
        >
          <label for="low_to_high">Low to high</label>
          <input
            type="radio"
            id="low_to_high"
            name="level_price"
            value="low_high_price"
            v-on:click="click_level()"
          />
        </div>

        <div
          id="check_boxes"
          class="d-flex align-items-center justify-content-between"
        >
          <label for="high_to_low">High to low</label>
          <input
            type="radio"
            id="high_to_low"
            name="level_price"
            value="high_low_price"
            v-on:click="click_level()"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<style>
#sidebar_container {
  width: 15vw;
}

#filt_rate h3,
#filt_price h3 {
  font-size: 20px;
  font-family: Georgia, "Times New Roman", Times, serif;
}

#filt_price {
  margin-top: 60px;
}

#check_boxes {
  font-size: 15px;
  font-family: Georgia, "Times New Roman", Times, serif;
}

#low_to_high,
#high_to_low,
#discounted {
  width: 15px;
  margin-right: 15px;
  /* grid-column-start: 2; */
}
</style>
