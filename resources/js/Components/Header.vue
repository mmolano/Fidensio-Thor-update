<template>
    <section>
        <nav class="transparent z-depth-0">
            <div class="nav-wrapper">
                <div class="brand-logo black-text">
                    <img v-bind:src="logo + 'Logos/logo-20.png'" id="icon" alt="logo"/>
                </div>
                <a href="#" data-target="mobile-demo" class="sidenav-trigger right">
                    <i class="material-icons icon-black">menu</i>
                </a>
                <ul id="nav-mobile" class="right hide-on-med-and-down">
                    <li>
                        <a href="/logout" class="deconnection white-text">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </nav>
        <ul class="sidenav" id="mobile-demo">
            <li class="nav-bar-button-parent" id="nav-bar-button-parent-1">
                <a href="/pressing/take" class="nav-bar-button">
                    <div class="nav-bar-icon" id="pret-icon"></div>
                    <div class="left-align">Prêt à être récupéré</div>
                </a>
            </li>
            <li class="nav-bar-button-parent" id="nav-bar-button-parent-2">
                <a href="/pressing/taken" class="nav-bar-button">
                    <div class="nav-bar-icon" id="recupere-icon"></div>
                    <div class="left-align">Récupéré</div>
                </a>
            </li>
            <li class="nav-bar-button-parent" id="nav-bar-button-parent-3">
                <a href="/pressing/processing" class="nav-bar-button">
                    <div class="nav-bar-icon" id="en-cours-icon"></div>
                    <div class="left-align">En cours de traitement</div>
                </a>
            </li>
            <li class="nav-bar-button-parent" id="nav-bar-button-parent-5">
                <a href="/pressing/deliver" class="nav-bar-button">
                    <div class="nav-bar-icon" id="termine-icon"></div>
                    <div class="left-align">Commande terminé</div>
                </a>
            </li>
            <li>
                <a href="/logout" class="black-text">Déconnexion</a>
            </li>
        </ul>
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
