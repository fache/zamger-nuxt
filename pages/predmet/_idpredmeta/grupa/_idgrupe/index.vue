<template>
  <div>
      <Headline />
      <Navigacija />
      <section class="container sekcija" v-bind:class="sidebar" style="overflow: auto;">
        <h3>{{nazivPredmeta}} - Grupa: {{this.$route.params.idgrupe==0?"svi studenti":nazivGrupe}} </h3>
        <h4 class="ui horizontal divider header">
            <i class="calendar alternate outline icon"></i>
            Registrujte novi čas
        </h4>
        <div class="ui segment">
        <div style="text-align:center">
          <input type="text" id="vrijeme-casa" placeholder="Unesite vrijeme časa" data-input> 
          <br>
          <div class="ui right pointing label" style="line-height:18px;" v-bind:class="{prisutni: sviPrisutni}">
            Svi {{sviPrisutni ? "prisutni": "odsutni"}}
          </div>
          <div class="mini ui compact segment" style="display:inline-block">
            <div class="ui fitted toggle checkbox">
              <input type="checkbox" v-model="sviPrisutni">
              <label></label>
            </div>
          </div>
          <br>
          <button class="medium ui primary button">
            Registruj
          </button>
        </div>
        </div>
        <h4 class="ui horizontal divider header">
            <i class="th list icon"></i>
            Pregled aktivnosti
        </h4>
        <table id="tabela-info" class="ui celled structured table">
          <thead>
            <tr>
              <th rowspan="2">Ime i prezime</th>
              <th rowspan="2">Broj indexa</th>
              <th rowspan="2">Komentar</th>
              <th colspan="4">Prisustvo</th>
              <th v-bind:colspan="prikaziSveZadace ? zadacePredmeta.length : 1" class="clickable" @click="tooglePrikazZadaca">{{prikaziSveZadace ? "-":"+"}}Zadaće</th>
              <th v-bind:colspan="prikaziSveIpite ? ispitiPredmeta.length : 1" class="clickable" @click="tooglePrikazIspita">{{prikaziSveIpite ? "-":"+"}} Ispiti</th>
              <th rowspan="2">Ukupno</th>
              <th rowspan="2">Konačna<br>ocjena</th>
            </tr>
            <tr>
              <th>Prvi čas</th>
              <th>Drugi čas</th>
              <th>Treci čas</th>
              <th>BOD.</th>

              <th v-show="prikaziSveZadace && zadacePredmeta.length!=0" v-for="item in zadacePredmeta" :key="'a'+item.id">{{item.name}}</th>
              <th v-show="!prikaziSveZadace">Zadaće ukupno</th>
              <th v-show="prikaziSveZadace && zadacePredmeta.length==0">Nema zadaća</th>

              <th v-show="prikaziSveIpite && ispitiPredmeta.length!=0" v-for="item in ispitiPredmeta" :key="item.id">{{ScoringElement(item.ScoringElement.id)}}<br>{{datumDanIMjesec(item.date)}}</th>
              <th v-show="!prikaziSveIpite">Ispiti ukupno</th>
              <th v-show="prikaziSveIpite && ispitiPredmeta.length==0">Nema ispita</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(item, index) in studentiUGrupi" :key="item.id">
              <td>{{index+1}}. {{ item.prezimeOsobe }} {{ item.imeOsobe }} </td>
              <td>{{ item.indexOsobe }} </td>
              <td>komentar todo</td>

              <td>pr. 1</td>
              <td>pr. 2</td>
              <td>pr. 3</td>
              <td>pr. bod.</td>

              <!-- zadace -->
              <!-- <td v-for="(itemZadace, index) in item.zadace" :key="index">{{itemZadace.bodovi ? itemOcjene.bodovi : "0"}}</td> -->
              <td v-show="prikaziSveZadace && zadacePredmeta.length!=0" v-for="item in zadacePredmeta" :key="'b'+item.id">todo zad</td>
              <td v-show="!prikaziSveZadace || zadacePredmeta.length==0">todo</td>

              <!-- sortiranje prema id ocjene -->
              <td v-show="prikaziSveIpite && item.ocjene.length!=0" v-for="(itemOcjene, index) in item.ocjene" :key="index" @dblclick="urediElement($event)" @focusout="vratiElement($event)">{{itemOcjene.rezultat ? itemOcjene.rezultat : "/"}}</td>
              <td v-show="!prikaziSveIpite || item.ocjene.length==0">todo</td>
              <td>uk</td>

              <td @dblclick="urediElement($event)" @focusout="vratiElement($event)">{{ item.konacnaOcjena ? item.konacnaOcjena : "/" }}</td>
            </tr>
          </tbody>
        </table>
      </section>
  </div>
