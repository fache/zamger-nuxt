<template>
  <div>
      <Headline />
      <UrediPredmet />
      <section class="container sekcija" v-bind:class="sidebar">
        <h3>{{nazivPredmeta}} - Izvještaji</h3>
        <h4 class="ui horizontal divider header">
            <i class="tasks icon"></i>
            Opcije predmeta
        </h4>
        <div class="ui segment">
            <div class="ui ordered list" style="margin: auto; width: fit-content; max-width: 345px;">
                <a class="item">Sumarni izvještaj za predmet</a>
                <div class="item">
                    <a>Spisak studenata:</a>
                    <div class="list">
                    <a class="item">Bez grupa</a>
                    <a class="item">Jedna kolona po grupama</a>
                    <a class="item">Dvije kolone (za lakše printanje)</a>
                    <a class="item">Sa komentarima na rad</a>
                    <a class="item">Sa poljima za prisustvo</a>
                    </div>
                </div>
                <div class="item">
                    <a>Pregled grupa, prisustva, bodova:</a>
                    <div class="list">
                    <a class="item">Puni izvještaj</a>
                    <a class="item">Sa sumiranim kolonama za prisustvo i zadaće</a>
                    <a class="item">Sa razdvojenim popravnim ispitima</a>
                    </div>
                </div>
                <div class="item">
                    <a>Pregled anketa:</a>
                    <div class="list">
                    <a class="item">Rank pitanja</a>
                    <a class="item">Komentari</a>
                    </div>
                </div>
            </div>
        </div>
      </section>
  </div>
</template>
<script>
import axios from 'axios'

import Headline from '@/components/Headline'
import UrediPredmet from '@/components/UrediPredmet'
export default {
    data() {
        return {
            predmet:null,
            nazivPredmeta:null
        }
    },
    methods: {

    },
    computed: {
      sidebar: function () {
        if(this.$store.state.showSidebar) return "lijeviPaddingSidebar";
        else return "lijeviPadding";
      }
    },
    components: {
    Headline, UrediPredmet
    },
    middleware:'auth',
    beforeCreate(){
        //TODO smisliti nacin za redirect u slucaju da je korisnik logiran
        if(this.$store.state.active=="aktivan")console.log(this.$store.state.active);
        else{console.log("neaktivan"); this.$router.push('/login');} 
    },
    mounted (){
        this.predmet=this.$route.params.id;
        this.$store.state.predmetZaUredivanje=this.predmet;
        this.$store.state.uredivanjePredmeta="izvjestaji";

        for(let i =0; i<this.$store.state.predmeti.length; i++){
            if(this.predmet==this.$store.state.predmeti[i].id){
                this.nazivPredmeta=this.$store.state.predmeti[i].naziv;
                break;
            }
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