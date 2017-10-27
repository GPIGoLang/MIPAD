<template>
  <div>

  </div>
</template>

<style>

</style>

<script>
    import { post } from '../../../helpers/api'
    import base_url from '../../../helpers/constants'
    import Auth from '../../../store/auth'
    import Flash from '../../../helpers/flash'

    export default {
        data() {
            return {
                test: {
                    id: null,
                    username: null,
                    score: null,
                    cut_off: null,
                    report_link: null,
                    title: null,
                    api_token: null
                },
                flash: Flash.state
            }
        },
        created() {
            Auth.initialize()
            if(this.$router.history.current.testId) {
                this.test.id = this.$router.history.current.query.testId
                this.test.username = this.$router.history.current.query.uid
                this.test.result = this.$router.history.current.query.testResult
                this.test.cut_off = this.$router.history.current.query.cutOff
                this.test.report_link = this.$router.history.current.query.pdfPath
                this.test.title = this.$router.history.current.query.testName

                post(base_url+`/api/assessment/result-store`, this.test)
                    .then((res) => {
                        if(res.data.completed) {
                            Flash.setSuccess("Thank you for completing your assessment.")
                            this.$router.push('/dashboard')
                        }
                    })
                    .catch((err) => {
                        if(err.response.status === 401) {
                            Flash.setError("Unauthorized access. Please login with your credentials")
                            Auth.remove()
                            Auth.initialize()
                            this.$router.push('/login')
                        }else{
                            console.log(err.response)
                        }
                    })
            }else{
                Flash.setError("No assessment record was found.")
                this.$router.push('/dashboard')
            }
        }
    }
</script>