</template>
<script>
import Headline from '@/components/Headline'
import Navigacija from '@/components/Navigacija'
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
        svistudenti:false,
        predmetId:null,
        nazivPredmeta:null,
        nazivGrupe:null,
        studentiUGrupi:[],
        ispitiPredmeta:[],
        zadacePredmeta:[],
        prikaziSveIpite:true,
        prikaziSveZadace:true,
        sviPrisutni:false
      }
    },
    components: {
      Headline, Navigacija
    },
    beforeCreate(){
    },
    mounted (){
      document.getElementById("vrijeme-casa").flatpickr({
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
      this.predmetId=this.$route.params.idpredmeta;
      //prikazuje se virtualna grupa Svi studenti
      if(this.$route.params.idgrupe==0){
        this.svistudenti=true;
        var object=this;

        //provjeravanje svih studenata na predmetu
        axios.get(process.env.baseUrl+'/group/course/'+this.predmetId+'/allStudents', { withCredentials: true }).then((resStudenti) => {
          if (resStudenti.statusText == "OK") {
            resStudenti.data.members.forEach((element, index) => {

              let student={};
              student.id=element.Person.id;
              student.ocjene=[];
              student.zadace=[];
              student.konacnaOcjena=element.grade;
              object.studentiUGrupi.push(student);

              //osnovne informacije o studentima
              axios.get(process.env.baseUrl+'/person/'+student.id, { withCredentials: true }).then((resPerson) => {
                // console.log(resPerson.data);
                for(var i=0; i<object.studentiUGrupi.length;i++){
                  if(object.studentiUGrupi[i].id==resPerson.data.id){
                    object.studentiUGrupi[i].imeOsobe=resPerson.data.name;
                    object.studentiUGrupi[i].prezimeOsobe=resPerson.data.surname;
                    object.studentiUGrupi[i].indexOsobe=resPerson.data.studentIdNr;
                    break;
                  }
                }
                if(index==resStudenti.data.members.length-1){
                  //provjeravnje svih ispita na predmetu
                  axios.get(process.env.baseUrl+'/exam/course/'+object.predmetId, { withCredentials: true }).then((resSvihIspita) => {
                    object.ispitiPredmeta=resSvihIspita.data.results;
                    object.ispitiPredmeta.sort(function(a, b){return a.id - b.id});
                    //za svaki ispit provjeri svakog studenta
                    object.studentiUGrupi.forEach((student) => {
                      object.ispitiPredmeta.forEach((ispit) => {
                        axios.get(process.env.baseUrl+'/exam/'+ispit.id+'/student/'+student.id, { withCredentials: true }).then((resPojedinacnihIspita) => {
                          let tempIspit={};
                          tempIspit.id=ispit.id;
                          tempIspit.rezultat=resPojedinacnihIspita.data.result;
                          student.ocjene.push(tempIspit);
                          student.ocjene.sort(function(a, b){return a.id - b.id});
                        }).catch((err, resPojedinacnihIspita) => {
                            console.log(err);
                        });
                      });
                    });

                  }).catch((err, resSvihIspita) => {
                      console.log(err);
                  });

                  //provjeravanje svih zadaca na predmetu
                  axios.get(process.env.baseUrl+'/homework/course/'+object.predmetId, { withCredentials: true }).then((resSvihZadaca) => {
                    // console.log(resSvihZadaca.data.results);
                    object.zadacePredmeta=resSvihZadaca.data.results;

                    object.zadacePredmeta.sort(function(a, b){return a.id - b.id});
                    //za svaku zadacu provjeri svakog studenta
                    // object.studentiUGrupi.forEach((student) => {
                    //     const params = {
                    //       // "year": 0,
                    //       // "scoringElement": 0,
                    //       // "SESSION_ID": this.$store.state.sid
                    //     };
                    // // TODO//     axios.get(process.env.baseUrl+'/homework/course/'+object.predmetId+'/student/'+student.id, { params },{ withCredentials: true }).then((resZadacaZaStudenta) => {
                    // //       console.log(resZadacaZaStudenta);
                    // // //     //   // let tempIspit={};
                    // // //     //   // tempIspit.id=ispit.id;
                    // // //     //   // tempIspit.rezultat=resPojedinacnihIspita.data.result;
                    // // //     //   // student.ocjene.push(tempIspit);
                    // // //     //   // student.ocjene.sort(function(a, b){return a.id - b.id});

                    // //     }).catch((err, resZadacaZaStudenta) => {
                    // //         console.log(err);
                    // //     });
                    // });
                  }).catch((err, resSvihZadaca) => {
                      console.log(err);
                  });

                  //provjeravanje prisutnosti na casovima
                  object.studentiUGrupi.forEach((student) => {
                    const params = {
                      // "SESSION_ID": this.$store.state,
                      // "year": 0,
                      // "scoringElement": 0.sid
                    };
                    axios.get(process.env.baseUrl+'/class/course/'+object.predmetId+'/student/'+student.id, { params },{ withCredentials: true }).then((resPrisutnosti) => {

                    }).catch((err, resPrisutnosti) => {
                        console.log(err);
                    });
                  });
                }
                
              }).catch((err, resPerson) => {
                  console.log(err);
              });

            });
          }
          else{
              console.log("greška pri pozivu svi studenti");
          }
        


        }).catch((err, resStudenti) => {
          console.log(err);
        });

      }
      //prikazuje se regularna grupa
      else{
        this.grupa=this.$route.params.idgrupe;        
        this.$store.state.aktivnaGrupa=this.grupa;
        this.svistudenti=false;
        var object=this;

        //provjeravanje svih studenata u grupi
        axios.get(process.env.baseUrl+'/group/course/'+this.predmetId+'/allStudents', { withCredentials: true }).then((resStudenti) => {
          if (resStudenti.statusText == "OK") {
            resStudenti.data.members.forEach((element,index) => {

              let student={};
              student.id=element.Person.id;
              student.ocjene=[];
              student.zadace=[];
              student.konacnaOcjena=element.grade;
              object.studentiUGrupi.push(student);

              //osnovne informacije o studentima
              axios.get(process.env.baseUrl+'/person/'+student.id, { withCredentials: true }).then((resPerson) => {
                // console.log(resPerson.data);
                for(var i=0; i<object.studentiUGrupi.length;i++){
                  if(object.studentiUGrupi[i].id==resPerson.data.id){
                    object.studentiUGrupi[i].imeOsobe=resPerson.data.name;
                    object.studentiUGrupi[i].prezimeOsobe=resPerson.data.surname;
                    object.studentiUGrupi[i].indexOsobe=resPerson.data.studentIdNr;
                    break;
                  }
                }
                console.log(index);
                console.log(resStudenti.data.members.length-1);
                if(index==resStudenti.data.members.length-1){
                  //provjeravnje svih ispita na predmetu
                  axios.get(process.env.baseUrl+'/exam/course/'+object.predmetId, { withCredentials: true }).then((resSvihIspita) => {
                    object.ispitiPredmeta=resSvihIspita.data.results;
                    object.ispitiPredmeta.sort(function(a, b){return a.id - b.id});
                    //za svaki ispit provjeri svakog studenta
                    object.studentiUGrupi.forEach((student) => {
                      object.ispitiPredmeta.forEach((ispit) => {
                        axios.get(process.env.baseUrl+'/exam/'+ispit.id+'/student/'+student.id, { withCredentials: true }).then((resPojedinacnihIspita) => {
                          let tempIspit={};
                          tempIspit.id=ispit.id;
                          tempIspit.rezultat=resPojedinacnihIspita.data.result;
                          student.ocjene.push(tempIspit);
                          student.ocjene.sort(function(a, b){return a.id - b.id});
                        }).catch((err, resPojedinacnihIspita) => {
                            console.log(err);
                        });
                      });
                    });

                  }).catch((err, resSvihIspita) => {
                      console.log(err);
                  });

                  //provjeravanje svih zadaca na predmetu
                  axios.get(process.env.baseUrl+'/homework/course/'+object.predmetId, { withCredentials: true }).then((resSvihZadaca) => {
                    // console.log(resSvihZadaca.data.results);
                    object.zadacePredmeta=resSvihZadaca.data.results;

                    object.zadacePredmeta.sort(function(a, b){return a.id - b.id});
                    //za svaku zadacu provjeri svakog studenta
                    // object.studentiUGrupi.forEach((student) => {
                    //     const params = {
                    //       // "year": 0,
                    //       // "scoringElement": 0
                    //     };
                    // // TODO//     axios.get(process.env.baseUrl+'/homework/course/'+object.predmetId+'/student/'+student.id, { params },{ withCredentials: true }).then((resZadacaZaStudenta) => {
                    // //       console.log(resZadacaZaStudenta);
                    // // //     //   // let tempIspit={};
                    // // //     //   // tempIspit.id=ispit.id;
                    // // //     //   // tempIspit.rezultat=resPojedinacnihIspita.data.result;
                    // // //     //   // student.ocjene.push(tempIspit);
                    // // //     //   // student.ocjene.sort(function(a, b){return a.id - b.id});

                    // //     }).catch((err, resZadacaZaStudenta) => {
                    // //         console.log(err);
                    // //     });
                    // });
                  }).catch((err, resSvihZadaca) => {
                      console.log(err);
                  });

                  // TODO //provjeravanje prisutnosti na casovima
                  // object.studentiUGrupi.forEach((student) => {
                  //   const params = {
                  //     "SESSION_ID": this.$store.state.sid
                  //   };
                  //   axios.get(process.env.baseUrl+'/class/course/'+object.predmetId+'/student/'+student.id, { params },{ withCredentials: true }).then((resPrisutnosti) => {
                  //     console.log(resPrisutnosti);
                  //   }).catch((err, resPrisutnosti) => {
                  //       console.log(err);
                  //   });
                  // });
                }

              }).catch((err, resPerson) => {
                  console.log(err);
              });

            });
          }
          else{
              console.log("greška pri pozivu svi studenti");
          }
        
          

        }).catch((err, resStudenti) => {
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
    methods:{
      ScoringElement(broj){
        if(broj==1) return "I parc";
        else if(broj==2) return "II parc";
        else if(broj==3) return "Int";
        else if(broj==4) return "Usmeni";
      },
      datumDanIMjesec(milisekunde){
        let dateObject = new Date(milisekunde*1000);
        return dateObject.toLocaleString("en-US", {day: "numeric"}) + ". " + dateObject.toLocaleString("en-US", {month: "numeric"})+ ".";
      },
      tooglePrikazIspita(){
        this.prikaziSveIpite=!this.prikaziSveIpite;
        if(this.prikaziSveIpite){
        }
      },
      tooglePrikazZadaca(){
        this.prikaziSveZadace=!this.prikaziSveZadace;
        if(this.prikaziSveZadace){
        }
      },
      urediElement(event){
        event.target.contentEditable = "true";
        event.target.focus();
      },
      vratiElement(event){
        event.target.contentEditable = "false";
      }
    },
    beforeDestroy(){
      this.$store.state.aktivnaGrupa=null;
    }
}
</script>
<style scoped>
#tabela-info .clickable{
  cursor:pointer;
}
#vrijeme-casa{
  height: 34px;
  border: 1px solid grey;
  border-radius: 5px;
  padding: 15px;
}
.prisutni{
  background: #2384d0;
  color: white;
}
@media screen and (min-width: 500px) {
  
}
</style>