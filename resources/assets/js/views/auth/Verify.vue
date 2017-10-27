<template>
    <div>
        <div class="container">
            <div class="row">
                <div class="col-xs-2"></div>
                <div class="col-xs-8 text-center">
                    <div class="m-login__logo">
                        <a href="#">
                            <img v-bind:src="'../metronic/app/media/img/logos/logo.png'" width="120">
                        </a>
                    </div>
                    <div class="message-box well">
                        <div class="inner">
                            <h1>Account Verification</h1>
                        </div>
                        <br>
                        <div class="message text-center" v-if="isVerified">
                            <h4>
                                Your account have been successfully verified.
                            </h4>
                            <router-link to="/login" class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air">Sign In</router-link>
                        </div>
                    </div>
                </div>
                <div class="col-xs-2"></div>
            </div>
        </div>
    </div>
</template>

<style>
.message-box {
    min-height: 300px;
    padding: 5px;
}
.message-box .inner {
    margin: 5px auto;
    border: 1px solid #ccc;
    border-radius: 4px; 
}
</style>


<script>
    import { post } from '../../helpers/api'
    import base_url from '../../helpers/constants'
    import Auth from '../../store/auth'

    export default{
        data(){
            return{
                isVerified: false,
                data: {
                    email: null,
                    code: null
                }
            }
        },
        created() {
            this.data.email = this.$router.history.current.query.email
            this.data.code = this.$router.history.current.query.code

            post(base_url+`/api/verify`, this.data)
                .then((res) => {
                    if(res.data.verified) {
                        this.isVerified = true
                    }
                })
                .catch((err) => {
                    console.log(err.response)
                })
            
        }
    }
</script>
