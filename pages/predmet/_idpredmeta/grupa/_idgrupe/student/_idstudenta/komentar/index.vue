<template>
  <div>
      <Poruka />
      <Headline />
      <Navigacija />
      <section class="container sekcija" v-bind:class="sidebar" style="overflow: auto;">
        <h3>{{nazivPredmeta}} - Grupa: {{nazivGrupe}}</h3>
        <h3>Student: {{imeIPrezime}} -  Komentari</h3>
        <h4 class="ui horizontal divider header">
            <i class="clipboard outline icon"></i>
            Prethodni komentari
        </h4>
        <h4 class="ui horizontal divider header">
            <i class="edit outline icon"></i>
            Novi komentar
        </h4>
        <div class="ui form">
            <div class="ui segment">
              <div class="two fields">
                <div class="field">
                  <p>Trenutni datum i vrijeme</p>
                  <input type="text" id="vrijeme" placeholder="Unesite vrijeme časa" data-input> 
                  <br>
                  <br>
                  <p>Komentar</p>
                  <textarea id="kom" rows="2"></textarea>
                  <br>
                  <br>
                  <button class="medium ui primary button" v-on:click="posaljiKomentar()">
                    Posalji komentar
                  </button>
                </div>
                <div class="field"></div>
              </div>
          </div>
        </div>
      </section>
  </div>
</template>
<script>
import Headline from '@/components/Headline'
import Navigacija from '@/components/Navigacija'
import Poruka from '@/components/Poruka'
import axios from 'axios'
export default {
  head () {
        return {
            script: [
            ],
            link: [
            ]
        }
    },
    data() {
      return {
        grupa:null,
        // svistudenti:false,
        predmetId:null,
        nazivPredmeta:null,
        nazivGrupe:null,
        studentiUGrupi:[],
        ispitiPredmeta:[],
        zadacePredmeta:[],
        prisustva:[],
        prikaziSveIpite:true,
        prikaziSveZadace:true,
        prikaziSveCasove:true,
        sviPrisutni:false,
        imeIPrezime:""
      }
    },
    components: {
      Headline, Navigacija, Poruka
    },
    middleware:'auth',
    beforeCreate(){
    },
    mounted (){
      document.getElementById("vrijeme").flatpickr({
        enableTime: true,
        time_24hr: true,
        dateFormat: "d.m.Y H:i",
        locale: {
          firstDayOfWeek: 1,
          weekdays: {
            shorthand: ["Ned", "Pon", "Uto", "Sri", "Čet", "Pet", "Sub"]    
          },
          months: {
            shorthand: ["Jan", "Feb", "Mar", "Apr", "Maj", "Jun", "Jul", "Avg", "Sep", "Okt", "Nov", "Dec"],
            longhand: [
              "Januar", "Februar", "Mart", "April", "Maj", "Juni", "Juli", "Avgust", "Septembar", "Oktobar", "Novembar", "Decembar"]
          }
        }
      });
      for(let i =0; i<this.$store.state.predmeti.length; i++){
        if(this.$route.params.idpredmeta==this.$store.state.predmeti[i].id){
          this.nazivPredmeta=this.$store.state.predmeti[i].naziv;
          for(let j =0; j<this.$store.state.predmeti[i].grupe.length; j++){
            if(this.$route.params.idgrupe==this.$store.state.predmeti[i].grupe[j].id){
              this.nazivGrupe=this.$store.state.predmeti[i].grupe[j].nazivGrupe;
              break;
            }
          }
          break;
        }
      }
      var obj=this;
      axios.get(process.env.baseUrl+'/person/'+this.$route.params.idstudenta, { withCredentials: true }).then((resPerson) => {
        obj.imeIPrezime=resPerson.data.name+" "+resPerson.data.surname;
      });
      
      this.prikaziPoruku("TODO: prikaz i update komentara", 4000);

      // axios.get(process.env.baseUrl+'/comment/group/'+obj.$route.params.idgrupe+'/student/'+this.$route.params.idstudenta, { withCredentials: true }).then((resKomentari) => {

      // }).catch((err, resKomentari) => {
      //     console.log(err);
      // });
      
                            
    },
    computed: {
      sidebar: function () {
        if(this.$store.state.showSidebar) return "lijeviPaddingSidebar";
        else return "lijeviPadding";
      }
    },
    methods:{
      posaljiKomentar(){
        this.prikaziPoruku("Akciju trenutno nije moguce izvrsiti");
      },
      datumDanIMjesec(milisekunde){
        if(milisekunde==null) return "-";
        let dateObject = new Date(milisekunde*1000);
        return dateObject.toLocaleString("en-US", {day: "numeric"}) + ". " + dateObject.toLocaleString("en-US", {month: "numeric"})+ ".";
      },
      prikaziPoruku(textPoruke){
        $("#poruka").show();
        $("#poruka").text(textPoruke);
        setTimeout(() => {
          $("#poruka").hide();
        }, 2500);
      }

    },
    beforeDestroy(){
      this.$store.state.aktivnaGrupa=null;
    }
}
</script>
<style scoped>
#vrijeme{
  height: 34px;
  border: 1px solid grey;
  border-radius: 5px;
  padding: 15px;
}
</style>