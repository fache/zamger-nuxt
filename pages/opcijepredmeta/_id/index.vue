<template>
  <div>
      <Headline />
      <UrediPredmet />
      <section class="container sekcija" v-bind:class="sidebar">
        <h3>{{nazivPredmeta}} - Opcije predmeta</h3>
        <h4 class="ui horizontal divider header">
            <i class="id card outline icon"></i>
            Pristup predmetu
        </h4>
        <div>Pristup predmetu imaju sljedeći nastavnici i saradnici (slovo N označava da saradnik ima privilegije nastavnika, a slovo S da ima privilegije "super-asistenta"):</div>
        
        <h4 class="ui horizontal divider header">
            <i class="tasks icon"></i>
            Opcije predmeta
        </h4>
        <div class="ui segment">
        <div>Izaberite opcije koje želite da učinite dostupnim studentima:</div>
            <div class="checkboxPadding">
                <div class="ui toggle checkbox" v-on:click="promijeniMaterijaliOpciju">
                    <input id="materijali" type="checkbox" name="public">
                    <label>Materijali (Moodle) </label>
                </div>
            </div>
            <div class="checkboxPadding">
                <div class="ui toggle checkbox" v-on:click="promijeniSlanjeZadaceOpciju">
                    <input id="slanjezadace" type="checkbox" name="public">
                    <label>Slanje zadaće </label>
                </div>
            </div>
            <div class="checkboxPadding">
                <div class="ui toggle checkbox" v-on:click="promijeniDnevnikOpciju">
                    <input id="dnevnik" type="checkbox" name="public">
                    <label>Dnevnik </label>
                </div>
            </div>
            <div class="checkboxPadding">
                <div class="ui toggle checkbox" v-on:click="promijeniProjektiOpciju">
                    <input id="projekti" type="checkbox" name="public">
                    <label>Projekti </label>
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
            nazivPredmeta:null,
            // todo
            materijali:true,
            slanjezadace:false,
            dnevnik:false,
            projekti:false

        }
    },
    middleware:'auth',
    methods: {
        promijeniMaterijaliOpciju: function () {
            this.materijali=!this.materijali;
        },
        promijeniSlanjeZadaceOpciju: function () {
            this.slanjezadace=!this.slanjezadace;
        },
        promijeniDnevnikOpciju: function () {
            this.dnevnik=!this.dnevnik;
        },
        promijeniProjektiOpciju: function () {
            this.projekti=!this.projekti;
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
    middleware:'auth',
    beforeCreate(){
        //TODO smisliti nacin za redirect u slucaju da je korisnik logiran
        if(this.$store.state.active=="aktivan")console.log(this.$store.state.active);
        else{console.log("neaktivan"); this.$router.push('/login');} 
    },
    mounted (){
        this.predmet=this.$route.params.id;
        this.$store.state.predmetZaUredivanje=this.predmet;
        this.$store.state.uredivanjePredmeta="opcijepredmeta";

        for(let i =0; i<this.$store.state.predmeti.length; i++){
            if(this.predmet==this.$store.state.predmeti[i].id){
                this.nazivPredmeta=this.$store.state.predmeti[i].naziv;
                break;
            }
        }

        //TODO
        document.getElementById("materijali").checked=true;
        this.materijali=true;

        document.getElementById("slanjezadace").checked=false;
        this.slanjezadace=false;

        document.getElementById("dnevnik").checked=false;
        this.dnevnik=false;

        document.getElementById("projekti").checked=false;
        this.projekti=false;

    },
    beforeDestroy(){
      this.$store.state.predmetZaUredivanje=null;
      this.$store.state.uredivanjePredmeta=null;
    }
}
</script>
<style scoped>
.checkboxPadding{
    padding-top:10px;
}
</style>