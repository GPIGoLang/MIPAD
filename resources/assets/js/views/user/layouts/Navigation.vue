<template>
  <div>
    <div class="m-grid__item m-body__nav">
    <div class="m-stack m-stack--ver m-stack--desktop">
        <!-- begin::Horizontal Menu -->
        <div class="m-stack__item m-stack__item--middle m-stack__item--fluid">
            <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light " id="m_aside_header_menu_mobile_close_btn">
                <i class="la la-close"></i>
            </button>
            <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-light m-aside-header-menu-mobile--submenu-skin-light "  >
                <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">
                    <li class="m-menu__item  m-menu__item--active"  aria-haspopup="false">
                        <a  href="index.html" class="m-menu__link ">
                            <span class="m-menu__link-text">
                                <router-link to="/dashboard">Dashboard</router-link>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- end::Horizontal Menu -->
        <!--begin::Search-->
        <div class="m-stack__item m-stack__item--middle m-dropdown m-dropdown--arrow m-dropdown--large m-dropdown--mobile-full-width m-dropdown--align-right m-dropdown--skin-light m-header-search m-header-search--expandable m-header-search--skin-" id="m_quicksearch" data-search-type="default">
            <!--begin::Search Form -->
            <form class="m-header-search__form">
                <div class="m-header-search__wrapper">
                    <span class="m-header-search__icon-search" id="m_quicksearch_search">
                        <i class="la la-search"></i>
                    </span>
                    <span class="m-header-search__input-wrapper">
                        <input autocomplete="off" type="text" name="q" class="m-header-search__input" value="" placeholder="Search..." id="m_quicksearch_input">
                    </span>
                    <span class="m-header-search__icon-close" id="m_quicksearch_close">
                        <i class="la la-remove"></i>
                    </span>
                    <span class="m-header-search__icon-cancel" id="m_quicksearch_cancel">
                        <i class="la la-times"></i>
                    </span>
                </div>
            </form>
            <!--end::Search Form -->
            <!--begin::Search Results -->
            <div class="m-dropdown__wrapper">
                <div class="m-dropdown__arrow m-dropdown__arrow--center"></div>
                <div class="m-dropdown__inner">
                    <div class="m-dropdown__body">
                        <div class="m-dropdown__scrollable m-scrollable" data-scrollable="true" data-max-height="300" data-mobile-max-height="200">
                            <div class="m-dropdown__content m-list-search m-list-search--skin-light"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Search Results -->
        </div>
        <!--end::Search-->
    </div>
    </div>
  </div>
</template>

<style>

</style>

<script>
    export default {

    }
</script>