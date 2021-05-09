<template>
    <b-card>
        <h2>Local Search Index: {{ percentage }}%</h2>
<!--        <img src="/img/percentage.jpg" class="LSI_PERCENTAGE">-->
        <div class="LSI_LSBontainer">
              <div v-for="(part, i) in completed" :key="`${part}_${i}`">
                <div class="LSI_PART" v-bind:class="part.part"></div>
              </div>
        </div>

        <div class="LSI_LSBontainer hideBG marginTop10">
              <div v-for="(part, i) in completed" :key="`${part}_${i}`">
                <div class="LSI_PART hideBG" v-bind:class="part.part">{{ part.name }} </div>
              </div>
        </div>


    </b-card>
</template>



<script>
export default {
    data() {
        return {
            completed: [
                { name: "Local Link Interface", part: "LSI_LLI"},
                { name: "Local Lead Boost", part: "LSI_LLB" },
                { name: "Local Perfomance Page", part: "LSI_LPP"},
                { name: "Local Sales Boost", part: "LSI_LSB"},
            ],
            percentage: '100',
        };
    },
};
</script>
<style>

  .LSI_PERCENTAGE{
    width:100%;
  }

  .LSI_LSBontainer{
      position:relative;
      height:20px;
      background:#f0f1f5;
      border:0px solid white;
      border-radius: 10px;
      overflow: hidden;
  }

  .LSI_PART{
    float:left;
    height:20px;
  }

  .LSI_LLI{
    background-image: linear-gradient(to right, #fe0265 , #f54643);
    width:25%;
  }

  .LSI_LLB{
    background-image: linear-gradient(to right, #f89865 , #ff5d00);
    width:25%;
  }

  .LSI_LPP{
    background-image: linear-gradient(to right, #fba43d , #f5f245);
    width:25%;
  }

  .LSI_LSB{
    background-image: linear-gradient(to right, #64e564 , #81f03c);
    width:25%;
  }

  .hideBG{
    background:none !important;
    font-size: 0.78rem;
    text-align:center;
    font-weight:bold;
  }
  .marginTop10{
    margin-top:10px;
  }

  .inactive{
    display:none;
  }

</style>
