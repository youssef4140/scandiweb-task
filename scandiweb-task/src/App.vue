<template>
  <div id="app">
    <div id="content">
      <router-view />
    </div>
    <FooterComponent id="footer" />
  </div>
</template>

<script>
import FooterComponent from "./components/FooterComponent.vue";

export default {
  components: {
    FooterComponent,
  },
};
</script>

<style lang="scss">
@import url("https://fonts.googleapis.com/css2?family=Cabin&display=swap");
$font-color: #2c3e50;
#app {
  font-family: "Cabin", Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: $font-color;
  min-height: 98vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  #content {
    flex-grow: 1;
  }
}

a {
  font-weight: bold;
  color: $font-color;
  text-decoration: none;

  &.router-link-exact-active {
    color: #42b983;
  }
}
.btn {
  border: none;
  outline: none;
  background-color: transparent;
  font-size: 16px;
  color: $font-color;
  font-weight: bold;
  border: 2px solid $font-color;
  text-align: center;
  padding: 5px;
  cursor: pointer;
  box-shadow: 2px 2px 0 2px black;
  transition: all 0.25s ease-in-out;
  &:hover {
    transition: all 0.25s ease-in-out;
    box-shadow: 0 0 0 0 black;
    transform: translate(2px, 2px);
  }
}
</style>
