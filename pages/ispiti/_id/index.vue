<template>
  <div>
      <Headline />
      <UrediPredmet />
      <section class="container sekcija" v-bind:class="sidebar">
        <h3>{{nazivPredmeta}} - Ispiti</h3>

        <h4 class="ui horizontal divider header">
            <i class="clipboard outline icon"></i>
            Pregled ispita
        </h4>
        <h4 class="ui horizontal divider header">
            <i class="edit outline icon"></i>
            Dodavanje novog ispita
        </h4>

        <div class="ui form">
            <div class="ui segment">
                <div class="two fields">
                    <div class="four wide field">
                        <label>Tip ispita:</label>
                        <select id="tipIspita" class="ui fluid search dropdown">
                            <option value="1">I parcijalni</option>
                            <option value="2">II parcijalni</option>
                            <option value="3">Integralni</option>
                            <option value="4">Usmeni</option>
                        </select>
                    </div> 
                    <div class="four wide field">
                    </div>
                    <div class="field">
                        <label>Datum:</label>
                        <!-- <input data-toggle="datepicker">
                        <textarea data-toggle="datepicker"></textarea>
                        <div data-toggle="datepicker"></div> -->
                    </div> 
                </div>
                <button class="ui primary button" type="submit">Dodaj</button>
            </div>
        </div>
      </section>
  </div>
</template>
<script>

import Headline from '@/components/Headline'
import UrediPredmet from '@/components/UrediPredmet'
import axios from 'axios'
export default {
    head () {
        return {
            script: [
                // { src: 'datepicker.js' }
            ],
            link: [
                // { rel: 'stylesheet', href: 'datepicker.css' }
            ]
        }
    },
    data() {
        return {
            predmet:null,
            nazivPredmeta:null,
            predmetObjekat:null,
            ispiti:[],
            ispitiInfo:[]
        }
    },
    methods:{
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
    beforeMount (){
        this.predmet=this.$route.params.id;
        this.$store.state.predmetZaUredivanje=this.predmet;
        this.$store.state.uredivanjePredmeta="ispiti";

        for(let i =0; i<this.$store.state.predmeti.length; i++){
            if(this.predmet==this.$store.state.predmeti[i].id){
                this.nazivPredmeta=this.$store.state.predmeti[i].naziv;
                this.predmetObjekat=this.$store.state.predmeti[i];
                break;
            }
        }
    },
    mounted(){
        // $('[data-toggle="datepicker"]').datepicker();
        axios.get(process.env.baseUrl+'/exam/course/'+this.predmet, { withCredentials: true }).then((res) => {
            for(let i=0; i<res.data.results.length; i++){
                var ispit={};
                ispit.id=res.data.results[i].id;
                ispit.godina=res.data.results[i].AcademicYear.id;
                this.ispiti.push(ispit);
            }
            for(let i=0; i<this.ispiti.length; i++){
                let kursId=this.ispiti[i].id;
                let godinaId=this.ispiti[i].godina;
                axios.get(process.env.baseUrl+'/exam/course/'+kursId+'/'+godinaId, { withCredentials: true }).then((res2) => {
                    var info={};
                    this.ispitiInfo.push();
                }).catch((err, res2) => {
                    console.log(err);
                });
            }
            
        }).catch((err, res) => {
            console.log(err);
        });
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