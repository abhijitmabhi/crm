<template functional>
    <div
        class="StatisticsLegendBox p-4"
        :class="[data.class, data.staticClass]"
        :style="[data.style, data.staticStyle]"
    >
        <slot />
    </div>
</template>

<style>
  .StatisticsLegendBox {
    border-radius: 24px;
    background-color: #eff2f4;
    padding: 40px;
  }
</style>
