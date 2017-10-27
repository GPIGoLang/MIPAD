<template>
    <div>
        <mast></mast>
        
                <!-- begin:: Page -->
		        <div class="m-grid m-grid--hor m-grid--root m-page">
                    <!-- begin::Body -->
			        <div class="m-grid__item m-grid__item--fluid m-grid m-grid m-grid--hor m-container m-container--responsive m-container--xxl">
                        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body">
                            <navigation></navigation>
                            <div class="container">
						        <div class="row">
                                    <div class="col-lg-12">
                                        <!-- BEGIN: Subheader -->
                                        <div class="m-subheader ">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-auto">
                                                    <h3 class="m-subheader__title ">
                                                        Dashboard
                                                    </h3>
                                                    <div v-if="flash.success" class="m-alert m-alert--outline alert alert-success alert-dismissible" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                                        <span>{{flash.success}}</span>
                                                    </div>
                                                    <div v-if="flash.error" class="m-alert m-alert--outline alert alert-error alert-dismissible" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                                                        <span>{{flash.error}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END: Subheader -->
                                        <div class="m-content">
                                            <!--Begin::Main Portlet-->
								            <div class="m-portlet">
                                                <div class="m-portlet__body  m-portlet__body--no-padding">
										            <div class="row m-row--no-padding m-row--col-separator-xl">
                                                        <div class="col-xl-4"  v-show="nominees">
                                                            <!--begin:: Widgets/Profit Share-->
                                                            <div class="m-widget14">
                                                                <div class="m-widget14__header">
                                                                    <h3 class="m-widget14__title">
                                                                        Nominees
                                                                    </h3>
                                                                    <span class="m-widget14__desc">
                                                                        
                                                                    </span>
                                                                </div>
                                                                <div class="row  align-items-center" >
                                                                    <div class="col">
                                                                        <div class="m-widget14__legends" v-if="!nominees">
                                                                            <h3>{{ nominees_message }}</h3>
                                                                        </div>
                                                                        <div class="m-widget14__legends" v-show="nominees">
                                                                            <div class="m-widget14__legend" v-for="nominee in nominees">
                                                                                <span class="m-widget14__legend-bullet m--bg-accent"></span>
                                                                                <span class="m-widget14__legend-text">
                                                                                    {{nominee.fullname}}
                                                                                </span>
                                                                                <a class="btn btn-success btn-xs pull-right" @click="getNomineeAssessmentStatus(nominee)">View</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--end:: Widgets/Profit Share-->
											            </div>
                                                        <div class="col-xl-4">
                                                            <!--begin:: Widgets/Profit Share-->
                                                            <div class="m-widget14">
                                                                <div class="m-widget14__header">
                                                                    <h3 class="m-widget14__title">
                                                                        Assessments Status
                                                                    </h3>
                                                                    <span class="m-widget14__desc">
                                                                        
                                                                    </span>
                                                                </div>
                                                                <div class="row  align-items-center" >
                                                                    <div class="col">
                                                                        <div class="m-widget14__legends" v-if="!assessments">
                                                                            <h3>{{ assessment_message }}</h3>
                                                                        </div>
                                                                        <div class="m-widget14__legends" v-if="assessments">
                                                                            <div class="m-widget14__legend" v-for="assessment in assessments">
                                                                                <span class="m-widget14__legend-bullet m--bg-accent"></span>
                                                                                <span class="m-widget14__legend-text">
                                                                                    {{assessment.title}}
                                                                                </span>
                                                                                <a class="btn btn-default btn-xs pull-right" href="javascript:;" v-if="assessment.status == 'closed'" @click="displayAssessmentResult(assessment)">
                                                                                    <i class="la la-eye"></i> Result
                                                                                </a>
                                                                                <a class="btn btn-success btn-xs pull-right" v-bind:href="assessment.report_link" v-if="!auth.state.admin && assessment.status === 'closed'">View</a>
                                                                                <a class="btn btn-success btn-xs pull-right" v-bind:href="assessment.test_link" v-if="auth.state.admin !== 'true' && assessment.status === 'open'">Take</a>
                                                                                <a class="btn btn-success btn-xs pull-right" href="javascript:;" v-if="auth.state.admin === 'true' && assessment.status === 'open'">Not taken</a>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--end:: Widgets/Profit Share-->
											            </div>
                                                        <div class="col-xl-4" v-if="assessmentResult">
                                                            <!--begin:: Widgets/Latest Updates-->
                                                            <div class="m-portlet m-portlet--full-height m-portlet--fit  m-portlet--rounded">
                                                                <div class="m-portlet__head">
                                                                    <div class="m-portlet__head-caption">
                                                                        <div class="m-portlet__head-title">
                                                                            <h3 class="m-portlet__head-text">
                                                                                {{assessmentResult.title}} Result
                                                                            </h3>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="m-portlet__body">
                                                                    <div class="m-widget4 m-widget4--chart-bottom" style="min-height: 120px">
                                                                        <div class="m-widget4__item">
                                                                            <div class="m-widget4__ext">

                                                                            </div>
                                                                            <div class="m-widget4__info">
                                                                                <span class="m-widget4__text">
                                                                                    Cut-off
                                                                                </span>
                                                                            </div>
                                                                            <div class="m-widget4__ext">
                                                                                <span class="m-widget4__number m--font-accent">
                                                                                    {{assessmentResult.cut_off}}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="m-widget4__item">
                                                                            <div class="m-widget4__ext">

                                                                            </div>
                                                                            <div class="m-widget4__info">
                                                                                <span class="m-widget4__text">
                                                                                    Score
                                                                                </span>
                                                                            </div>
                                                                            <div class="m-widget4__ext">
                                                                                <span class="m-widget4__number m--font-accent">
                                                                                    {{assessmentResult.score}}
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="m-widget4__item" v-if="auth.state.admin === 'true'">
                                                                            <div class="m-widget4__ext">

                                                                            </div>
                                                                            <div class="m-widget4__info">
                                                                                <span class="m-widget4__text">
                                                                                    Report Link
                                                                                </span>
                                                                            </div>
                                                                            <div class="m-widget4__ext">
                                                                                <span class="m-widget4__number m--font-accent">
                                                                                    <a v-bind:href="assessmentResult.report_link" target="_blank">Report</a>
                                                                                </span>
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--end:: Widgets/Latest Updates-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            

    </div>
</template>

<script>
    import { post } from '../../helpers/api'
    import base_url from '../../helpers/constants'
    import Navigation from '../user/layouts/Navigation.vue'
    import Mast from '../user/layouts/Mast.vue'
    import Auth from '../../store/auth'
    import Flash from '../../helpers/flash'

    export default{
        data(){
            return{
                nominees: null,
                nominee: null,
                assessments: null,
                assessmentResult: null,
                assessment_message: null,
                nominees_message: null,
                flash: Flash,
                auth: Auth
            }
        },
        components:{
            Navigation, Mast
        },
        created() {
            Auth.initialize()
            if(!Auth.state.api_token) {
                this.$router.push('/login')
            }

            this.getAssessments()

            this.getNominees()
        },
        methods: {
            getAssessments() {
                Auth.initialize();
                this.message = '';
                let payload = {
                    email: Auth.state.email
                }

                post(base_url+`/api/assessments`, payload)
                    .then((res) => {
                        if(!res.data.found) {
                            this.assessment_message = res.data.message;
                        }else{
                            this.assessments = res.data.assessments
                        }
                    })
                    .catch((err) => {
                        if(err.response.status === 401) {
                            Auth.remove()
                            Auth.initialize()
                            this.$router.push('/login')
                        }
                    })
            },

            getNominees() {
                let payload = {
                    email: Auth.state.email
                }
                post(base_url+`/api/nominations`, payload)
                    .then((res) => {
                        if(!res.data.found) {
                            this.assessment_message = res.data.message;
                        }else{
                            this.nominees = res.data.nominees
                        }
                    })
                    .catch((err) => {
                        if(err.response.status === 401) {
                            Auth.remove()
                            Auth.initialize()
                            this.$router.push('/login')
                        }
                    })
            },

            getNomineeAssessmentStatus(nominee) {
                this.nominee = nominee
                let payload = {
                    email: nominee.email,
                    api_token: Auth.state.api_token
                }
                post(base_url+`/api/nominee/assessments`, payload)
                    .then((res) => {
                        if(!res.data.found) {
                            this.assessment_message = res.data.message;
                        }else{
                            this.assessments = res.data.tests
                        }
                    })
                    .catch((err) => {
                        if(err.response.status === 401) {
                            Auth.remove()
                            Auth.initialize()
                            this.$router.push('/login')
                        }
                    })
            },

            displayAssessmentResult(assessment) {
                let payload = {
                    email: this.nominee ? this.nominee.email : Auth.state.email,
                    api_token: Auth.state.api_token,
                    test_id: assessment.test_id
                }
                post(base_url+`/api/assessment/result-display`, payload)
                    .then((res) => {
                        if(res.data.found){
                            this.assessmentResult = res.data.test
                        }
                    })
                    .catch((err) => {
                        if(err.response.status === 401) {
                            Auth.remove()
                            Auth.initialize()
                            this.$router.push('/login')
                        }
                    })
            }
        }
    }
</script>
