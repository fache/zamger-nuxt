<template>
  <div id='loginPage'>
    <div class="ui middle aligned center aligned grid" style="height:100vh; background-color:#dadada; margin-top: 0;">
      <div class="column customSize">
        <h3 class="ui teal image header">
          <img src="../assets/etf-50x50.png" class="image" />
          <span class="content">
            Dobro došli na bolognaware <br />Elektrotehničkog fakulteta Sarajevo
          </span>
        </h3>
        <form class="ui large form" @submit.prevent="prijava" method="post">
          <div class="ui stacked segment">
            <div class="field">
              <div class="ui left icon input">
                <i class="user icon"></i>
                <input id="username" type="text" name="login" v-model="username" placeholder="User" />
              </div>
            </div>
            <div class="field">
              <div class="ui left icon input">
                <i class="lock icon"></i>
                <input id="password" type="password" name="pass" v-model="password" placeholder="Password" />
              </div>
            </div>
            <input type="submit" class="ui fluid large teal submit button" value="Prijavite se">
          </div>
          <div class="ui error message" v-bind:class="{ visible: errorMessage }">{{errorMessage}}</div>
        </form>

        <div class="ui message">
          Preuzmite <a href="https://zamger.etf.unsa.ba/static/doc/zamger-uputstva-42-nastavnik.pdf" target="_blank">uputstva za nastavnike</a>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import axios from 'axios'
  export default {
    data() {
      return {
        errorMessage: null,
        username: null,
        password: null,
        predmeti: []
      }
    },
    middleware:'auth',
    beforeCreate(){
    },
    methods: {
      prijava: function () {
        this.errorMessage = null;
        var object=this;
        axios.post(process.env.baseUrl+'/auth', { login: this.username, pass: this.password }, { withCredentials: true })
        .then((res) => {
          if (res.data.success == "true")  {
            sessionStorage.setItem("zamger-auth",res.data.sid);
            document.cookie ="PHPSESSID = " + res.data.sid+ "; path = /; domain=localhost; Expires=Tue, 1 Jan 2040 00:00:00 GMT;"
            object.$store.state.userId = res.data.userid;
            object.$store.state.sid = res.data.sid;
            object.$store.state.active = "aktivan";
            axios.get(process.env.baseUrl+'/person/'+object.$store.state.userId, { withCredentials: true })
            .then((res) => {
              if (res.statusText == "OK") {
                object.$store.state.userIme = res.data.name;
                object.$store.state.userPrezime = res.data.surname;
                this.$router.push('/');     
              }
            }).catch((err, res) => {
              console.log(err);
            });
          }
          else {
            object.errorMessage = "Nepoznat korisnik";
          }
        }).catch((err, res) => {
          console.log(err);
        });
      }
    }
  }
  
  </script>

<style scoped>
  @media screen and (max-width: 768px) {
    .customSize {
      width: 85% !important;
    }
  }

  @media screen and (min-width: 768px) and (max-width: 991px) {
    .customSize {
      width: 50% !important;
    }
  }

  @media screen and (min-width: 992px) {
    .customSize {
      width: 40% !important;
    }
  }
</style>
