<template>
    <div class="loader-container">
        <div class="lds-ring">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="load-text"><b>Chargement des données...</b></div>
    </div>
</template>

<script>
export default {
    name: "Loader"
}
</script>

<style scoped lang="scss">
@import "resources/sass/assets/variable";

.loader-container {
    z-index: 9999999;
    position: fixed;
    display: flex;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    margin: auto;
    justify-content: center;
    flex-direction: column;
    height: 100%;
    background-color: #d4d0d073;

    & .lds-ring, .load-text {
        margin: 5px auto;
    }

    & .lds-ring {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;

        & div {
            box-sizing: border-box;
            display: block;
            position: absolute;
            width: 64px;
            height: 64px;
            margin: 8px;
            border: 8px solid $main;
            border-radius: 50%;
            animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            border-color: $mainBtnBack transparent transparent transparent;

            &:nth-child(1) {
                animation-delay: -0.45s;
            }

            &:nth-child(2) {
                animation-delay: -0.3s;
            }

            &:nth-child(3) {
                animation-delay: -0.15s;
            }
        }
    }
}

@keyframes lds-ring {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
