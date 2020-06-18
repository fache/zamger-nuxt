<template>
  <div>
      <Headline />
      <UrediPredmet />
      <section class="container sekcija" v-bind:class="sidebar">
        <h3>{{nazivPredmeta}} - Obavještenja za studente</h3>

        <h4 class="ui horizontal divider header">
            <i class="clipboard outline icon"></i>
            Prethodna obavještenja
        </h4>
        <h4 class="ui horizontal divider header">
            <i class="edit outline icon"></i>
            Unos novog obavještenja
        </h4>

        <div class="ui form">
            <div class="ui segment">
                <div class="two fields">
                    <div class="field">
                        <select id="izborGrupe" class="ui fluid search dropdown" v-model="izborGrupe">
                            <option value="0">Sve grupe</option>
                            <option v-for="grupa in predmetObjekat.grupe" v-bind:key="grupa.id" value="grupa.id">{{grupa.nazivGrupe}}</option>
                        </select>
                    </div>            
                    <div class="field">
                        <div class="ui toggle checkbox">
                            <input id="email" type="checkbox" name="gift" tabindex="0">
                            <label>Slanje e-maila</label>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>Kraći tekst (2-3 rečenice):</label>
                    <textarea id="kratkiTekst" rows="2" v-model="kraciTekst"></textarea>
                </div>
                <div class="field">
                    <label>Detaljan tekst (nije obavezan):</label>
                    <textarea id="detaljniTekst" rows="4" v-model="duziTekst"></textarea>
                </div>
                <button class="ui primary button" type="submit">Pošalji</button>
                <button class="ui button" type="submit" v-on:click="ponistiFormu">Poništi</button>
            </div>
        </div>
      </section>
  </div>
</template>
<script>

import Headline from '@/components/Headline'
import UrediPredmet from '@/components/UrediPredmet'
export default {
    head () {
        return {
            script: [
            ],
        }
    },
    middleware:'auth',
    data() {
        return {
            predmet:null,
            nazivPredmeta:null,
            predmetObjekat:null,
            izborGrupe:0,
            kraciTekst:"",
            duziTekst:""
        }
    },
    methods:{
        ponistiFormu: function(){
            this.izborGrupe=0;
            this.kraciTekst="";
            this.duziTekst="";
            document.getElementById('email').checked=false;
        } 
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
    beforeCreate(){
        console.log('5');
        //TODO smisliti nacin za redirect u slucaju da je korisnik logiran
        if(this.$store.state.active=="aktivan")console.log(this.$store.state.active);
        else{console.log("neaktivan"); this.$router.push('/login');} 
    },
    beforeMount (){
        this.predmet=this.$route.params.id;
        this.$store.state.predmetZaUredivanje=this.predmet;
        this.$store.state.uredivanjePredmeta="obavjestenja";

        for(let i =0; i<this.$store.state.predmeti.length; i++){
            if(this.predmet==this.$store.state.predmeti[i].id){
                this.nazivPredmeta=this.$store.state.predmeti[i].naziv;
                this.predmetObjekat=this.$store.state.predmeti[i];
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