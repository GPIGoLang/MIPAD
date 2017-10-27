<template>
  <div>
    <!-- begin::Header -->
        <header class="m-grid__item	m-grid m-grid--desktop m-grid--hor-desktop  m-header " >
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--desktop m-grid--hor-desktop m-container m-container--responsive m-container--xxl">
                <div class="m-grid__item m-grid__item--fluid m-grid m-grid--desktop m-grid--ver-desktop m-header__wrapper">
                    <!-- begin::Brand -->
                    <div class="m-grid__item m-brand">
                        <div class="m-stack m-stack--ver m-stack--general m-stack--inline">
                            <div class="m-stack__item m-stack__item--middle m-brand__logo">
                                <a href="index.html" class="m-brand__logo-wrapper">
                                    <img alt="" v-bind:src="'../metronic/demo/demo4/media/img/logo/logo.png'"/>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- end::Brand -->
                    <!-- begin::Topbar -->
                    <div class="m-grid__item m-grid__item--fluid m-header-head" id="m_header_nav">
                        <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                            <div class="m-stack__item m-topbar__nav-wrapper">
                                <ul class="m-topbar__nav m-nav m-nav--inline">
                                    <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
                                        <a href="#" class="m-nav__link m-dropdown__toggle">
                                            <span class="m-topbar__welcome m--hidden-tablet m--hidden-mobile">
                                                You logged in as,&nbsp;
                                            </span>
                                            <span class="m-topbar__username m--hidden-tablet m--hidden-mobile m--padding-right-15">
                                                <span class="m-link">
                                                    {{username}}
                                                </span>
                                            </span>
                                            <span class="m-topbar__userpic">
                                                <img v-bind:src="'metronic/app/media/img/users/user4.jpg'" class="m--img-rounded m--marginless m--img-centered" alt=""/>
                                            </span>
                                        </a>
                                        <div class="m-dropdown__wrapper">
                                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                            <div class="m-dropdown__inner">
                                                <div class="m-dropdown__header m--align-center" style="background: url(metronic/app/media/img/misc/user_profile_bg.jpg); background-size: cover;">
                                                    <div class="m-card-user m-card-user--skin-dark">
                                                        <div class="m-card-user__pic">
                                                            <img v-bind:src="'metronic/app/media/img/users/user.jpg'" class="m--img-rounded m--marginless" alt=""/>
                                                        </div>
                                                        <div class="m-card-user__details">
                                                            <span class="m-card-user__name m--font-weight-500">
                                                                {{username}}
                                                            </span>
                                                            <a href="" class="m-card-user__email m--font-weight-300 m-link">
                                                                
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="m-dropdown__body">
                                                    <div class="m-dropdown__content">
                                                        <ul class="m-nav m-nav--skin-light">
                                                            <li class="m-nav__section m--hide">
                                                                <span class="m-nav__section-text">
                                                                    Section
                                                                </span>
                                                            </li>
                                                            <li class="m-nav__item">
                                                                <a href="profile.html" class="m-nav__link">
                                                                    <i class="m-nav__link-icon flaticon-profile-1"></i>
                                                                    <span class="m-nav__link-title">
                                                                        <span class="m-nav__link-wrap">
                                                                            <span class="m-nav__link-text">
                                                                                My Profile
                                                                            </span>
                                                                        </span>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="m-nav__separator m-nav__separator--fit"></li>
                                                            <li class="m-nav__item">
                                                                <a href="javascript:;" @click="logout" class="btn m-btn--pill btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                                                    Logout
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- end::Topbar -->
                </div>
            </div>
        </header>
        <!-- end::Header -->
  </div>
</template>

<style>

</style>

<script>
    import { post } from '../../../helpers/api'
    import base_url from '../../../helpers/constants'
    import Auth from '../../../store/auth'

    export default {
        data() {
            return {
                username: null
            }
        },
        created() {
            Auth.initialize();
            this.username = Auth.state.email
        },
        methods: {
            logout() {
                post(base_url+`/api/logout`)
                    .then((res) => {
                        if(res.data.logged_out) {
                            Auth.remove();
                            this.$router.push('/login');
                        }
                    })
                    .catch((err) => {

                    })
            }

        }
    }
</script>
