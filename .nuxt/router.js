import Vue from 'vue'
import Router from 'vue-router'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _4d01ce2b = () => interopDefault(import('../pages/login.vue' /* webpackChunkName: "pages/login" */))
const _4372a222 = () => interopDefault(import('../pages/ispiti/trash.vue' /* webpackChunkName: "pages/ispiti/trash" */))
const _a86c9418 = () => interopDefault(import('../pages/ispiti/_id/index.vue' /* webpackChunkName: "pages/ispiti/_id/index" */))
const _0af21e5b = () => interopDefault(import('../pages/izvjestaji/_id/index.vue' /* webpackChunkName: "pages/izvjestaji/_id/index" */))
const _1dae3d91 = () => interopDefault(import('../pages/konacnaocjena/_id/index.vue' /* webpackChunkName: "pages/konacnaocjena/_id/index" */))
const _16e7b9b6 = () => interopDefault(import('../pages/kvizovi/_id/index.vue' /* webpackChunkName: "pages/kvizovi/_id/index" */))
const _20904440 = () => interopDefault(import('../pages/obavjestenja/_id/index.vue' /* webpackChunkName: "pages/obavjestenja/_id/index" */))
const _56e094c4 = () => interopDefault(import('../pages/opcijepredmeta/_id/index.vue' /* webpackChunkName: "pages/opcijepredmeta/_id/index" */))
const _7df75c94 = () => interopDefault(import('../pages/projekti/_id/index.vue' /* webpackChunkName: "pages/projekti/_id/index" */))
const _0ee2fc2a = () => interopDefault(import('../pages/raspored/_id/index.vue' /* webpackChunkName: "pages/raspored/_id/index" */))
const _5c95d0b3 = () => interopDefault(import('../pages/sistembodovanja/_id/index.vue' /* webpackChunkName: "pages/sistembodovanja/_id/index" */))
const _20aa78a4 = () => interopDefault(import('../pages/uredigrupe/_id/index.vue' /* webpackChunkName: "pages/uredigrupe/_id/index" */))
const _794b2462 = () => interopDefault(import('../pages/zadace/_id/index.vue' /* webpackChunkName: "pages/zadace/_id/index" */))
const _f1a94b66 = () => interopDefault(import('../pages/predmet/_idpredmeta/grupa/_idgrupe/index.vue' /* webpackChunkName: "pages/predmet/_idpredmeta/grupa/_idgrupe/index" */))
const _9f52f364 = () => interopDefault(import('../pages/predmet/_idpredmeta/grupa/_idgrupe/student/_idstudenta/komentar/index.vue' /* webpackChunkName: "pages/predmet/_idpredmeta/grupa/_idgrupe/student/_idstudenta/komentar/index" */))
const _f94ba9d8 = () => interopDefault(import('../pages/index.vue' /* webpackChunkName: "pages/index" */))

// TODO: remove in Nuxt 3
const emptyFn = () => {}
const originalPush = Router.prototype.push
Router.prototype.push = function push (location, onComplete = emptyFn, onAbort) {
  return originalPush.call(this, location, onComplete, onAbort)
}

Vue.use(Router)

export const routerOptions = {
  mode: 'history',
  base: decodeURI('/'),
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,

  routes: [{
    path: "/login",
    component: _4d01ce2b,
    name: "login"
  }, {
    path: "/ispiti/trash",
    component: _4372a222,
    name: "ispiti-trash"
  }, {
    path: "/ispiti/:id?",
    component: _a86c9418,
    name: "ispiti-id"
  }, {
    path: "/izvjestaji/:id?",
    component: _0af21e5b,
    name: "izvjestaji-id"
  }, {
    path: "/konacnaocjena/:id?",
    component: _1dae3d91,
    name: "konacnaocjena-id"
  }, {
    path: "/kvizovi/:id?",
    component: _16e7b9b6,
    name: "kvizovi-id"
  }, {
    path: "/obavjestenja/:id?",
    component: _20904440,
    name: "obavjestenja-id"
  }, {
    path: "/opcijepredmeta/:id?",
    component: _56e094c4,
    name: "opcijepredmeta-id"
  }, {
    path: "/projekti/:id?",
    component: _7df75c94,
    name: "projekti-id"
  }, {
    path: "/raspored/:id?",
    component: _0ee2fc2a,
    name: "raspored-id"
  }, {
    path: "/sistembodovanja/:id?",
    component: _5c95d0b3,
    name: "sistembodovanja-id"
  }, {
    path: "/uredigrupe/:id?",
    component: _20aa78a4,
    name: "uredigrupe-id"
  }, {
    path: "/zadace/:id?",
    component: _794b2462,
    name: "zadace-id"
  }, {
    path: "/predmet/:idpredmeta?/grupa/:idgrupe?",
    component: _f1a94b66,
    name: "predmet-idpredmeta-grupa-idgrupe"
  }, {
    path: "/predmet/:idpredmeta?/grupa/:idgrupe?/student/:idstudenta?/komentar",
    component: _9f52f364,
    name: "predmet-idpredmeta-grupa-idgrupe-student-idstudenta-komentar"
  }, {
    path: "/",
    component: _f94ba9d8,
    name: "index"
  }],

  fallback: false
}

export function createRouter () {
  return new Router(routerOptions)
}
