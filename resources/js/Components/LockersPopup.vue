<template>

</template>

<script>
import axios from "axios";

export default {
    name: "LockersPopup",
    props: {
        orderId: Number,
        status: Number,
    },
    data() {
        return {
            lockerCode: Number
        }
    },
    methods: {
        sendData: function () {
            axios.post('/update', {
                'orderId': orderId,
                'status': status
            }).then(res => {
                if (res.status === 200) {
                    this.$parent.$data.message = res.data
                    this.loadOrders();
                }
            }).catch(err => {
                this.$parent.$data.message = err.response.data
            });
        },
        hidePopup: function () {
            this.$parent.$data.showLockers = false;
        }
    }
}
</script>

<style scoped lang="scss">
@import "../../sass/assets/variable";

section {
    height: initial !important;
}

</style>
