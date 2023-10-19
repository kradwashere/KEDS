<template>

    <Head title="Find Order" />
    <div class="layout-wrapper landing">
        
    </div>
    <View ref="view" />
</template>
<script>
    import View from './View.vue';
    export default {
        layout: null,
        components: {
            View
        },
        data() {
            return {
                currentUrl: window.location.origin,
                keyword: '',
                none: false
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
                        if(response.data == ''){
                            this.none = true;
                        }else if(response) {
                             this.none = false;
                            this.$refs.view.show(response.data.data);
                        }
                    })
                    .catch(err => console.log('wew'));
            },

        }
    }

</script>
