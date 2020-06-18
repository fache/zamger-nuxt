<template>
  <div>
      <Headline />
      <UrediPredmet />
      <section class="container sekcija" v-bind:class="sidebar">
          <h3>{{nazivPredmeta}} - Raspored</h3>
      </section>
  </div>
</template>
<script>
import Headline from '@/components/Headline'
import UrediPredmet from '@/components/UrediPredmet'
export default {
    data() {
        return {
            predmet:null,
            nazivPredmeta:null
        }
    },
    components: {
    Headline, UrediPredmet
    },
    beforeCreate(){
        //TODO smisliti nacin za redirect u slucaju da je korisnik logiran
        if(this.$store.state.active=="aktivan")console.log(this.$store.state.active);
        else{console.log("neaktivan"); this.$router.push('/login');} 
    },
    beforeMount (){
        this.predmet=this.$route.params.id;
        this.$store.state.predmetZaUredivanje=this.predmet;
        this.$store.state.uredivanjePredmeta="raspored";

        for(let i =0; i<this.$store.state.predmeti.length; i++){
            if(this.predmet==this.$store.state.predmeti[i].id){
                this.nazivPredmeta=this.$store.state.predmeti[i].naziv;
                break;
            }
        }
    },
    mounted (){
    },
    computed: {
      sidebar: function () {
        if(this.$store.state.showSidebar) return "lijeviPaddingSidebar";
        else return "lijeviPadding";
      }
    },
    beforeDestroy(){
      this.$store.state.predmetZaUredivanje=null;
      this.$store.state.uredivanjePredmeta=null;
    }
}
</script>
<style scoped>
</style>