<template>
    <section>

    </section>
</template>

<script>
export default {
    name: "Header",
    data() {
        return {
            logo: process.env.MIX_LOGO
        }
    }
}
</script>

<style scoped lang="scss">
#icon {
    height: 64px;
}

.deconnection {
    background-color: orange;
    transition: 0.6s;
}

.deconnection:hover {
    background-color: red;
}

.icon-black {
    color: black;
}

.brand-logo {
    height: 64px !important;
    margin-left: 0px;
}

@media screen and (min-width: 993px) {
    #icon {
        margin-left: 24px;
    }
}

@media screen and (max-width: 600px) {
    #icon {
        height: 56px;
        margin-left: 0px;
    }

    .brand-logo {
        height: 56px !important;
        margin-left: 0px;
    }
}
</style>
