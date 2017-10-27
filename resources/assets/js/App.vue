<template>
    <div>

        <router-view></router-view>
    </div>
</template>
<style>
    .m-header-menu .m-menu__nav>.m-menu__item>.m-menu__link .m-menu__link-text {
        font-size: 1.4rem;
    }
    .m-topbar .m-topbar__nav.m-nav>.m-nav__item.m-topbar__user-profile>.m-nav__link .m-topbar__welcome {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .m-link {
        font-size: 1.5rem;
        font-weight: bold;
    }
    .m-widget4 .m-widget4__item .m-widget4__info .m-widget4__text{
        font-size: 1.3rem;
    }
    .m-body--fixed .m-body .m-body__content {
        min-height: 300px;
    }
</style>
<script>
    import Auth from './store/auth';

    export default{
        data(){
            return{
                auth: Auth.state
            }
        },
        components:{

        }
    }

</script>
