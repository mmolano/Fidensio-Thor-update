<template>
    <section>
        <div id="popup-container">
            <div id="popup" class="card z-depth-4">
                <div class="card-content">
                    <div class="popup-tables-container">
                        <div id="popup-div-one">
                            <div class="popup-row">
                                <div>N de téléphone :</div>
                                <div class="popup-contenu" id="popup-tel">+{{ orderData.user.indicMobile }}{{ orderData.user.mobile }}</div>
                            </div>
                            <div class="popup-row">
                                <div>Email :</div>
                                <div class="popup-contenu" id="popup-email">{{ orderData.user.email }}</div>
                            </div>
                            <div class="popup-row">
                                <div>N° de commande :</div>
                                <div class="popup-contenu" id="popup-n_commande">{{ orderData.id }}</div>
                            </div>
                            <div class="popup-row">
                                <div>Service :</div>
                                <div class="popup-contenu" id="popup-service">{{ orderData.service.name }}</div>
                            </div>
                            <div class="popup-row">
                                <div>Emplacement</div>
                                <div class="popup-contenu" id="popup-emplacement_aller">{{ orderData.company.name }}</div>
                            </div>
                        </div>
                        <div id="popup-div-two">
                            <div class="popup-row">
                                <div>N de consigne :</div>
                                <div class="popup-contenu" id="popup-consigne">{{ orderData.locker.length === 0 ? 'Bring me' : 'Classic' }}</div>
                            </div>
                            <div class="popup-commentaire-title" style="text-align:center">Commentaire :</div>
                            <div class="popup-row" style="margin-top: 0;">
                                <div id="popup-commentaire-div">
                                    <div class="popup-contenu-comment" id="popup-commentaire">{{ orderData.userComment }}</div>
                                </div>
                            </div>
                            <div id="popup-commande-div" v-on:click="hidePopup">
                                <div id="popup-commande">
                                    <button>
                                        Fermer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: "Popup",
    props: {
      orderData: Object
    },
    methods: {
        hidePopup: function () {
            this.$parent.$data.isDisplay = false;
        }
    }
}
</script>

<style scoped lang="scss">
@import "../../sass/assets/variable";

section {
    height: initial !important;
}

#popup-container {
    z-index: 9999;
    display: flex;
    position: absolute;
    height: 100%;
    width: 100%;
    flex-flow: column;
    align-items: center;
    justify-content: center;
    background-color: #80808047;
}

#popup {
    position: fixed;
    margin: 0 auto;
    z-index: 1000;
    width: 100%;
    max-width: 340px;
    max-height: 80%;
    border-radius: 20px;
    background-color: white;
    padding: 29px;
}

#popup-commande {
    width: 100%;

    & button {
        width: 100%;
        border-radius: 20px;
        height: 45px;
        border: unset;
        background-color: $mainBtnBack;
        color: white;
        cursor: pointer;
    }
}

#popup-commentaire {
    overflow-y: auto;
    padding: 10px;
    height: 90px;
}

#popup-commentaire-div {
    background-color: #F6F6F6;
    border-radius: 2px;
}

.popup-row {
    margin: 5px;
    padding-right: 5px;
    padding-left: 10px;
    border-radius: 5px;
    background-color: #F6F6F6;
    font-size: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.popup-commentaire-title {
    margin-top: 5px;
}

.popup-contenu {
    font-weight: bold;
    color: grey;
    background-color: white;
    border-radius: 5px;
    padding: 3px;
    margin: 5px;
}

.popup-contenu-comment {
    font-weight: bold;
    color: grey;
}

@media screen and (max-width: 900px) {
    #popup {
        max-width: 80%;
    }
}

@media screen and (max-width: 500px) {
    #popup {
        max-width: 100%;
    }
}

@media screen and (max-height: 500px) {
    #popup {
        max-width: 90%;
        max-height: 95%;
    }

    #popup-container {
        width: 100%;
    }

    .popup-tables-container {
        display: flex;
    }

    #popup-div-one,
    #popup-div-two {
        flex: 1;
    }

    #popup-commentaire {
        height: 70px;
    }
}
</style>
