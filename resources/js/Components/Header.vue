<template>
    <section>
        <header class="header-nav">
            <h1>
                <label for="nav-toggle">
                    <span class="las la-bars"></span>
                    Bienvenue {{ userName }}
                </label>
            </h1>
        </header>
    </section>
</template>

<script>
export default {
    name: "Header",
    props: {
        userName: String
    }
}
</script>

<style scoped lang="scss">
@import "../../sass/assets/variable";

header {
    z-index: 20;
    position: fixed;
    display: flex;
    width: 100%;
    height: 90px;
    justify-content: space-between;
    top: 0;
    padding: 1rem;
    font-size: 27px;
    background-color: white;
    box-shadow: 0 14px 38px 6px rgb(74 74 74 / 13%);
    transition: width 300ms;

    & h1 {
        overflow: hidden;
    }

    & h1, label {
        cursor: pointer;
    }

    & label {
        vertical-align: sub;
    }

    & span {
        margin-right: 17px;
    }
}

section {
    height: initial !important;
}

@media only screen and (max-width: 490px) {
    header {
        z-index: 999;
        font-size: 1.4rem;
        height: 90px;
        width: 100% !important;
        left: 0 !important;
    }
}
</style>
