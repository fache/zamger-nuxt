<template>
  <div>
      <Poruka />
      <Navigacija />
      <Headline />

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
          <button class="medium ui primary button" v-on:click="prikaziPoruku('Nepostojeci API')">
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
              <th v-bind:colspan="prikaziSveCasove ? prisustva.length+1 : 1" class="clickable" @click="tooglePrikazCasova">{{prikaziSveCasove ? "-":"+"}} Prisustvo</th>
              <th v-bind:colspan="prikaziSveZadace ? zadacePredmeta.length : 1" class="clickable" @click="tooglePrikazZadaca">{{prikaziSveZadace ? "-":"+"}} Zadaće</th>
              <th v-bind:colspan="prikaziSveIpite ? ispitiPredmeta.length : 1" class="clickable" @click="tooglePrikazIspita">{{prikaziSveIpite ? "-":"+"}} Ispiti</th>
              <th rowspan="2">Ukupno</th>
              <th rowspan="2">Konačna<br>ocjena</th>
            </tr>
            <tr>
              <th v-show="prikaziSveCasove && prisustva.length!=0" v-for="item in prisustva" :key="'p'+item.id">{{datumDanIMjesec(item.datetime)}}</th>
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
              <td class="imeIPrezimeCell">{{index+1}}. {{ item.prezimeOsobe }} {{ item.imeOsobe }} </td>
              <td>{{ item.indexOsobe }} </td>
              <td>
                <nuxt-link :to="{path:'/predmet/'+predmetId+'/grupa/'+idtrenutnegrupe+'/student/'+item.id+'/komentar'}">
                  <span class="comment"><i class='comments icon'></i></span>
                </nuxt-link>


              <td v-on:click="updatePrisustva(item2)" class="poljePrisustva" v-show="prikaziSveCasove && prisustva.length!=0" v-for="item2 in item.prisustvo" :key="'p'+item2.ZClass.id" v-bind:style="(prikazPrisustva(item2.presence))=='DA'?'background-color:#21ba459c':((prikazPrisustva(item2.presence))=='NE'?'background-color:#db282880':'background-color:#fbbd0880') ">{{prikazPrisustva(item2.presence)}}</td>

              <td>{{item.bodoviNaPrisustvo}}</td>

              <!-- zadace -->
              <!-- <td v-for="(itemZadace, index) in item.zadace" :key="index">{{itemZadace.bodovi ? itemOcjene.bodovi : "0"}}</td> -->
              <td v-show="prikaziSveZadace && zadacePredmeta.length!=0" v-for="zadaca in zadacePredmeta" :key="'b'+zadaca.id" v-html="prikaziZadatke(zadaca,item)"></td>
              <td v-show="!prikaziSveZadace || zadacePredmeta.length==0">{{sumaZadaca(item)}}</td>

              <!-- sortiranje prema id ocjene -->
              <td v-show="prikaziSveIpite && item.ocjene.length!=0" v-for="(itemOcjene, index) in item.ocjene" :key="index" @dblclick="urediElement($event)" @focusout="vratiElement($event)" v-on:keydown.13="upisiOcjenu" v-bind:studentid="item.id" v-bind:ispitid="itemOcjene.id">{{itemOcjene.rezultat ? itemOcjene.rezultat : "/"}}</td>
              <td v-show="!prikaziSveIpite || item.ocjene.length==0">{{sumaOcjena(item.id)}}</td>
              <td>{{prikazUkupnihBodova(item)}}</td>

              <td @dblclick="urediElement($event)" @focusout="vratiElement($event)" v-on:keydown.13="upisiOcjenu">{{ item.konacnaOcjena ? item.konacnaOcjena : "/" }}</td>
            </tr>
          </tbody>
        </table>
      </section>
  </div>
