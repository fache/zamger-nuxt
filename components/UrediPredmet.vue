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
            <div class="item">
                Uredite predmet
            </div>         
            <nuxt-link  :to="{path:'/opcijepredmeta/'+predmet}">
                <div class="item uredivanjeSidebar" v-bind:class="{active:$store.state.uredivanjePredmeta=='opcijepredmeta'}" v-bind:style="{paddingLeft: '40px', color:'goldenrod', borderBottom: '1px solid #2d2e2f'}">
                    Opcije predmeta
                </div>                
            </nuxt-link>
            <nuxt-link  :to="{path:'/obavjestenja/'+predmet}">
                <div class="item uredivanjeSidebar" v-bind:class="{active:$store.state.uredivanjePredmeta=='obavjestenja'}" v-bind:style="{paddingLeft: '40px', color:'goldenrod', borderBottom: '1px solid #2d2e2f'}">
                    Obavještenja za studente
                </div>                
            </nuxt-link>
            <nuxt-link  :to="{path:'/raspored/'+predmet}">
                <div class="item uredivanjeSidebar" v-bind:class="{active:$store.state.uredivanjePredmeta=='raspored'}" v-bind:style="{paddingLeft: '40px', color:'goldenrod', borderBottom: '1px solid #2d2e2f'}">
                    Raspored
                </div>                
            </nuxt-link>
            <nuxt-link  :to="{path:'/uredigrupe/'+predmet}">
                <div class="item uredivanjeSidebar" v-bind:class="{active:$store.state.uredivanjePredmeta=='uredigrupe'}" v-bind:style="{paddingLeft: '40px', color:'goldenrod', borderBottom: '1px solid #2d2e2f'}">
                    Grupe za predavanja i vježbe
                </div>                
            </nuxt-link>
            <nuxt-link  :to="{path:'/ispiti/'+predmet}">
                <div class="item uredivanjeSidebar" v-bind:class="{active:$store.state.uredivanjePredmeta=='ispiti'}" v-bind:style="{paddingLeft: '40px', color:'goldenrod', borderBottom: '1px solid #2d2e2f'}">
                    Ispiti
                </div>                
            </nuxt-link>
            <nuxt-link  :to="{path:'/zadace/'+predmet}">
                <div class="item uredivanjeSidebar" v-bind:class="{active:$store.state.uredivanjePredmeta=='zadace'}" v-bind:style="{paddingLeft: '40px', color:'goldenrod', borderBottom: '1px solid #2d2e2f'}">
                   Kreiranje i unos zadaća
                </div>                
            </nuxt-link>
            <nuxt-link  :to="{path:'/konacnaocjena/'+predmet}">
                <div class="item uredivanjeSidebar" v-bind:class="{active:$store.state.uredivanjePredmeta=='konacnaocjena'}" v-bind:style="{paddingLeft: '40px', color:'goldenrod', borderBottom: '1px solid #2d2e2f'}">
                    Konačna ocjena
                </div>                
            </nuxt-link>
            <nuxt-link  :to="{path:'/izvjestaji/'+predmet}">
                <div class="item uredivanjeSidebar" v-bind:class="{active:$store.state.uredivanjePredmeta=='izvjestaji'}" v-bind:style="{paddingLeft: '40px', color:'goldenrod', borderBottom: '1px solid #2d2e2f'}">
                    Izvještaji
                </div>                
            </nuxt-link>
            <nuxt-link  :to="{path:'/sistembodovanja/'+predmet}">
                <div class="item uredivanjeSidebar" v-bind:class="{active:$store.state.uredivanjePredmeta=='sistembodovanja'}" v-bind:style="{paddingLeft: '40px', color:'goldenrod', borderBottom: '1px solid #2d2e2f'}">
                    Sistem bodovanja
                </div>                
            </nuxt-link>
            <nuxt-link  :to="{path:'/kvizovi/'+predmet}">
                <div class="item uredivanjeSidebar" v-bind:class="{active:$store.state.uredivanjePredmeta=='kvizovi'}" v-bind:style="{paddingLeft: '40px', color:'goldenrod', borderBottom: '1px solid #2d2e2f'}">
                    Kvizovi (beta)
                </div>                
            </nuxt-link>
            <nuxt-link  :to="{path:'/projekti/'+predmet}">
                <div class="item uredivanjeSidebar" v-bind:class="{active:$store.state.uredivanjePredmeta=='projekti'}" v-bind:style="{paddingLeft: '40px', color:'goldenrod', borderBottom: '1px solid #2d2e2f'}">
                    Projekti
                </div>                
            </nuxt-link>
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
                predmet:null,
                sidebarMenuVisible:null
            }
        },
        mounted (){
            this.predmet=this.$route.params.id;
            this.sidebarMenuVisible=this.$store.state.showSidebar;
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