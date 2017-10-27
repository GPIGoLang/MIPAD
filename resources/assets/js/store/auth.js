export default {
    state: {
        api_token: null,
        admin: null,
        email: null
    },

    initialize() {
        this.state.api_token = localStorage.getItem('api_token')
        this.state.admin = localStorage.getItem('admin')
        this.state.email = localStorage.getItem('email')
    },

    set(api_token, email, admin) {

        localStorage.setItem('api_token', api_token)

        localStorage.setItem('email', email)

        if (admin !== null) {
            localStorage.setItem('admin', admin)
        }

        this.initialize()
    },

    remove() {
        localStorage.removeItem('api_token')

        localStorage.removeItem('email')

        localStorage.removeItem('admin')

        this.initialize()
    }
}