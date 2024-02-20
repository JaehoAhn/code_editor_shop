<script setup></script>

<template>
  <div id="my_order_container">
    <div id="prev_or_curr_order_nav">
      <h1 class="prev_order_nav">Previous orders</h1>

      <h1 class="curr_order_nav">Current orders</h1>
    </div>

    <div id="my_order_header">
      <h1>Image</h1>

      <h1>Name</h1>

      <h1>Statue</h1>
    </div>

    <div id="prev_orders_container">
      <div id="prev_order">
        <img src="./img/vs_code.jpg" />

        <div class="prev_order_title_date">
          <h1>vs code editor</h1>

          <p>2024.02.05</p>
        </div>

        <h1>Completed</h1>
      </div>

      <div id="prev_order">
        <img src="./img/vs_code.jpg" />

        <div class="prev_order_title_date">
          <h1>vs code editor</h1>

          <p>2024.02.05</p>
        </div>

        <h1>Completed</h1>
      </div>

      <div id="prev_order">
        <img src="./img/vs_code.jpg" />

        <div class="prev_order_title_date">
          <h1>vs code editor</h1>

          <p>2024.02.05</p>
        </div>

        <h1>Completed</h1>
      </div>
    </div>

    <div id="curr_order"></div>
  </div>
</template>

<style>
#my_order_container {
  width: 60vw;
}

#prev_or_curr_order_nav {
  display: flex;
}

#prev_or_curr_order_nav .prev_order_nav {
  margin-bottom: 0;
  
  padding-top: 10px;
  padding-left: 20px;
  padding-right: 20px;
  padding-bottom: 10px;

  border: 2px solid black;
  border-bottom: 0;
}

#prev_or_curr_order_nav .curr_order_nav {
  margin-bottom: 0;

  padding-top: 10px;
  padding-left: 20px;
  padding-right: 20px;
  padding-bottom: 10px;

  /* border: 2px solid black; */
  background-color: black;
  color: white;
}

#my_order_header {
  display: flex;
  justify-content: space-around;

  padding-top: 20px;

  border: 2px solid black;
  border-bottom: 0;
}

#my_order_header h1 {
  font-size: 15px;
  font-family: Georgia, "Times New Roman", Times, serif;
}

#prev_orders_container {
    border: 2px solid black;
    border-top: 0;
}

#prev_order {

  display: flex;
  justify-content: space-around;
  align-items: center;

  padding: 10px;
}

#prev_order img {
  width: 80px;
  height: 80px;
}

#prev_order h1,
#prev_order p {
  font-family: Georgia, "Times New Roman", Times, serif;
  font-size: 18px;
}
</style>
