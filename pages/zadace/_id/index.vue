<template>
  <div>
      <Headline />
      <UrediPredmet />
      <section class="container sekcija" v-bind:class="sidebar">
        <h3>{{nazivPredmeta}} - Zadaće</h3>

        <h4 class="ui horizontal divider header">
            <i class="clipboard outline icon"></i>
            Lista zadaća           
        </h4>
        <div class="ui segment">
            <table class="ui very basic collapsing celled table" style="margin:auto;">
                <thead>
                    <tr>
                        <th>Naziv zadaće</th>
                        <th>Uređivanje zadaće</th>
                        <th>Brisanje zadaće</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(zadaca) in zadace" :key="zadaca.id">
                        <td>
                            <h4 class="ui header">
                                <div class="content">
                                    {{zadaca.nazivZadace}}
                                </div>
                            </h4>
                        </td>
                        <td>
                            <div class="ui yellow mini button">Uredi zadaću</div>
                        </td>
                        <td>
                            <div class="ui negative mini button">Izbriši zadaću</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <h4 class="ui horizontal divider header">
            <i class="edit outline icon"></i>
            Kreiraj novu zadaću
        </h4>
        <div class="ui segment">
            <div class="ui form" style="max-width: 400px; margin: auto;">
                <div class="field">
                    <label>Naziv zadaće:</label>
                    <div class="ui input">
                        <input type="text" id="nazivZadace" name="nazivZadace" placeholder="" v-model="nazivZadace">
                    </div>
                </div>
                <div class="two fields">
                    <div class="field">
                        <label>Broj zadataka:</label>
                        <div class="ui input">
                            <input type="text" id="brojZadatka" name="brojZadatka" placeholder="" v-model="brojZadatka">
                        </div>
                    </div>
                    <div class="field">
                        <label>Max. broj bodova:</label>
                        <div class="ui input">
                            <input type="text" id="maxBodovi" name="maxBodovi" placeholder="" v-model="maxBodovi">
                        </div>
                    </div>
                </div>
                <div class="field">
                <label>Rok za slanje:</label>
                
                </div>
                    <div class="field">
                        <div class="">
                            <div class="ui toggle checkbox">
                                <input id="aktivna" type="checkbox" name="public">
                                <label>Aktivna</label>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="">
                            <div class="ui toggle checkbox">
                                <input id="readonly" type="checkbox" name="public">
                                <label>Read-only</label>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <div class="">
                            <div class="ui toggle checkbox">
                                <input id="attachment" type="checkbox" name="public">
                                <label>Slanje zadatka u formi attachmenta</label>
                            </div>
                        </div>
                    </div>
                <div class="field">
                    <label>Programski jezik:</label>
                    <select id="programskiJezik" class="ui fluid search dropdown" v-model="programskiJezik">
                        <option value="0">Nije određen</option>
                        <option value="1">C</option>
                        <option value="2">C++</option>
                        <option value="3">C++11</option>
                        <option value="4">HTML</option>
                        <option value="5">Java</option>
                        <option value="6">JavaScript</option>
                        <option value="7">Matlab .n</option>
                        <option value="8">PHP</option>
                        <option value="9">Python</option>
                    </select>
                </div>
                <div class="field">
                <label>Postavka zadaće:</label>
                <div class="ui left icon input">
                    <div class="ui button">Choose File</div>
                </div>
                </div>
                <div class="inline fields">
                    <div class="field">
                        <div class="ui positive submit button">Pošalji</div>
                    </div>
                    <div class="field">
                        <div class="ui negative submit button" v-on:click="ponistiFormu">Poništi</div>
                    </div>
                </div>
            </div>
        </div>
        <h4 class="ui horizontal divider header">
            <i class="edit outline icon"></i>
            Masovni unos zadaća
        </h4>

        <div class="ui segment">
            <div class="ui form" style="max-width:600px; margin:auto;">
                <div class="fields">
                    <div class="field">
                        <label>Izaberite zadaću:</label>
                        <select class="ui fluid search dropdown">
                            <option value="1">Zadaca 1</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Izaberite zadatak:</label>
                        <select id="izborGrupe" class="ui fluid search dropdown">
                            <option value="1">Zadatak 1</option>
                        </select>
                    </div>
                </div>

                <div class="field">
                    <textarea rows="5"></textarea>
                </div>
                
                <div class="fields">
                    <div class="field">
                        <label>Format:</label>
                        <select class="ui fluid search dropdown">
                            <option value="1">Ime Prezime</option>
                            <option value="2">Prezime Ime</option>
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
    head () {
        return {
        script: [
        ],
        }
    },
    data() {
        return {
            predmet:null,
            nazivPredmeta:null,
            predmetObjekat:null,
            zadace:[],
            zadaceInfo:[],
            nazivZadace:"",
            brojZadatka:"",
            maxBodovi:"",
            programskiJezik:0
        }
    },
    methods:{
        ponistiFormu: function(){
            this.nazivZadace="";
            this.brojZadatka="";
            this.maxBodovi="";
            document.getElementById('aktivna').checked=false;
            document.getElementById('readonly').checked=false;
            document.getElementById('attachment').checked=false;
            this.programskiJezik=0;  
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
        this.$store.state.uredivanjePredmeta="zadace";

        for(let i =0; i<this.$store.state.predmeti.length; i++){
            if(this.predmet==this.$store.state.predmeti[i].id){
                this.nazivPredmeta=this.$store.state.predmeti[i].naziv;
                this.predmetObjekat=this.$store.state.predmeti[i];
                break;
            }
        }

        axios.get(process.env.baseUrl+'/homework/course/'+ this.predmet, { withCredentials: true }).then((res) => {
             for(let i =0; i<res.data.results.length; i++){
                 var obj={};
                 obj.id=res.data.results[i].id;
                 obj.nazivZadace=res.data.results[i].name;
                 this.zadace.push(obj)
             }
             
            console.log(this.zadace);
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