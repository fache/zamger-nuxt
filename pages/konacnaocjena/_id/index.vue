<template>
  <div>
      <Headline />
      <UrediPredmet />
      <section class="container sekcija" v-bind:class="sidebar">
        <h3>{{nazivPredmeta}} - Konačna ocjena</h3>       

        <h4 class="ui horizontal divider header">
            <i class="users icon"></i>
            Masovni upis konačnih ocjena
        </h4>
        <div class="ui segment">
            <div class="ui form" style="max-width:600px; margin:auto;">
                <div class="field">
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
                <div class="field">
                    <div class="">
                        <div class="ui toggle checkbox">
                            <input id="readonly" type="checkbox" name="public">
                            <label>Treća kolona u formatu dd.mm.gggg.</label>
                        </div>
                    </div>
                </div>
                <button class="ui blue submit button" type="submit">Dodaj</button>
            </div>
        </div>
        <h4 class="ui horizontal divider header">
            <i class="user icon"></i>
            Pojedinačni upis konačnih ocjena
        </h4>
        <div class="ui segment">

        <table class="ui very compact  celled table" style="margin:auto;">
            <thead>
                <tr>
                    <th>R. br.</th>
                    <th>Prezime i ime</th>
                    <th>Broj indexa</th>
                    <th>Ocjena</th>
                    <th>Datum</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(student, index) in studenti" :key="index">
                    <td>
                        {{index+1}}
                    </td>
                    <td>
                        <h4 class="ui header">
                            <div class="content">
                                {{student.prezime}} {{student.ime}}
                            </div>
                        </h4>
                    </td>
                    <td>
                        {{student.index}}
                    </td>
                    <td>
                        <input type="text" name="ocjena">
                        <!-- {{student.ocjena}} -->
                    </td>
                    <td>
                        {{datumDanIMjesec(student.datum)}}
                    </td>
                    <td>
                        
                    </td>
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
import axios from 'axios'
export default {
    data() {
        return {
            predmet:null,
            nazivPredmeta:null,
            predmetObjekat:null,
            studenti:[]
        }
    },
    middleware:'auth',
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
        this.$store.state.uredivanjePredmeta="konacnaocjena";

        for(let i =0; i<this.$store.state.predmeti.length; i++){
            if(this.predmet==this.$store.state.predmeti[i].id){
                this.nazivPredmeta=this.$store.state.predmeti[i].naziv;
                this.predmetObjekat=this.$store.state.predmeti[i];
                break;
            }
        }
        // course/{course}/student/{student}
        

        axios.get(process.env.baseUrl+'/group/course/'+this.predmet+'/allStudents', { withCredentials: true }).then((res) => {
            for(let i=0; i<res.data.members.length; i++){
                var obj=res;
                axios.get(process.env.baseUrl+'/person/'+obj.data.members[i].Person.id, { withCredentials: true }).then((res) => {
                    var student={};
                    student.ime=res.data.name;
                    student.prezime=res.data.surname;
                    student.index=res.data.studentIdNr;
                    student.id=obj.data.members[i].Person.id;
                    student.ocjena=obj.data.members[i].grade;
                    student.datum=obj.data.members[i].gradeDate;
                    this.studenti.push(student);
                }).catch((err, res) => {
                    console.log(err);
                });
            }
                // console.log(res); TODO problem grade date
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
    methods: {
      datumDanIMjesec(milisekunde){
        let dateObject = new Date(milisekunde*1000);
        return dateObject.toLocaleString("en-US", {day: "numeric"}) + ". " + dateObject.toLocaleString("en-US", {month: "numeric"})+ ".";
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