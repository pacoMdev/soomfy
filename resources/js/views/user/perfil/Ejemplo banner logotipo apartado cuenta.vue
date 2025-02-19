<template>
  <div class="apartado">
    <div class="banner" @click="onBannerClick">Banner</div>
    <div class="logo" @click="onLogoClick">Logo</div>
  </div>
</template>

<script>
export default {
  name: "CustomComponent",
  methods: {
    onBannerClick() {
      alert("Banner clicked!");
    },
    onLogoClick() {
      alert("Logo clicked!");
    },
  },
};
</script>

<style scoped>
.apartado {
  width: 100%;
  position: relative;
}
.banner {
  width: 100%;
  height: 200px;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  border: 1px solid black;
}
.logo {
  background-color: white;
  cursor: pointer;
  position: absolute;
  top: 90px;
  left: 200px;
  width: 200px;
  height: 200px;
  border-radius: 1000px;
  display: flex;
  justify-content: center;
  align-items: center;
  border: 3px solid red;
}
</style>