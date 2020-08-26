<template>
  <div>
      <Headline />
      <UrediPredmet />
      <section class="container sekcija" v-bind:class="sidebar">
        <h3>{{nazivPredmeta}} - Grupe za predavanja i vježbe</h3>

        <h4 class="ui horizontal divider header">
            <i class="user icon"></i>
            Spisak grupa
        </h4>
        <div class="ui segment">

        <table class="ui very basic collapsing celled table" style="margin:auto;">
            <thead>
                <tr>
                    <th>Naziv grupe</th>
                    <th>Broj studenata u grupi</th>
                    <th>Brisanje grupe</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(grupa) in grupeZaPredmet" :key="grupa.id">
                    <td>
                        <h4 class="ui header">
                            <div class="content">
                                {{grupa.nazivGrupe}}
                            </div>
                        </h4>
                    </td>
                    <td>
                        {{grupa.brojStudenata}}
                    </td>
                    <td>
                        <div class="ui negative mini button">Izbriši grupu</div>
                    </td>
                </tr>
            </tbody>
        </table>

        </div>
        <h4 class="ui horizontal divider header">
            <i class="user plus icon"></i>
            Dodavanje grupe
        </h4>


        <div class="ui placeholder segment">
        <div class="ui two column very relaxed stackable grid">
            <div class="column">
                <div class="ui form">
                    <div class="field">
                    <label>Dodaj grupu:</label>
                    <div class="ui left icon input">
                        <input type="text" name="nazivGrupe" placeholder="Naziv grupe">
                        <i class="user icon"></i>
                    </div>
                    </div>
                    <div class="field">
                        <label>Tip grupe:</label>
                        <select class="ui fluid search dropdown">
                            <option value="vjezbe">Grupa za predavanja</option>
                            <option value="vjezbe">Grupa za vježbe</option>
                            <option value="vjezbe">Grupa za tutorijale</option>
                            <option value="vjezbe">Grupa za vježbe i turorijale</option>
                        </select>
                    </div>
                    <div class="ui blue submit button">Dodaj</div>
                </div>
            </div>
            <div class="middle aligned column">
                <div class="ui form" style="max-width: 600px; margin: auto;">
                    <div class="field">
                        <label>Prekopiraj grupe sa predmeta:</label>
                        <select class="ui fluid search dropdown">
                            <option v-for="predmet in predmeti" :key="predmet.id" value="predmet.id">{{predmet.naziv}}</option>
                        </select>
                    </div>
                    <div class="ui blue submit button">Prekopiraj</div>
                </div>
            </div>
        </div>
        <div class="ui vertical divider">
            ili
        </div>
        </div>
        <h4 class="ui horizontal divider header">
            <i class="users icon"></i>
            Masovni upis studenata
        </h4>
        <div class="ui segment">
            <div class="ui form" style="max-width:600px; margin:auto;">
                <div class="field">
                    <label>U prozoru ispod navedite ime i prezime studenta, znak za separator i naziv grupe u koju želite da ga/je upišete.</label>
                    <textarea rows="5"></textarea>
                </div>
                
                <div class="fields">
                    <div class="field">
                        <label>Format:</label>
                        <select class="ui fluid search dropdown">
                            <option value="1">Ime Prezime</option>
                            <option value="2">Preime Ime</option>
                            <option value="3">Ime[TAB]Prezime</option>
                            <option value="4">Prezime[TAB]Ime</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Separator:</label>
                        <select id="izborGrupe" class="ui fluid search dropdown">
                            <option value="1">Zarez</option>
                            <option value="2">Tab</option>
                        </select>
                    </div>
                </div>
                <button class="ui blue submit button" type="submit">Dodaj</button>
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
    data() {
        return {
            predmet:null,
            nazivPredmeta:null,
            predmeti:null,
            predmetObjekat:null,
            grupeZaPredmet:[]
            // grupeZaPredmet:[{nazivGrupe:"Grupa 1", brojStudenata:1},{nazivGrupe:"Grupa 2", brojStudenata:1}]
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
    beforeMount (){
        this.predmet=this.$route.params.id;
        this.$store.state.predmetZaUredivanje=this.predmet;
        this.$store.state.uredivanjePredmeta="uredigrupe";

        for(let i =0; i<this.$store.state.predmeti.length; i++){
            if(this.predmet==this.$store.state.predmeti[i].id){
                this.nazivPredmeta=this.$store.state.predmeti[i].naziv;
                this.predmetObjekat=this.$store.state.predmeti[i];
                break;
            }
        }
        this.predmeti=this.$store.state.predmeti;

        for(let i =0; i<this.predmetObjekat.grupe.length; i++){
            axios.get(process.env.baseUrl+'/group/'+ this.predmetObjekat.grupe[i].id, { withCredentials: true }).then((res) => {
                var obj={};
                obj.id=res.data.id;
                obj.nazivGrupe=res.data.name;
                obj.brojStudenata=res.data.members.length;
                this.grupeZaPredmet.push(obj);
                //console.log(res);
            }).catch((err, res) => {
                console.log(err);
            });
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