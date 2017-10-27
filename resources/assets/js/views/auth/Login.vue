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
                                        Sign In
                                    </h3>
                                </div>
                                <div v-if="flash.success" class="m-alert m-alert--outline alert alert-success alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                    <span>{{flash.success}}</span>
                                </div>
                                <p v-if="errors.statusCode === 423">
                                    Didn't get the link? 
                                    <router-link to="/request-code-resend">Resend Code</router-link>
                                </p>
                                <div v-if="flash.error" class="m-alert m-alert--outline alert alert-error alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                    <span>{{flash.error}}</span>
                                </div>
                                <form  class="m-login__form m-form" action="" @submit.prevent="login">

                                    <div class="form-group m-form__group">
                                        <input class="form-control m-input" type="text" placeholder="Email" name="email" v-model="form.email">
                                        <span v-if="errors.email" class="text-danger">This field is required.</span>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="form.password" v-model="form.password">
                                        <span v-if="errors.password" class="text-danger">This field is required.</span>
                                    </div>
                                    <div class="row m-login__form-sub">
                                        <div class="col m--align-left">
                                            <label class="m-checkbox m-checkbox--focus">
                                                <input type="checkbox" name="remember" >
                                                Remember me
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col m--align-right">
                                            <a href="javascript:;" id="m_login_forget_password" class="m-link">
                                                Forget Password ?
                                            </a>
                                        </div>
                                    </div>
                                    <div class="m-login__form-action">
                                        <button v-bind:class="{ 'disabled': isLoading }" id="m_login_signin_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">
                                            Sign In
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <div class="m-login__forget-password">
                                <div class="m-login__head">
                                    <h3 class="m-login__title">
                                        Forgotten Password ?
                                    </h3>
                                    <div class="m-login__desc">
                                        Enter your email to reset your password:
                                    </div>
                                </div>
                                <form class="m-login__form m-form" action="">

                                    <div class="form-group m-form__group">
                                        <input class="form-control m-input" type="text" placeholder="Email" name="email" id="m_email">
                                    </div>
                                    <div class="m-login__form-action">
                                        <button id="m_login_forget_password_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">
                                            Request
                                        </button>
                                        <button class="btn btn-secondary m-btn m-btn--pill m-btn--custom m-btn--air">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--center m-grid--hor m-grid__item--order-tablet-and-mobile-1	m-login__content" style="background-image: url(../metronic/app/media/img/bg/bg-5.jpg)">
                <div class="m-grid__item m-grid__item--middle">
                    <h3 class="m-login__welcome">
                        MIPAD
                    </h3>
                    <p>
                        Most Influential People of African Descent
                        
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
    import Auth from '../../store/auth'

    export default{
        data(){
            return{
                form: {
                    email: '',
                    password: ''
                },
                errors: {},
                flash: Flash.state,
                isLoading: false
            }
        },
        components:{

        },
        created() {
            Auth.initialize()
            if(Auth.state.api_token) {
                this.$router.push('/dashboard')
            }
        },
        methods: {
            login() {
                this.isLoading = true
                post(base_url+`/api/login`, this.form)
                    .then((res) => {
                        this.isLoading = false
                        
                        if(res.data.authenticated)
                        {
                            Auth.set(res.data.api_token, res.data.email, res.data.admin)
                            
                            this.$router.push('/dashboard')
                        }
                    })
                    .catch((err) => {
                        this.isLoading = false;
                        this.errors = null;
                        this.errors = err.response.data;
                        Flash.setSuccess(this.errors.message);
                    })
            }
        }
    }
</script>
