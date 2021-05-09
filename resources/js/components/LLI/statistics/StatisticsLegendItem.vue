<template>
  <div class="d-flex justify-content-start align-items-center" v-bind:class="{ ItemContainer: !isLast }">
    <div class="ColorIndicator" :style="background"/>
    <div class="ItemText">
      <slot />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    gradientColorStart: {
      type: String
    },
    gradientColorEnd: {
      type: String
    },
    isLast: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    background() {
      return {
        'background-image': 'linear-gradient(to top, ' + this.gradientColorStart + ', ' + this.gradientColorEnd + ')'
      }
    }
  }
}
</script>

<style>
.ItemContainer {
  margin-bottom: 40px;
}

.ColorIndicator {
  width: 20px;
  height: 10px;
  border-radius: 5px;
  margin-right: 20px;
}

.ItemText {
  font-size: 16px;
  font-weight: bold;
  color: #34373a;
}
</style>
