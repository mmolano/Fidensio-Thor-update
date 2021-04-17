<template>
    <section>
        <header class="header-nav">
            <h1>
                <label for="nav-toggle">
                    <span class="las la-bars"></span>
                    Bienvenue Miguel
                </label>
                <!--                TODO: mettre le nom du mec qui est log   -->
            </h1>
        </header>
    </section>
</template>

<script>
export default {
    name: "Header"
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
    font-size: 30px;
    background-color: white;
    box-shadow: 0 14px 38px 6px rgb(74 74 74 / 13%);
    transition: width 300ms;

    & h1, label {
        cursor: pointer;
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
        font-size: 20px;
        height: auto;
    }
}
</style>
