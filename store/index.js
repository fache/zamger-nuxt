import Vuex from 'vuex'

const createStore = () => {
  return new Vuex.Store({
    state: () => ({
      counter: 0,
      showSidebar: true,
      active: "neaktivan",
      sid: null,
      userId: null,
      userIme: null,
      userPrezime: null,
      predmeti: [],
      aktivnaGrupa:null,
      predmetZaUredivanje:null,
      uredivanjePredmeta:null
    }),
    mutations: {
      increment(state) {
        state.counter++
      },
      postaviPredmete(state, predmetiNastavnika){
        state.predmeti=predmetiNastavnika
      }
    },
    actions:{
      postaviPredmete(vuexContext, predmetiNastavnika){
        vuexContext.commit('postaviPredmete',predmetiNastavnika)
      }
    },
    getters:{

      korisnikLogiran(state){
        return state.active
      },
      predmeti(state){
        return state.predmeti
      },
      showSidebar(state){
        return state.showSidebar;
      },
      sid(state){
        return state.sid;
      }


    }
    
  })
}

export default createStore
