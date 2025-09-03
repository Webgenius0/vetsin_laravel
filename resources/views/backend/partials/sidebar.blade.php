<div id="kt_aside" class="aside aside-default aside-hoverable " data-kt-drawer="true" data-kt-drawer-name="aside"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start"
    data-kt-drawer-toggle="#kt_aside_toggle">

    <!--begin::Brand-->
    <div class="px-10 pb-5 aside-logo flex-column-auto pt-9" id="kt_aside_logo">
        <!--begin::Logo-->
        <a href="{{ route('admin.dashboard') }}">
            <img alt="Logo" src="{{ asset($systemSetting->logo ?? 'backend/media/logos/logo-default.svg') }}"
                class="max-h-50px logo-default theme-light-show" />
            {{-- <img alt="Logo" src="{{ asset($systemSetting->logo ?? 'backend/media/logos/logo-default.svg') }}"
                class="max-h-50px logo-default theme-dark-show" /> --}}
            <img alt="Logo" src="{{ asset($systemSetting->logo ?? 'backend/media/logos/logo-default.svg') }}"
                class="max-h-50px logo-minimize" />
        </a>
        <!--end::Logo-->
    </div>
    <!--end::Brand-->

    <!--begin::Aside menu-->
    <div class="aside-menu flex-column-fluid ps-3 pe-1">
        <!--begin::Aside Menu-->

        <!--begin::Menu-->
        <div class="my-5 menu menu-sub-indention menu-column menu-rounded menu-title-gray-600 menu-icon-gray-400 menu-active-bg menu-state-primary menu-arrow-gray-500 fw-semibold fs-6 mt-lg-2 mb-lg-0"
            id="kt_aside_menu" data-kt-menu="true">

            <div class="mx-4 hover-scroll-y" id="kt_aside_menu_wrapper" data-kt-scroll="true"
                data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
                data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="20px"
                data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer">

                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-element-11 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                <div class="menu-item">
                    <div class="menu-content">
                        <div class="mx-1 my-2 separator"></div>
                    </div>
                </div>

                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}"
                        href="{{ route('admin.faqs.index') }}">
                        <span class="menu-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" width="24"
                                height="24" stroke-width="2">
                                <path
                                    d="M19.875 6.27c.7 .398 1.13 1.143 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z">
                                </path>
                                <path d="M12 16v.01"></path>
                                <path d="M12 13a2 2 0 0 0 .914 -3.782a1.98 1.98 0 0 0 -2.414 .483"></path>
                            </svg>
                        </span>
                        <span class="menu-title">FAQ</span>
                    </a>
                </div>

                <div data-kt-menu-trigger="click"
                    class="menu-item {{ request()->routeIs(['profile.setting', 'stripe.setting', 'paypal.setting', 'dynamic_page.*', 'system.index', 'mail.setting', 'social.index']) ? 'active show' : '' }} menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="fa-solid fa-gear fs-2"></i>
                        </span>
                        <span class="menu-title">Setting</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a href="{{ route('profile.setting') }}"
                                class="menu-link {{ request()->routeIs('profile.setting') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Profile Setting</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('system.index') }}"
                                class="menu-link {{ request()->routeIs('system.index') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">System Setting</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('dynamic_page.index') }}"
                                class="menu-link {{ request()->routeIs(['dynamic_page.index', 'dynamic_page.create', 'dynamic_page.update']) ? 'active show' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Dynamic Page</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('mail.setting') }}"
                                class="menu-link {{ request()->routeIs('mail.setting') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Mail Setting</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('social.index') }}"
                                class="menu-link {{ request()->routeIs('social.index') ? 'active' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Social Media</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->routeIs([
                        'age_preference.*',
                        'prefered_property_type.*',
                        'choose_your_identity.*',
                        'budget.*',
                        'admin.ideal_connection.*',
                        'admin.willing_to_relocate.*'
                    ]) ? 'active show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="fa-solid fa-sliders fs-2"></i>
                        </span>
                        <span class="menu-title">Dynamic Input</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">

                        <!-- Ideal Connection -->
                        <div class="menu-item">
                            <a href="{{ route('admin.ideal_connection.index') }}"
                                class="menu-link {{ request()->routeIs(['admin.ideal_connection.index', 'admin.ideal_connection.create', 'admin.ideal_connection.edit']) ? 'active' : '' }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Ideal Connection</span>
                            </a>
                        </div>

                        <!-- Willing to Relocate -->
                        <div class="menu-item">
                            <a href="{{ route('admin.willing_to_relocate.index') }}"
                                class="menu-link {{ request()->routeIs(['admin.willing_to_relocate.index', 'admin.willing_to_relocate.create', 'admin.willing_to_relocate.edit']) ? 'active' : '' }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Willing to Relocate</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('age_preference.index') }}"
                                class="menu-link {{ request()->routeIs(['age_preference.index', 'age_preference.create', 'age_preference.edit']) ? 'active show' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Age Preference</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('prefered_property_type.index') }}"
                                class="menu-link {{ request()->routeIs(['prefered_property_type.index', 'prefered_property_type.create', 'prefered_property_type.edit']) ? 'active show' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Prefered Property Type</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('choose_your_identity.index') }}"
                                class="menu-link {{ request()->routeIs(['choose_your_identity.index', 'choose_your_identity.create', 'choose_your_identity.edit']) ? 'active show' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Choose Your Identity</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="{{ route('budget.index') }}"
                                class="menu-link {{ request()->routeIs(['budget.index', 'budget.create', 'budget.edit']) ? 'active show' : '' }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title">Budget</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div data-kt-menu-trigger="click"
                    class="menu-item {{ request()->routeIs(['fun-prompt1s.*', 'fun-prompt2s.*', 'fun-prompt3s.*']) ? 'active show' : '' }} menu-accordion">
                    <div data-kt-menu-trigger="click"
                        class="menu-item {{ request()->routeIs('fun-prompts.*') ? 'active show' : '' }} menu-accordion">
                        <span class="menu-link">
                            <span class="menu-icon"><i class="fa-solid fa-face-grin-stars fs-2"></i></span>
                            <span class="menu-title">Fun Prompts</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a href="{{ route('fun-prompts.index', 'fun1') }}"
                                    class="menu-link {{ request()->is('fun-prompts/fun1*') ? 'active show' : '' }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Fun Prompt 1</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a href="{{ route('fun-prompts.index', 'fun2') }}"
                                    class="menu-link {{ request()->is('fun-prompts/fun2*') ? 'active show' : '' }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Fun Prompt 2</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a href="{{ route('fun-prompts.index', 'fun3') }}"
                                    class="menu-link {{ request()->is('fun-prompts/fun3*') ? 'active show' : '' }}">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Fun Prompt 3</span>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
                <div data-kt-menu-trigger="click"
                    class="menu-item {{ request()->routeIs('favorite-investing-markets.*') ? 'active show' : '' }} menu-accordion">
                    <span class="menu-link">
                        <span class="menu-icon"><i class="fa-solid fa-chart-line fs-2"></i></span>
                        <span class="menu-title">Favorite Investing Markets</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a href="{{ route('favorite-investing-markets.index') }}"
                                class="menu-link {{ request()->is('favorite-investing-markets*') ? 'active show' : '' }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Manage Markets</span>
                            </a>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>
