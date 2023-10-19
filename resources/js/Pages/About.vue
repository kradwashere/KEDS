<template>

    <Head title="Find Order" />
    <div class="layout-wrapper landing">
        <nav class="navbar navbar-expand-lg navbar-landing fixed-top job-navbar bg-white" id="navbar">
            <b-container fluid class="custom-container">
                <b-link class="navbar-brand" href="/">
                    <img src="/images/logo-dark.png" class="card-logo card-logo-dark" alt="logo dark" height="17">
                    <img src="/images/logo-light.png" class="card-logo card-logo-light" alt="logo light" height="17">
                </b-link>
                <button class="navbar-toggler py-0 fs-20 text-body" type="button" v-b-toggle.navbarSupportedContent>
                    <i class="mdi mdi-menu"></i>
                </button>

                <b-collapse class="navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                         <li class="nav-item">
                            <Link class="nav-link" :class="{ 'active': $page.url === '/' }" href="/">Home</Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" :class="{ 'active': $page.url === '/service/lists' }" href="/service/lists">Services</Link>
                        </li>
                        <li class="nav-item">
                            <Link class="nav-link" :class="{ 'active': $page.url === '/about' }" href="/about">About</Link>
                        </li>
                    </ul>

                    <div class="">
                        <Link href="/login" class="btn btn-soft-primary">
                        <i class="ri-user-3-line align-bottom me-1"></i> Login
                        </Link>
                    </div>
                </b-collapse>

            </b-container>
        </nav>
        <section class="section job-hero-section bg-light pb-0" id="hero">
            <b-container class="mb-5">
                <b-row class="justify-content-between align-items-center">
                    <div class="row align-items-center gy-4">
                        <div class="col-lg-6 order-2 order-lg-1 order-2">
                             <div>
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.810004180772!2d122.0743084147071!3d6.913306520426996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3250419220a70b99%3A0x5a4a694020324efc!2sWEEFIX%20I.T.%20CARE%20CENTER!5e0!3m2!1sen!2sph!4v1681259863334!5m2!1sen!2sph" width="500" height="200" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                <p>Visit us now and we will be happy to serve you! <br /><br />
                                🏪 WeeFix I.T. Care Center - Agan Branch <br />
                                📍 2nd Floor, FLK Building, Nuñez Extension, Mayor Vitaliano Agan Avenue, Tetuan, Zamboanga City<br /><br />

                                ☎ (062) 975-3373<br />
                                📱 (0927) 717-5421</p>
                               <p class="fw-bold"> Follow us!<br/>
                                <a href="https://www.instagram.com/lenincomputers"> <img src="/images/icons/ig.png" width="30" height="30"> </a>
                                <a href="https://www.tiktok.com/@lenincomputers"> <img src="/images/icons/tik.png" width="30" height="30"> </a>
                                <a href="https://www.youtube.com/@lenintv4728">  <img src="/images/icons/yt.png" width="30" height="30"> </a>
                                <a href="https://shopee.ph/lcsi.zamboanga">  <img src="/images/icons/shopee.png" width="30" height="30"> </a></p>
                            </div>
                          
                        </div>
                        <div class="col-sm-7 col-lg-6 order-1 order-lg-2 col-10 order-1 ms-auto">
                             <div>
                                <h1 class="text-success fw-bold text-uppercase" >About</h1>
                                <p class="mb-4">WeeFix I.T. Care Center is an authorized service provider
                                    of:</p>
                                <ul class="list-unstyled">
                                    <li class="py-1 fs-13" v-for="(list, index) of brands" :key="index">
                                        <i class="mdi mdi-circle-medium me-1 text-muted align-middle"></i>
                                        {{ list.name}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </b-row>
            </b-container>
        </section>
    </div>
    <View ref="view" />
</template>
<script>
    import View from './View.vue';
    export default {
        layout: null,
        props: ['brands'],
        components: {
            View
        },
        data() {
            return {
                currentUrl: window.location.origin,
                keyword: '',
            }
        },

        methods: {

            search() {
                axios.get(this.currentUrl + '/search', {
                        params: {
                            keyword: this.keyword,
                            search: 'public'
                        }
                    })
                    .then(response => {
                        if (response) {
                            this.$refs.view.show(response.data.data);
                        }
                    })
                    .catch(err => console.log(err));
            },

        }
    }

</script>
