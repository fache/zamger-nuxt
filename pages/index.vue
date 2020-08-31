<template>
  <div>
      <Headline />
      <Navigacija />
      <section class="container sekcija" v-bind:class="sidebar">
        <div class="wrapper">
            <div class="ui large header center aligned">Svi Vaši predmeti</div>
          <div class="ui four column doubling stackable grid">
            <div v-for="predmet in predmeti" :key="predmet.id" class="column">
              <div class="ui segment">
                <div class="ui small header center aligned">{{predmet.naziv}}</div>                
                <div class="ui tiny header right aligned"><nuxt-link :to="{path:'/opcijepredmeta/'+predmet.id}"><button class="mini ui yellow button">Uredi predmet</button></nuxt-link></div>
                <nuxt-link v-for="grupa in predmet.grupe" :key="grupa.id" :to="{path:'/predmet/'+predmet.id+'/grupa/'+ grupa.id }"><div>{{grupa.nazivGrupe}}</div></nuxt-link>
                <!-- <nuxt-link :to="{path:'/predmet/'+predmet.id+'/grupa/'+ 0 }">Svi studenti</nuxt-link> -->
              </div>
            </div>
          </div>
        </div>
      </section>
  </div>
</template>
<script>
import Headline from '@/components/Headline'
import Navigacija from '@/components/Navigacija'
import axios from 'axios'
export default {
    data() {
      return {
        predmeti: []
      }
    },
    methods: {
      
    },
    computed: {
      sidebar: function () {
        if(this.$store.state.showSidebar) return "lijeviPaddingSidebar";
        else return "lijeviPadding";
      }
    },
    middleware:'auth',
    beforeCreate(){
    },
    mounted (){
      //daj sve predmete nastavnika
      axios.get(process.env.baseUrl+'/course/teacher/'+this.$store.state.userId, { withCredentials: true }).then((res) => {
        for(let i=0; i<res.data.results.length; i++){
          let predmet={};
          predmet.naziv=res.data.results[i].CourseUnit.name;
          predmet.id=res.data.results[i].CourseUnit.id;                    
          predmet.grupe=[];
          
          //daj informacije o grupama o pojedinacnom predmetu
          axios.get(process.env.baseUrl+'/group/course/'+predmet.id, { withCredentials: true }).then((res) => {
            if(res.statusText="OK"){
              //pronalazak id-a virtualne grupe prema listi grupa studenta upisanog na predmet TODO:ako nema upisanih studenata
              var virtualnaGrupa={};
              axios.get(process.env.baseUrl+'/group/course/'+predmet.id+'/allStudents', { withCredentials: true }).then((resVir) => {
                
                var idstudenta=resVir.data.members[0].Person.id;
                axios.get(process.env.baseUrl+'/group/course/'+predmet.id+'/student/'+idstudenta, { withCredentials: true }).then((resVirSve) => {
                  //dodaj regularnu grupu u predmet
                  for(let l=0; l<res.data.results.length; l++){
                    let grupa={};
                    grupa.virtual=false;
                    grupa.id=res.data.results[l].id;
                    grupa.nazivGrupe=res.data.results[l].name;
                    predmet.grupe.push(grupa);
                  }
                  for(let p=0; p<resVirSve.data.results.length; p++){
                    //  console.log(resVirSve.data.results[p]);
                    if(resVirSve.data.results[p].virtual){
                      virtualnaGrupa.id=resVirSve.data.results[p].id;
                      virtualnaGrupa.virtual=true;
                      virtualnaGrupa.nazivGrupe=resVirSve.data.results[p].name;
                      //dodaj virtualnu grupu u predmet
                      predmet.grupe.push(virtualnaGrupa);
                      break;
                    }
                  }
                }).catch((err, resVir) => {
                    console.log(err);
                });

              }).catch((err, resVir) => { console.log(err); });
            }
          }).catch((err, res) => {
              console.log(err);
          });
          //spasi podatke o grupi u predmet
          this.predmeti.push(predmet);
        }
        this.$store.dispatch('postaviPredmete',this.predmeti);
      }).catch((err, res) => {
        console.log(err);
      });      
    },
    components: {
      Headline, Navigacija
    }
  }
</script>
<style scoped>
</style>