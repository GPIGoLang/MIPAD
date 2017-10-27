<template>
    <div>
        <mast></mast>    
         <!-- begin:: Page -->
        <div class="m-grid m-grid--hor m-grid--root m-page">
            <!-- begin::Body -->
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid m-grid--hor m-container m-container--responsive m-container--xxl">
                <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body">
                    <navigation></navigation>
                    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--desktop m-grid--ver-desktop m-body__content">
                        <!-- BEGIN: Left Aside -->
                        <button class="m-aside-left-close m-aside-left-close--skin-light" id="m_aside_left_close_btn">
                                <i class="la la-close"></i>
                        </button>
                        <div id="m_aside_left" class="m-grid__item m-aside-left ">
                            <!-- BEGIN: Aside Menu -->
                            <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-light m-aside-menu--submenu-skin-light " data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500">
                                <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
                                    <li class="m-menu__item " aria-haspopup="true" data-redirect="true">
                                        <a href="inner.html" class="m-menu__link ">
                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                <span></span>
                                            </i>
                                            <span class="m-menu__link-text">
                                                
                                            </span>
                                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                                        </a>
                                    </li>
                                    <li class="m-menu__item " aria-haspopup="true" data-redirect="true">
                                        <a href="inner.html" class="m-menu__link ">
                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                <span></span>
                                            </i>
                                            <span class="m-menu__link-text">
                                                
                                            </span>
                                            <i class="m-menu__ver-arrow la la-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <!-- END: Aside Menu -->
                    </div>
                    <!-- END: Left Aside -->
                    <div class="m-grid__item m-grid__item--fluid m-wrapper">
                        <!-- BEGIN: Subheader -->
                        <div class="m-subheader ">
                            <div class="d-flex align-items-center">
                                <div class="mr-auto">
                                    <h3 class="m-subheader__title m-subheader__title--separator">
                                        Nominate Someone
                                    </h3>
                                    <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                                        <li class="m-nav__item m-nav__item--home">
                                            <a href="#" class="m-nav__link m-nav__link--icon">
                                                <i class="m-nav__link-icon la la-home"></i>
                                            </a>
                                        </li>
                                        <li class="m-nav__separator">
                                            -
                                        </li>
                                        <li class="m-nav__item">
                                            <router-link to="/dashboard" class="m-nav__link">
                                                <span class="m-nav__link-text">
                                                    Dashboard
                                                </span>
                                            </router-link>
                                        </li>
                                        <li class="m-nav__separator">
                                            -
                                        </li>
                                        <li class="m-nav__item">
                                            <router-link to="/nominate" class="m-nav__link">
                                                <span class="m-nav__link-text">
                                                    Nominations
                                                </span>
                                            </router-link>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- END: Subheader -->
                        <div class="m-content">
                            
                            <form class="col-xs-8" @submit.prevent="nominate">
                                <div v-if="flash.error" class="m-alert m-alert--outline alert alert-error alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                    <span>{{flash.error}}</span>
                                </div>
                                <div class="form-group">
                                    <input type="text" ref="email" placeholder="Email" class="form-control" v-model="form.email">
                                    <span v-if="errors.email" class="text-danger">{{errors.email[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <input type="text" ref="first_name" placeholder="First name" class="form-control" v-model="form.first_name">
                                    <span v-if="errors.first_name" class="text-danger">{{errors.first_name[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <input type="text" ref="last_name" placeholder="Last name" class="form-control" v-model="form.last_name">
                                    <span v-if="errors.last_name" class="text-danger">{{errors.last_name[0]}}</span>
                                </div>
                                <div class="form-group">
                                    <input type="text" ref="mobile" placeholder="Mobile" class="form-control" v-model="form.mobile">
                                    <span v-if="errors.mobile" class="text-danger">{{errors.mobile[0]}}</span>
                                </div>
                                
                                <button class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">Nominate</button>
                                <div class="form-group">
                                    <br> <br>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
            
  </div>
</template>

<style>

</style>

<script>
    import Mast from '../../views/user/layouts/Mast.vue'
    import Navigation from '../../views/user/layouts/Navigation.vue'
    import Flash from '../../helpers/flash'
    import base_url from '../../helpers/constants'
    import { post } from '../../helpers/api'
    import Auth from '../../store/auth'
    
    export default {
        data() {
            return {
                form: {
                    first_name: '',
                    last_name: '',
                    mobile: '',
                    email: '',
                },
                errors: [],
                flash: Flash.state,
            }
        },
        components: {
            Navigation, Mast
        },
        created() {
            if(!Auth.state.api_token) {
                this.$router.push('/login')
            }
        },
        methods: {
            nominate() {
                post(base_url+`/api/nominate`, this.form)
                    .then((res) => {
                        if(res.data.nominated) {
                            this.$router.push('/dashboard')
                        }else if(res.data.nominated === false){
                            Flash.setError(res.data.message)
                        }
                    })
                    .catch((err) => {
                        if(err.response.status === 422) {
                            this.errors = err.response.data
                        }else if(err.response.status === 401){
                            Auth.remove()
                            Auth.initialize()
                            Flash.setError('Your session has expired.')
                            this.$router.push('/login')
                        }
                    })
            }
        }
    }
</script>
