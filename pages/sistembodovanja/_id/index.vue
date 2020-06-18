<template>
  <div> 
      <Headline />
      <UrediPredmet />
      <section class="container sekcija" v-bind:class="sidebar">
        <h3>{{nazivPredmeta}} - Sistem bodovanja</h3>
        <h4 class="ui horizontal divider header">
            <i class="id card outline icon"></i>
            Promijenite sistem bodovanja
        </h4>
        <div class="ui segment">
            Promjenom sistema bodovanja na predmetu gube se svi pohranjeni podaci o ispitima, zadaćama i sl.
        </div>
        <h4 class="ui horizontal divider header">
            <i class="tasks icon"></i>
            Trenutno definisane komponente na predmetu:
        </h4>
        <div class="ui segment">
            <table class="ui celled collapsing structured table" style="margin:auto;">
                <thead>
                    <tr>
                        <th>Naziv</th>
                        <th>Komponente</th>
                        <th>Max. bodova</th>
                        <th>Prolaz</th>
                        <th>Uslov</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="7">
                            Bologna standard
                        </td>
                        <td>I parcijalni</td>
                        <td>20</td>
                        <td>10</td>
                        <td>Ne</td>
                    </tr>
                    <tr>
                        <td>II parcijalni</td>
                        <td>20</td>
                        <td>10</td>
                        <td>Ne</td>
                    </tr>
                    <tr>
                        <td>Integralni</td>
                        <td>40</td>
                        <td>20</td>
                        <td>Ne</td>
                    </tr>
                    <tr>
                        <td>Usmeni</td>
                        <td>40</td>
                        <td></td>
                        <td>Ne</td>
                    </tr>
                    <tr>
                        <td>Prisustvo</td>
                        <td>10</td>
                        <td></td>
                        <td>Ne</td>
                    </tr>
                    <tr>
                        <td>Zdaće</td>
                        <td>10</td>
                        <td></td>
                        <td>Ne</td>
                    </tr>
                    <tr>
                        <td>Ukupno</td>
                        <td>100</td>
                        <td>20</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
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
    mounted (){
        this.predmet=this.$route.params.id;
        this.$store.state.predmetZaUredivanje=this.predmet;
        this.$store.state.uredivanjePredmeta="sistembodovanja";

        for(let i =0; i<this.$store.state.predmeti.length; i++){
            if(this.predmet==this.$store.state.predmeti[i].id){
                this.nazivPredmeta=this.$store.state.predmeti[i].naziv;
                break;
            }
        }
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