<template>
    <Vertical> <slot/> </Vertical>
    <b-modal v-model="message" hide-footer class="v-modal-custom" body-class="p-0" centered
        hide-header-close>
        <div class="text-center px-3 pt-0">
            <div class="text-end">
                <button v-if="$page.props.flash.datares != 'newjob' && $page.props.flash.datares != 4" type="button" class="btn-close text-end" @click="check()"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="mt-0">
                <div class="avatar-sm mx-auto" v-if="this.$page.props.flash.datares == 'not'">
                    <div class="avatar-title rounded-circle bg-light">
                        <i :class="'bx '+$page.props.flash.type+' h1 mb-0 text-danger'"></i>
                    </div>
                </div>
                <div class="avatar-sm mx-auto" v-else>
                    <div class="avatar-title rounded-circle bg-light">
                        <i :class="'bx '+$page.props.flash.type+' h1 mb-0 text-success'"></i>
                    </div>
                </div>

                <h5 class="mb-2 mt-2">{{$page.props.flash.message }}</h5>
            </div>
            <div class="mt-3"><Link v-if="this.$page.props.flash.datares == 'newjob'" href="/jobs" class="btn btn-success">Proceed to Jobs</Link></div>
            <div class="mt-3"><Link v-if="this.$page.props.flash.datares == 4" href="/home" class="btn btn-success">Proceed to Home</Link></div>
        </div>
        <div class="modal-footer bg-light p-3 mt-4 justify-content-center">
            <p class="mb-0 text-muted">You like our service? </p>
        </div>
    </b-modal>
</template>
<script>
import Vertical from './Vertical.vue';
export default {
    props: ['auth'],
    components : { Vertical },
    computed: {
        message() {
            return (this.$page.props.flash.message) ?  true : false;
        },
    },
    methods: {
        check(){
            this.$page.props.flash.message = '';
            this.message = false;
        }
    }
}
</script>
