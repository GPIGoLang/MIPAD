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
                                        {{form.fullname}}, welcome to GPI
                                    </h3>
                                    <div class="m-login__desc">
                                        {{messagebox}}
                                    </div>
                                </div>
                            
                                <form class="m-login__form m-form" action="" @submit.prevent="testSetUp">
                                    <div class="form-group m-form__group">
                                        <input type="text" class="form-control m-input" v-model="form.email" ref="email" placeholder="Username" disabled>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <input type="password" class="form-control m-input" v-model="form.password" ref="password" placeholder="Password">
                                        <span v-if="errors.password" class="text-danger">{{errors.password[0]}}</span>
                                    </div>

                                    <div class="form-group m-form__group">
                                        <input type="password" class="form-control m-input" v-model="form.password_confirmation" ref="password_confirmation" placeholder="Confirm Password">
                                    </div>
                                    
                                    <div v-if="flash.success" ref="messagebox" class="m-alert m-alert--outline alert alert-success alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                        <span>{{flash.success}}</span>
                                    </div>
                                    <div class="m-login__form-action">
                                        <button id="m_login_signup_submit" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">
                                            Get Started
                                            <span v-if="isLoading">
                                                <div class="m-loader m-loader--brand"></div>
                                            </span>
                                        </button>
                                        <router-link to="/login" class="btn btn-secondary m-btn m-btn--pill m-btn--custom m-btn--air">Cancel</router-link>
                                    </div><br><br><br><br>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="m-stack__item m-stack__item--center">
                        <div class="m-login__account">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--center m-grid--hor m-grid__item--order-tablet-and-mobile-1	m-login__content" style="background-image: url(../metronic/app/media/img/bg/bg-5.jpg)">
                <div class="m-grid__item m-grid__item--middle">
                    <h3 class="m-login__welcome">
                        MIPAD
                    </h3>
                    <p class="m-login__msg">
                        Most Influential People of African Descent
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import { post, get } from '../../helpers/api'
    import Flash from '../../helpers/flash'
    import base_url from '../../helpers/constants'

    export default{
        data(){
            return{
                form: {
                    password: '',
                    password_confirmation: '',
                    email: '',
                    orgrefid: null,
                    cat_id: null,
                    code: null,
                    fullname: null
                },
                isLoading: false,
                flash: Flash.state,
                errors: {},
                messagebox: null
            }
        },
        mounted: function() {

            // Check if this user is a nominated user
            if(this.$router.history.current.query.org_refid) {
                this.form.orgrefid = this.$router.history.current.query.org_refid
                this.form.cat_id = this.$router.history.current.query.cat_id
                this.form.code = this.$router.history.current.query.activation
                this.form.email = this.$router.history.current.query.email

                post(base_url+`/api/org-chk-refid`, {org_refid: this.form.orgrefid, activation_code: this.form.code, email: this.form.email})
                    .then((res) => {
                        if(res.data.found === true){
                            this.messagebox = "To get you all setup, please fill the field below. All your data is protected."
                            this.form.fullname = res.data.fullname
                            this.form.email = res.data.email
                        }
                    })
                    .catch((err) => {
                        console.log(err.response)
                    })
            }else{
                this.$router.push('/login')
            }
        },
        methods: {
            testSetUp() {
                this.isLoading = true;
                post(base_url+`/api/nominee/test-setup`, this.form)
                .then((res) => {
                    this.isLoading = false;
                    if(res.data.registered) {
                        let message = "Your assessments have been setup. Login with your MIPAD credentials and take your tests.";
                        Flash.setSuccess(message);
                        setTimeout(() => {
                            this.$router.push('/login');
                        }, 100)
                    }
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.errors = err.response.data

                    console.log(err.response)
                })
            }
        }
    }
</script>
