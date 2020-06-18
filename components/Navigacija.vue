<template>
    <div>
        <div class="ui sidebar inverted vertical menu" v-bind:class="{visible:sidebarMenuVisible}">
            <div class="item" v-bind:style="{borderBottom: '1px solid #2d2e2f'}">
                <i class="hand point left outline icon" id="toogleSidebarOff" v-on:click="toogleSidebar" title="Sakrij sidebar"></i>
                <i class="home icon" id="home" v-on:click="goToHome" title="Idi na početnu stranicu"></i>
            </div>
            <!-- <nuxt-link :to="{path:'/'}"> -->
                <div class="item" v-bind:style="{borderBottom: '1px solid #2d2e2f'}">
                    Dobro došli,<br>{{this.$store.state.userIme}} {{this.$store.state.userPrezime}}                    
                </div>
            <!-- </nuxt-link> -->
            <nuxt-link :to="{path:'/login'}">
                <div id="odjava" class="item" v-bind:style="{borderBottom: '1px solid #2d2e2f'}">
                    Odjava                   
                </div>
            </nuxt-link>
            <a class="item">
                Poruke (0)
            </a>              
            <span v-for="(predmet,index) in predmeti" :key="index">
                <a class="item" v-bind:style="{borderBottom: '1px solid #2d2e2f'}">
                    {{predmet.naziv}}
                </a>    
                <nuxt-link v-for="grupa in predmet.grupe" :key="grupa.id" :to="{path:'/predmet/'+predmet.id+'/grupa/'+grupa.id}">
                    <div class="item grupaSidebar" v-bind:class="{active:grupa.id==$store.state.aktivnaGrupa}" v-bind:style="{paddingLeft: '40px', color:'goldenrod', borderBottom: '1px solid #2d2e2f'}">
                        {{grupa.nazivGrupe}}
                    </div>                
                </nuxt-link>
            </span>            
        </div>
        <i class="hand point right outline icon"  title="Prikaži sidebar" id="toogleSidebarOn" v-on:click="toogleSidebar"></i>
        <i class="home icon" id="home2" v-on:click="goToHome" title="Idi na početnu stranicu"></i>
    </div>
</template>
<script>
    import axios from 'axios'
    export default {
        data() {
            return {
                sidebarMenuVisible: null
            }
        },
        mounted (){
            this.sidebarMenuVisible=this.$store.state.showSidebar;
            
        },
        computed:{
            predmeti(){
                return this.$store.getters.predmeti;
            }
        },
        methods:{
            toogleSidebar: function(){
                this.sidebarMenuVisible=!this.sidebarMenuVisible;
                this.$store.state.showSidebar=this.sidebarMenuVisible;
            },
            goToHome: function(){
                this.$router.push('/'); 
            }
        }
    };
</script>
<style scoped>
.grupaSidebar:hover{
    background-color:#ffffff14!important;
}
#toogleSidebarOn{
    position: absolute;
    top:15px;
    left:70px;
    font-size: 2em;
    cursor:pointer;
}
#toogleSidebarOff{
    font-size: 2em;
    cursor:pointer;
}
#home{
    font-size: 2em;
    cursor:pointer;
    display:contents;
}
#home2{
    position: absolute;
    top:15px;
    left:20px;
    font-size: 2em;
    cursor:pointer;
}
#odjava{
    background-color:#ff000066;
}
#odjava:hover{
    background-color:#ff0000ad;
}
</style>