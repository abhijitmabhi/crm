<template>
  <b-card>
    <div class="d-flex justify-content-between align-items-center mb-2">
      <h3 class="box-header lli-card-title">Ranking nach Keyword</h3>
    </div>
    <b-row>
      <b-col cols="6" v-for="(box, i) in boxes" :key="`box_${i}`">
        <div class="d-flex flex-column">
          <div class="d-flex" :style="`background: linear-gradient(90deg, ${box.color1}, ${box.color2} 100%)`"
               style="height:180px;border-radius: 5px;width:100%;margin-bottom:40px">
            <div style="margin:auto">
              <div style="float: left;margin-top: 15px;margin-right: 15px;">
                <span class="border border-light rounded-circle p-2 box-circle-small">
                  <i v-if="'+' === box.change" class="fas fa-arrow-up box-change"></i>
                  <i v-else-if="'-' === box.change" class="fas fa-arrow-down box-change"></i>
                  <i v-else class="fas fa-circle fa-xs box-change-circle"/>
                </span>
              </div>
              <span class="box-texts" v-if="box.change" v-text="box.change"/>
              <span class="box-texts" v-text="box.value"/>
              <span class="box-texts" v-if="'Citations' === box.title">%</span>
              <div style="margin-top:50px;text-align:center;margin-bottom:-20px">
                <span class="box-title" v-text="box.title"/>
              </div>
            </div>
            <div>
              <span class="border border-light rounded-circle p-2 box-circle-big">
                <i v-if="'+' === box.change" class="fas fa-arrow-up box-change-big"></i>
                <i v-else-if="'-' === box.change" class="fas fa-arrow-down box-change-big"></i>
                <i v-else class="fas fa-circle fa-xs box-change-big box-change-big-circle"/>
              </span>
            </div>
          </div>
          <p></p>
        </div>
      </b-col>
    </b-row>
  </b-card>
</template>

<script>

export default {
  data() {
    return {
      boxes: [
        {
          title: "Ranking",
          value: 2.7,
          color1: "#3df5f3",
          color2: "#3df575",
          change: "+",
        },
        {
          title: "Bewertungen",
          value: 1.3,
          color1: "#f54646",
          color2: "#ff0066",
          change: "+",
        },
        {
          title: "Nutzeraktionen",
          value: 47,
          color1: "#f5f33d",
          color2: "#f5ad46",
          change: "•",
        },
        {
          title: "Citations",
          value: 2,
          color1: "#3da9f5",
          color2: "#683df5",
          change: "-",
        },
      ],
    };
  },
  props: {
    companyId: {
      default: 0,
    },
    location: {
      default() {
        return {
          id: 0,
          name: "Asdf",
        };
      },
    },
  },
  methods: {}

};
</script>

<style scoped>
.ball {
  width: 4px;
  background: #fff;
}

.box-texts {
  color: white;
  font-size: 34px;
  font-weight: 600
}

.box-change {
  color: white;
  padding-right: 3px;
  padding-left: 4px;
}

.box-change-big {
  color: white;
  padding-right: 3px;
  padding-left: 4px;
  font-size: 120px;
}

.box-circle-big {
  border: 10px solid #ffffff !important;
  opacity: 0.2;
  padding: 20px !important;
  float: right;
  position: absolute;
  margin-top: 50px;
  margin-left: -120px;
}

.box-circle-small {
  border: 3px solid #ffffff !important;
  padding: 2px !important
}

.box-title {
  color: white;
  font-size: 18px;
  font-weight: 500
}

.box-change-circle {
  color: white;
  padding: 4px;
}

.box {
  height: 180px;
  border-radius: 5px;
  width: 345px;
  margin-bottom: 40px
}

.box-change-big-circle {
  padding: 40px !important;
  font-size: 50px !important;
}

.box-header {
  margin-bottom: 50px !important;
}
</style>

