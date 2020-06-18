<template>
  <div id="headline">
    <div style="float:right; margin-right:20px;">
      <h2 style="color:white; margin-bottom:0; text-align:right; line-height: 30px;">Zamger</h2>
      <div style="text-align:right;">
        <a href="https://zamger.etf.unsa.ba/static/doc/zamger-uputstva-42-nastavnik.pdf" target="_blank"><i class="download icon"></i>Uputstva</a>
        <a href="https://zamger.etf.unsa.ba/static/doc/zamger-uputstva-42-nastavnik.pdf" target="_blank"><i class="bug icon"></i>Prijavite bug</a>
      </div>
    </div>
    <img id="etfIkona" src="../assets/etf-50x50.png" class="image" />
  </div>
</template>
<script>
  export default {

  };
</script>
<style scoped>
  #headline {
    background-color: #BBBBFF;
    width:100%;
    height:50px;
  }
  #etfIkona{
    float: right; 
    padding-right: 10px;
  }
  @media screen and (max-width: 600px) {
    #etfIkona{
      position: absolute;
      left: 0;
      right: 0;
      margin: auto;
      padding: 2px;
      width: 36px;
    }
  }
</style>
