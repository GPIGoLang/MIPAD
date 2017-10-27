<template>
    <div>
        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-grid--tablet-and-mobile m-grid--hor-tablet-and-mobile m-login m-login--1 m-login--singin" id="m_login">
            <div class="m-grid__item m-grid__item--order-tablet-and-mobile-2 m-login__aside">
                <div class="m-stack m-stack--hor m-stack--desktop">
                    <div class="m-stack__item m-stack__item--fluid">
                        <div class="m-login__wrapper">
                            <div class="m-login__logo">
                                <a href="#">
                                    <img v-bind:src="'../metronic/app/media/img/logos/logo.png'" width="120">
                                </a>
                            </div>
                            <div class="m-login__signin">
                                <div class="m-login__head">
                                    <h3 class="m-login__title">
                                        Request Verification Link
                                    </h3>
                                </div>
                                <div v-if="flash.success" class="m-alert m-alert--outline alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                    <span>{{flash.success}}</span>
                                </div>
                                <div v-if="flash.error" class="m-alert m-alert--outline alert alert-danger alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                    <span>{{flash.error}}</span>
                                </div>
                                
                                <form  class="m-login__form m-form" action="" @submit.prevent="resend">

                                    <div class="form-group m-form__group">
                                        <input class="form-control m-input" type="text" placeholder="Email" ref="email" id="m_email" v-model="form.email">
                                        <span v-if="errors.email" class="text-danger">{{errors.email[0]}}.</span>
                                    </div>
                                    <div class="m-login__form-action">
                                        <button id="m_login_forget_password_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">
                                            Request
                                        </button>
                                        <router-link to="/login" class="btn btn-secondary m-btn m-btn--pill m-btn--custom m-btn--air">Cancel</router-link>
                                    </div>
                                
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="m-stack__item m-stack__item--center">
                        <div class="m-login__account">
					<span class="m-login__account-msg">
						Don't have an account yet ?
					</span>
                            &nbsp;&nbsp;
                            <router-link to="/register" class="m-link m-link--focus m-login__account-link">Sign Up</router-link>
                            &nbsp;|&nbsp;
                            <router-link to="/login" class="m-link m-link--focus m-login__account-link">Sign In</router-link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--center m-grid--hor m-grid__item--order-tablet-and-mobile-1	m-login__content" style="background-image: url(../metronic/app/media/img/bg/bg-5.jpg)">
                <div class="m-grid__item m-grid__item--middle">
                    <h3 class="m-login__welcome">
                        Join Our Community
                    </h3>
                    <p class="m-login__msg">
                        Lorem ipsum dolor sit amet, coectetuer adipiscing
                        <br>
                        elit sed diam nonummy et nibh euismod
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { post } from '../../helpers/api'
    import Flash from '../../helpers/flash'
    import base_url from '../../helpers/constants'

    export default{
        data(){
            return{
                form: {
                    email: ''
                },
                errors: {},
                flash: Flash.state,
                isLoading: false
            }
        },
        components:{

        },
        methods: {
            resend() {
                post(base_url+`/api/code-resend`, this.form)
                    .then((res) => {
                        if(!res.data.resent) {
                            Flash.setError(res.data.message)
                        }else if(res.data.resent) {
                            Flash.setSuccess(res.data.message)
                            this.$router.push('/login')
                        }
                    })
                    .catch((err) => {
                        this.errors = null;
                        this.errors = err.response.data;
                    })
            }
        }
    }
</script>