</template>
<script>
import Headline from '@/components/Headline'
import Poruka from '@/components/Poruka'
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
        editOcjene:null,
        idtrenutnegrupe:null
      }
    },
    components: {
      Headline, Navigacija, Poruka
    },
    middleware:'auth',
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
              this.idtrenutnegrupe=this.$store.state.predmeti[i].grupe[j].id;
              this.nazivGrupe=this.$store.state.predmeti[i].grupe[j].nazivGrupe;
              break;
            }
          }
          break;
        }
      }
      //test
      // axios.get(process.env.baseUrl+'/homework/course/1/student/5', { withCredentials: true }).then((testVirtual) => {
      //   // console.log(testVirtual);
      //   // console.log(b.zadacePredmeta);
      // });
      this.ispisTabele();

    },
    computed: {
      sidebar: function () {
        if(this.$store.state.showSidebar) return "lijeviPaddingSidebar";
        else return "lijeviPadding";
      }
    },
    methods:{
      updatePrisustva(item){
        if(item.presence!=1){
          axios.post(process.env.baseUrl+'/class/'+item.ZClass.id+'/student/'+item.student.id, {student: item.student.id, id: item.ZClass.id, presence: 1}, { withCredentials: true }).then((resPris) => {
            if(resPris.data.success=="false") this.prikaziPoruku(resPris.data.message);
          }).catch((err, resSvihZadaca) => {
              console.log(err);
          });
          item.presence=1;
        }
        else{
          axios.post(process.env.baseUrl+'/class/'+item.ZClass.id+'/student/'+item.student.id, {student: item.student.id, id: item.ZClass.id, presence: 0}, { withCredentials: true }).then((resPris) => {
            if(resPris.data.success=="false") this.prikaziPoruku(resPris.data.message);
          }).catch((err, resSvihZadaca) => {
              console.log(err);
          });
          item.presence=0;
        }
      },
      prikaziPoruku(textPoruke){
        $("#poruka").show();
        $("#poruka").text(textPoruke);
        setTimeout(() => {
          $("#poruka").hide();
        }, 2500);
      },
      prikazUkupnihBodova(student){
        var prisustvo=student.bodoviNaPrisustvo;
        var zadace=this.sumaZadaca(student);
        var ispiti=this.sumaOcjena(student.id)
        return prisustvo+zadace+ispiti;
      },
      prikazPrisustva(res){
        if(res===false) return "/";
        if(res==1) return "DA";
        else if(res==0) return "NE";
        else return "?";
      },
      ispisTabele(){
        var studentCounter=0;
        var studentCounterFin=0;
        this.predmetId=this.$route.params.idpredmeta;
        axios.get(process.env.baseUrl+'/group/'+this.$route.params.idgrupe, { withCredentials: true }).then((testVirtual) => {
          var object=this;
          //casovi u grupi
          axios.get(process.env.baseUrl+'/class/group/'+this.$route.params.idgrupe,{ withCredentials: true }).then((resPrisutnosti) => {
            object.prisustva=resPrisutnosti.data.results;
            axios.get(process.env.baseUrl+'/group/course/'+this.predmetId+'/allStudents', { withCredentials: true }).then((resStudenti) => {
              if (resStudenti.statusText == "OK") {
                resStudenti.data.members.forEach((element, index) => {

                  //provjera egzistencije studenta u grupi
                  axios.get(process.env.baseUrl+'/group/'+this.$route.params.idgrupe+'/student/'+element.Person.id, { withCredentials: true }).then((rese) => {
                    //ako se student nalazi u grupi
                    if(rese.data==true){
                      studentCounter++;
                      //kreiraj objekat studenta
                      let student={};
                      student.id=element.Person.id;
                      student.ocjene=[];
                      student.zadace=[];
                      student.prisustvo=[];
                      student.bodoviNaPrisustvo=10;
                      student.konacnaOcjena=element.grade;
                      object.studentiUGrupi.push(student);

                      //osnovne informacije o studentima
                      axios.get(process.env.baseUrl+'/person/'+student.id, { withCredentials: true }).then((resPerson) => {
                        for(var i=0; i<object.studentiUGrupi.length;i++){
                          if(object.studentiUGrupi[i].id==resPerson.data.id){
                            object.studentiUGrupi[i].imeOsobe=resPerson.data.name;
                            object.studentiUGrupi[i].prezimeOsobe=resPerson.data.surname;
                            object.studentiUGrupi[i].indexOsobe=resPerson.data.studentIdNr;
                            object.studentiUGrupi[i].zadace=[];
                            studentCounterFin++;
                            break;
                          }
                        }

                        if(studentCounter==studentCounterFin){//nakon osnovnih informacija, dobavi ostale

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
                                  tempIspit.vrsta=ispit.ScoringElement.id;
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
                            object.zadacePredmeta=resSvihZadaca.data.results;
                            object.zadacePredmeta.sort(function(a, b){return a.id - b.id});
                            //za svaku zadacu provjeri svakog studenta
                            object.studentiUGrupi.forEach((student) => {
                              axios.get(process.env.baseUrl+'/homework/course/'+object.predmetId+'/student/'+student.id, { withCredentials: true }).then((resZadacaZaStudenta) => {
                                    resZadacaZaStudenta.data.results.forEach((zadatak) => {
                                      student.zadace.push(zadatak);
                                    });

                              }).catch((err, resZadacaZaStudenta) => {
                                  console.log(err);
                              });
                            });
                          }).catch((err, resSvihZadaca) => {
                              console.log(err);
                          });

                          //provjeravanje prisutnosti na casovima
                          object.prisustva.forEach((cas) => {
                            object.studentiUGrupi.forEach((student) => {
                              axios.get(process.env.baseUrl+'/class/'+cas.id+'/student/'+student.id,{ withCredentials: true }).then((resPrisutnosti) => {
                                object.studentiUGrupi.forEach((student) => {
                                  if(student.id==resPrisutnosti.data.student.id){
                                    student.prisustvo.push(resPrisutnosti.data);
                                    var izostanak=0;
                                    student.prisustvo.forEach((cas) => {
                                      if(cas.presence==0)izostanak++;
                                      if(izostanak>=4)student.bodoviNaPrisustvo=0;
                                    });
                                  }
                                });
                            }).catch((err, resPrisutnosti) => {
                                console.log(err);
                            });
                            });
                          });
                        }
                      }).catch((err, resPerson) => {
                          console.log(err);
                      });
                    }
                  }).catch((err, rese) => {
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
          }).catch((err, resPrisutnosti) => {
              console.log(err);
          });
        }).catch((err, testVirtual) => {
            console.log(err);
        });
      },
      sumaZadaca(osoba){
        var suma=0;
        osoba.zadace.forEach((zadatak, index) => {
          suma+=zadatak.score;
        });
        return suma;
      },
      prikaziZadatke(zadaca,item){
        var vrati="";
        item.zadace.forEach((zadatak, index) => {
          if(zadatak.Homework.id==zadaca.id){
            if(zadatak.status==0) vrati+=" <span title='nepoznat status'>"+zadatak.score+"<i class='question icon'></i></span>";
            else if(zadatak.status==1) vrati+=" <span title='sacekati automatsko testiranje'>"+zadatak.score+"<i class='spinner icon'></i></span>";
            else if(zadatak.status==2) vrati+=" <span title='prepisano'>"+zadatak.score+"<i class='ban icon'></i></span>";
            else if(zadatak.status==3) vrati+=" <span title='ne moze se kompajlirati>"+zadatak.score+"<i class='bug icon'></i></span>";
            else if(zadatak.status==4) vrati+=" <span title='potrebno pregledati'>"+zadatak.score+"<i class='eye icon'></i></span>";
            else if(zadatak.status==5) vrati+=" <span title='pregledano'>"+zadatak.score+"<i class='check icon'></i></span>";
          }
        });
        return vrati;
      },
      sumaOcjena(idStudenta){
        var Iparc=0;
        var IIparc=0;
        var Int=0;
        var Usmeni=0;
        this.studentiUGrupi.forEach((element, index) => {
          if(idStudenta==element.id){
            element.ocjene.forEach((ocjena, index) => {
              if(ocjena.rezultat!=false && ocjena.vrsta==1 && ocjena.rezultat>Iparc)Iparc=ocjena.rezultat;
              else if(ocjena.rezultat!=false && ocjena.vrsta==2 && ocjena.rezultat>IIparc)IIparc=ocjena.rezultat;
              else if(ocjena.rezultat!=false && ocjena.vrsta==3 && ocjena.rezultat>Int)Int=ocjena.rezultat;
              else if(ocjena.rezultat!=false && ocjena.vrsta==4 && ocjena.rezultat>Usmeni)Usmeni=ocjena.rezultat;
            });
          }
        });
        return Iparc+IIparc+Int+Usmeni;
      },
      ScoringElement(broj){
        if(broj==1) return "I parc";
        else if(broj==2) return "II parc";
        else if(broj==3) return "Int";
        else if(broj==4) return "Usmeni";
      },
      datumDanIMjesec(milisekunde){
        if(milisekunde==null) return "-";
        let dateObject = new Date(milisekunde*1000);
        return dateObject.toLocaleString("en-US", {day: "numeric"}) + ". " + dateObject.toLocaleString("en-US", {month: "numeric"})+ ".";
      },
      tooglePrikazIspita(){
        this.prikaziSveIpite=!this.prikaziSveIpite;
      },
      tooglePrikazZadaca(){
        this.prikaziSveZadace=!this.prikaziSveZadace;
        if(this.prikaziSveZadace){
        }
      },
      tooglePrikazCasova(){
        this.prikaziSveCasove=!this.prikaziSveCasove;
        if(this.prikaziSveCasove){
        }
      },
      urediElement(event){
        event.target.contentEditable = "true";
        event.target.focus();
        this.editOcjene=$(event.target).text();
      },
      vratiElement(event){
        event.target.contentEditable = "false";
        if(this.editOcjene!=null){
          $(event.target).text(this.editOcjene);
        }
      },
      upisiOcjenu(event){
        var object=this;
        if(!$.isNumeric($(event.target).text())){
          $(event.target).text(this.editOcjene);
        }
        else{
          this.editOcjene=null;
          var novaOcjena=$(event.target).text();
          var studentid=$(event.target).attr("studentid");
          if(studentid==undefined){
            this.prikaziPoruku("Akciju trenutno nije moguce izvrsiti");
            this.vratiElement(event);
            return;
          }
          var ispitid=$(event.target).attr("ispitid");
          axios.post(process.env.baseUrl+'/exam/'+ispitid+'/student/'+studentid, {"result":novaOcjena}, { withCredentials: true }).then((putOcjena) => {
            if(putOcjena.data.success=="false"){
              this.prikaziPoruku(putOcjena.data.message);
              console.log({"error upis ispita":putOcjena.data})
            }
            // console.log(this.studentiUGrupi);
            object.studentiUGrupi.forEach(function(student,index){
              if(studentid==student.id){
                object.studentiUGrupi[index].ocjene.forEach(function(ocjena,index2){
                  if(ocjena.id==ispitid){
                    object.studentiUGrupi[index].ocjene[index2].rezultat=parseInt(novaOcjena);
                  }
                });
              }
            });

          }).catch((err, putOcjena) => {//TODO iako baca error 500, ocjena se azurira
              console.log(err);
              object.studentiUGrupi.forEach(function(student,index){
              if(studentid==student.id){
                object.studentiUGrupi[index].ocjene.forEach(function(ocjena,index2){
                  if(ocjena.id==ispitid){
                    object.studentiUGrupi[index].ocjene[index2].rezultat=parseInt(novaOcjena);
                  }
                });
              }
            });
          });
        }
        this.vratiElement(event);
      }
    },
    beforeDestroy(){
      this.$store.state.aktivnaGrupa=null;
    }
}
</script>
<style scoped>
#tabela-info{
  text-align: center;
}
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
.poljePrisustva, .comment{
  cursor:pointer;
}
#tabela-info .imeIPrezimeCell{
  text-align: left;
}
@media screen and (min-width: 500px) {

}
</style>