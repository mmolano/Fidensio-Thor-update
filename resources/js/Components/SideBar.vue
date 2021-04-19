<template>
    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="/img/logo.png" id="icon" alt="logo"/>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="#" v-on:click="getOrders('')" v-bind:class="getActiveUrl('')">
                        <span class="las la-warehouse"></span>
                        <span>Prêt à être récupérée</span>
                    </a>
                </li>
                <li>
                    <a href="#?type=pickupDone" v-on:click="getOrders('?type=pickupDone')" v-bind:class="getActiveUrl('?type=pickupDone')">
                        <span class="las la-truck"></span>
                        <span>Récupérée</span>
                    </a>
                </li>
                <li>
                    <a href="#?type=processing" v-on:click="getOrders('?type=processing')" v-bind:class="getActiveUrl('?type=processing')">
                        <span class="las la-hourglass-half"></span>
                        <span>En cours de traitement</span>
                    </a>
                </li>
                <li>
                    <a href="#?type=finished" v-on:click="getOrders('?type=finished')" v-bind:class="getActiveUrl('?type=finished')">
                        <span class="las la-thumbs-up"></span>
                        <span>Commande terminé</span>
                    </a>
                </li>
            </ul>
            <button>
                <a href="">
                    <span class="las la-sign-out-alt"></span>
                    <span>Déconnexion</span>
                </a>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "SideBar",
    props: {
        activeUrl: String
    },
    data() {
        return {
            urlString: ''
        }
    },
    methods: {
        getOrders: function (url) {
            this.$parent.$data.typeOfStatus = url;
        },
        getActiveUrl: function (url) {
            return this.activeUrl === url ? 'active' : '';
        }
    }
}
</script>

<style scoped lang="scss">
@import "../../sass/assets/variable";

.sidebar {
    width: 385px;
    position: fixed;
    left: 0;
    top: 0;
    height: 100%;
    background: $main;
    z-index: 100;
    transition: width 300ms;

    & span {
        font-family: 'Line Awesome Free', sans-serif !important;
    }
}

.sidebar-brand {
    margin-top: 2px;
    height: 90px;
    padding: 1rem 0 1rem 2.813rem;
    color: #fff;

    & img {
        vertical-align: text-top;
        width: 250px;
        height: 44px;
        display: inline-block;
        padding-right: 1rem;
    }
}

.sidebar-menu {
    margin: 20px 0;

    & li {
        width: 100%;
        margin-bottom: 1.3rem;
        padding-left: 2rem;
    }

    & a {
        padding-left: 1rem;
        display: block;
        color: #fff;
        font-size: 1.1rem;

        & span:first-child {
            font-size: 1.5rem;
            padding-right: 1rem;
        }

        &.active {
            background: #fff;
            padding-top: 1rem;
            padding-bottom: 1rem;
            color: $main;
            border-radius: 30px 0 0 30px;
        }
    }
}

button {
    position: absolute;
    bottom: 35px;
    left: 0;
    right: 0;
    margin: auto;
    padding: 20px;
    font-size: 15px;
    border: none;
    border-radius: 20px;
    width: 100%;
    max-width: 200px;

    & a {
        color: $main !important;
        padding-left: 0 !important;

        & span:first-child {
            vertical-align: top;
        }
    }
}

@media only screen and (max-width: 1200px) {
    .sidebar {
        width: 70px;
        left: 0;
        transition: left 100ms;

        & li a {
            padding-left: 0;
        }

        & .sidebar-brand {
            margin-top: 2px;
            width: 60px;
            overflow: hidden;
        }

        & .sidebar-brand, li {
            padding-left: 1rem;
            text-align: center;
        }

        & .sidebar-brand h2 span:last-child, li a span:last-child {
            display: none;
        }
    }

    button {
        width: 82%;
        padding: 16px;

        & a {
            padding: 0 !important;
            color: black !important;

            & span {
                padding: 0;

                &:last-child {
                    display: none;
                }
            }
        }
    }
}

@media only screen and (max-width: 490px) {
    .sidebar {
        left: -100% !important;
        transition: left 300ms;
    }
}
</style>
