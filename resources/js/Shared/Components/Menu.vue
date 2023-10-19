<template>
    <div>
        <div class="app-menu navbar-menu">
            <div class="navbar-brand-box">
                <Link href='/' class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="/images/lenin.png" alt="" height="30" />
                    </span>
                    <span class="logo-lg">
                        <img src="/images/logo-dark.png" alt="" height="17" />
                    </span>
                </Link>
                <Link href="/" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="/images/lenin.png" alt="" height="30" />
                    </span>
                    <span class="logo-lg">
                        <img src="/images/logo-light.png" alt="" height="17" />
                    </span>
                </Link>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover" @click="initActiveMenu">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

             <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <Link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Home') }" href="/home">
                        <i class='bx bxs-dashboard'></i>
                        <span data-key="t-dashboards">Home</span>
                    </Link>
                </li>
                 <li class="nav-item" v-if="$page.props.auth.data.role == 'Administrator'">
                    <Link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Bookings') }" href="/bookings">
                        <i class='ri-menu-add-line'></i>
                        <span data-key="t-students">Bookings</span>
                    </Link>
                </li>
                <li class="nav-item" v-if="$page.props.auth.data.role == 'Administrator'">
                    <Link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Customers') }" href="/customers">
                        <i class='ri-team-line'></i>
                        <span data-key="t-students">Customers</span>
                    </Link>
                </li>
                <li class="nav-item" v-if="$page.props.auth.data.role == 'Administrator'">
                    <Link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Buildings') }" href="/buildings">
                        <i class='ri-building-2-line'></i>
                        <span data-key="t-students">Buildings</span>
                    </Link>
                </li>
                <li class="nav-item" v-if="$page.props.auth.data.role == 'Administrator'">
                    <Link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Staffs') }" href="/staffs">
                        <i class='bx bx-user-circle' ></i>
                        <span data-key="t-students">Staffs</span>
                    </Link>
                </li>
                <li class="nav-item" v-if="$page.props.auth.data.role == 'Administrator'">
                    <Link class="nav-link menu-link" :class="{'active': $page.component.startsWith('Lists') }" href="/lists">
                        <i class='bx bx-list-ul' ></i>
                        <span data-key="t-students">Lists</span>
                    </Link>
                </li>
            </ul>
            <div class="sidebar-background"></div>
        </div>
        <div class="vertical-overlay" id="overlay"></div>
    </div>
</template>