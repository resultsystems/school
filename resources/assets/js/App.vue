<template>
  <div id="app">
    <div class="container">
      <h1>{{ msg }}</h1>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      msg: 'Hello Vue!!!!'
    }
  },
  created: function(){
    Materialize.toast('Hello World', 1000) 
  }
}
</script>