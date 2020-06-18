import Vue from 'vue'
import Router from 'vue-router'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _073a54ee = () => interopDefault(import('../pages/login.vue' /* webpackChunkName: "pages/login" */))
const _99ded268 = () => interopDefault(import('../pages/ispiti/trash.vue' /* webpackChunkName: "pages/ispiti/trash" */))
const _911b315e = () => interopDefault(import('../pages/ispiti/_id/index.vue' /* webpackChunkName: "pages/ispiti/_id/index" */))
const _740e4d90 = () => interopDefault(import('../pages/izvjestaji/_id/index.vue' /* webpackChunkName: "pages/izvjestaji/_id/index" */))
const _dc4c8cd8 = () => interopDefault(import('../pages/konacnaocjena/_id/index.vue' /* webpackChunkName: "pages/konacnaocjena/_id/index" */))
const _ff55980e = () => interopDefault(import('../pages/kvizovi/_id/index.vue' /* webpackChunkName: "pages/kvizovi/_id/index" */))
const _03fb557d = () => interopDefault(import('../pages/obavjestenja/_id/index.vue' /* webpackChunkName: "pages/obavjestenja/_id/index" */))
const _68249921 = () => interopDefault(import('../pages/opcijepredmeta/_id/index.vue' /* webpackChunkName: "pages/opcijepredmeta/_id/index" */))
const _42392ab1 = () => interopDefault(import('../pages/projekti/_id/index.vue' /* webpackChunkName: "pages/projekti/_id/index" */))
const _59b66b72 = () => interopDefault(import('../pages/raspored/_id/index.vue' /* webpackChunkName: "pages/raspored/_id/index" */))
const _73d257f6 = () => interopDefault(import('../pages/sistembodovanja/_id/index.vue' /* webpackChunkName: "pages/sistembodovanja/_id/index" */))
const _aa9d02ea = () => interopDefault(import('../pages/uredigrupe/_id/index.vue' /* webpackChunkName: "pages/uredigrupe/_id/index" */))
const _f6185482 = () => interopDefault(import('../pages/zadace/_id/index.vue' /* webpackChunkName: "pages/zadace/_id/index" */))
const _f4c5f6ac = () => interopDefault(import('../pages/predmet/_idpredmeta/grupa/_idgrupe/index.vue' /* webpackChunkName: "pages/predmet/_idpredmeta/grupa/_idgrupe/index" */))
const _3d92b1d7 = () => interopDefault(import('../pages/index.vue' /* webpackChunkName: "pages/index" */))

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
    component: _073a54ee,
    name: "login"
  }, {
    path: "/ispiti/trash",
    component: _99ded268,
    name: "ispiti-trash"
  }, {
    path: "/ispiti/:id?",
    component: _911b315e,
    name: "ispiti-id"
  }, {
    path: "/izvjestaji/:id?",
    component: _740e4d90,
    name: "izvjestaji-id"
  }, {
    path: "/konacnaocjena/:id?",
    component: _dc4c8cd8,
    name: "konacnaocjena-id"
  }, {
    path: "/kvizovi/:id?",
    component: _ff55980e,
    name: "kvizovi-id"
  }, {
    path: "/obavjestenja/:id?",
    component: _03fb557d,
    name: "obavjestenja-id"
  }, {
    path: "/opcijepredmeta/:id?",
    component: _68249921,
    name: "opcijepredmeta-id"
  }, {
    path: "/projekti/:id?",
    component: _42392ab1,
    name: "projekti-id"
  }, {
    path: "/raspored/:id?",
    component: _59b66b72,
    name: "raspored-id"
  }, {
    path: "/sistembodovanja/:id?",
    component: _73d257f6,
    name: "sistembodovanja-id"
  }, {
    path: "/uredigrupe/:id?",
    component: _aa9d02ea,
    name: "uredigrupe-id"
  }, {
    path: "/zadace/:id?",
    component: _f6185482,
    name: "zadace-id"
  }, {
    path: "/predmet/:idpredmeta?/grupa/:idgrupe?",
    component: _f4c5f6ac,
    name: "predmet-idpredmeta-grupa-idgrupe"
  }, {
    path: "/",
    component: _3d92b1d7,
    name: "index"
  }],

  fallback: false
}

export function createRouter () {
  return new Router(routerOptions)
}
