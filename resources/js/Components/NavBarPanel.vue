<template>
    <section class="nav-bar">
        <div class="card hoverable" id="nav-bar">
            <div class="nav-bar-global">
                <ul>
                    <li class="nav-wrapper">
                        <form>
                            <div class="input-field">
                                <input id="search" type="search" placeholder="Rechercher" required>
                                <label class="label-icon" for="search">
                                    <i class="material-icons">search</i>
                                </label>
                            </div>
                        </form>
                    </li>
                    <li class="nav-bar-button-parent" id="nav-bar-button-parent-1">
                        <a href="/pressing/take" class="nav-bar-button">
                            <i class="nav-bar-icon" id="i-ready"></i>
                            <div class="left-align">Prêt à être récupéré</div>
                        </a>
                    </li>
                    <li class="nav-bar-button-parent" id="nav-bar-button-parent-2">
                        <a href="/pressing/taken" class="nav-bar-button">
                            <i class="nav-bar-icon" id="i-delivery"></i>
                            <div class="left-align">Récupéré</div>
                        </a>
                    </li>
                    <li class="nav-bar-button-parent" id="nav-bar-button-parent-3">
                        <a href="/pressing/processing" class="nav-bar-button">
                            <i class="nav-bar-icon" id="i-processing"></i>
                            <div class="left-align">En cours de traitement</div>
                        </a>
                    </li>
                    <li class="nav-bar-button-parent" id="nav-bar-button-parent-5">
                        <a href="/pressing/deliver" class="nav-bar-button">
                            <i class="nav-bar-icon" id="i-finished"></i>
                            <div class="left-align">Commande terminé</div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "NavBarPanel"
}
</script>

<style scoped lang="scss">
#nav-bar {
    z-index: 1;
    overflow: hidden;
    height: 310px;
    display: flex;
    flex-direction: column;
    transition: 0.3s;
    border-radius: 30px;
}

#nav-bar-button-parent-1 {
    border-left: orange solid 5px;
    background-color: #F3F3F3;
}

.nav-bar-global {
    margin-bottom: 50px;
    margin-top: 25px;
    margin-right: 25px;
}

.nav-bar {
    flex: 1;
    margin-right: 1%;
    margin-left: 1%;
}

.nav-bar-button-parent {
    border-left: #00AEA1 solid 5px;
    padding-left: 25px;
    margin-top: 5px;
    margin-bottom: 5px;
    border-radius: 0 20px 20px 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 40px;
    color: grey;

    a {
        color: inherit;
    }

    :hover {
        background-color: #F8F8F8;
    }
}

.nav-bar-button {
    display: flex;
    align-items: center;
}

.card-content {
    padding-top: 0px;
}

.inside-form {
    background-color: #F8F8F8;
    padding: 20px;
    border-radius: 20px;
}

#open-menu {
    display: none;
}

.nav-bar-icon {
    background-size: contain;
    background-repeat: no-repeat;
    width: 25px;
    height: 25px;
    display: block;
    margin: 14.5px 14.5px 14.5px 0;
}

#i-ready {
    background-image: url(/img/ready.png);
}

#i-delivery {
    background-image: url(/img/delivery.png);
}

#i-processing {
    background-image: url(/img/hourglass.png);
}

#i-finished {
    background-image: url(/img/enjoy.png);
}

@media screen and (min-width: 1440px) {
    .nav-bar {
        flex: 2;
        margin-right: 1%;
    }
}

@media screen and (max-width: 900px) {
    .nav-bar {
        display: none;
    }

    .nav-bar-icon {
        display: none;
    }
}
</style>
