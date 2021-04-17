<template>
    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="img/logo.png" id="icon" alt="logo"/>
        </div>
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="" class="active">
                        <span class="las la-warehouse"></span>
                        <span>Prêt à être récupérée</span>
                    </a>
                </li>
                <li>
                    <a href="">
                        <span class="las la-truck"></span>
                        <span>Récupérée</span>
                    </a>
                </li>
                <li>
                    <a href="">
                        <span class="las la-hourglass-half"></span>
                        <span>En cours de traitement</span>
                    </a>
                </li>
                <li>
                    <a href="/pressing/deliver">
                        <span class="las la-thumbs-up"></span>
                        <span>Commande terminé</span>
                    </a>
                </li>
            </ul>
        </div>
        <button><a href="">Déconnexion</a></button>
    </div>
</template>

<script>
export default {
    name: "SideBar",
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
    margin-top: 20px;
    height: 90px;
    padding: 1rem 0 1rem 2rem;
    color: #fff;

    & img {
        width: 284px;
        height: 47px;
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
        color: $main;
    }
}

@media only screen and (max-width: 1200px) {
    .sidebar {
        width: 70px;

        & li a {
            padding-left: 0;
        }

        & .sidebar-brand {
            width: 70px;
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
}
</style>
