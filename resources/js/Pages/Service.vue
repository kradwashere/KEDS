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
            <b-container>
                <b-row class="justify-content-between align-items-center">
                    <div class="row align-items-center gy-4">
                        <div class="col-lg-6 order-2 order-lg-1 order-2">
                            <div class="text-muted">
                                <h1 class="text-success fw-bold text-uppercase ">Services</h1>
                                <h5 class="mb-3">List of services available:</h5>
                                <p class="mb-4 ff-secondary">Lenin Mobile Store is a subsidiary of Lenin Computer Systems, Inc., one of the major I.T. retailer of Zamboanga Peninsula.</p>
                                <div class="row">
                                    <ul class="list-group list-group-flush border-dashed px-3">
                                        <li class="list-group-item ps-0"
                                            v-for="(service, index) of services" :key="index">
                                            <div class="d-flex align-items-start">
                                                
                                                <div class="flex-grow-1">
                                                    <label class="form-check-label mb-0 ps-2"
                                                        :for="service.id">{{ service.name }}</label>
                                                </div>
                                                <div class="flex-shrink-0 ms-2">
                                                    <p class="text-muted fs-12 mb-0">₱{{ service.amount }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-7 col-lg-6 order-1 order-lg-2 col-10 order-1 ms-auto">
                            <div><img src="/images/home.png" alt="" class="img-fluid"></div>
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
        props: ['services'],
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
