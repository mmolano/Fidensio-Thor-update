<template>
    <section>
        <div class="main-container">
            <div class="content-head">
                <SearchComponent v-model="search"></SearchComponent>
                <div class="user-informations">
                    <div class="row">
                        <h4>Client: </h4>
                        <p>{{ orderData.userData.firstName }} {{ orderData.userData.lastName }}</p>
                    </div>
                    <div class="row">
                        <h4>Numéro de commande: </h4>
                        <p>{{ orderData.userData.firstName }} {{ orderData.userData.lastName }}</p>
                    </div>
                    <div class="row">
                        <h4>Emplacement: </h4>
                        <p>{{ orderData.company.name }}</p>
                    </div>
                    <div class="row">
                        <h4>Date de retour: </h4>
                        <p>{{ dateFormat(orderData.deliveryDate) }}</p>
                    </div>
                    <div class="row">
                        <h4>Commentaire: </h4>
                        <p>{{ orderData.userComment }}</p>
                    </div>
                </div>
            </div>
            <div class="main-content">
                <CardSelection v-for="(item, index) in filteredProduct" :key="index"
                               :product="item"></CardSelection>
            </div>
        </div>
        <div class="secondary-container">
            <form method="POST" @submit.prevent="submit">
                <input type="hidden" name="_token" :value="csrf">
                <div class="bottom-informations">
                    <div v-for="(item, index) in selectedProduct">
                        {{ item.name }}
                    </div>
                    <div class="input-container">
                        <input id='one' type='checkbox' v-model="gift"/>
                        <label for='one'>
                            <span></span>
                            Offrir au client
                            <ins><i>Offrir au client</i></ins>
                        </label>
                    </div>
                    <div class="total-price">
                        <h4>Total: </h4>
                        <p :inner-html.prop="gift === false ? (finalPrice / 100) + ' €' : 0 + ' €'"></p>
                    </div>
                    <button type="submit" name="action" id="login-button">
                        Confirmer la commande
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>

<script>
import CardSelection from "./CardSelection";
import SearchComponent from "./SearchComponent";
import axios from "axios";
import moment from "moment";

export default {
    name: "OrderProfile",
    props: {
        orderData: Object,
    },
    components: {
        CardSelection,
        SearchComponent
    },
    data() {
        return {
            search: '',
            productData: [],
            finalPrice: 0,
            selectedProduct: [],
            gift: false
        }
    },
    methods: {
        loadProducts: function () {
            axios.get('/getProduct?id=' + this.orderData.userId).then(res => {
                if (res.status === 200) {
                    this.productData = res.data.products;
                }
            }).catch(err => {
                // TODO: catch l'erreur dans une popup
                console.log(err.data)
            });
        },
        dateFormat: function (date) {
            return moment(date, 'YYYY-MM-DD').format('DD-MM-YYYY');
        }
    },
    computed: {
        filteredProduct: function () {
            if (!this.search) {
                return this.productData
            }

            const filterValue = this.search.toLowerCase();

            const filter = event =>
                event.name.toLowerCase().includes(filterValue) ||
                (event.price / 100).toString().toLowerCase().includes(filterValue) ||
                event.description.toLowerCase().includes(filterValue)

            return this.productData.filter(filter)
        },
        csrf() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        },
    },
    mounted() {
        this.loadProducts()
    }
}
</script>

<style scoped lang="scss">
@import "../../sass/assets/variable";

html,
body {
    height: 100%;
    width: 100%;
    display: flex;
    flex-flow: column;
}

section {
    display: flex;
    padding: 2rem 1.5rem;
    background: #F0F0F0
}

tbody tr td {
    padding: 20px;
    border-bottom: none;
    text-align: center;

    & span {
        padding: 9px;
        border-radius: 6px;
        font-weight: bold;
    }
}

thead {
    box-shadow: 0 2px 31px 0 rgb(123 123 123 / 20%);

    & tr {
        color: #232222;
        height: 50px;

        & th {
            top: 0;
            z-index: 2;
            position: sticky;
            background-color: white;
            padding: 20px;
            box-shadow: 20px 9px 31px 0 rgb(123 123 123 / 20%);
        }
    }
}

input[type='checkbox'] {
    height: 0;
    width: 0;
}

ins {
    margin-left: 10px;
}

input[type='checkbox'] + label {
    position: relative;
    display: flex;
    margin: .6em 0;
    align-items: center;
    color: #9e9e9e;
    transition: color 250ms cubic-bezier(.4, .0, .23, 1);
}

input[type='checkbox'] + label > ins {
    position: absolute;
    display: block;
    bottom: 0;
    left: 2em;
    height: 0;
    width: 100%;
    overflow: hidden;
    text-decoration: none;
    transition: height 300ms cubic-bezier(.4, .0, .23, 1);
}

input[type='checkbox'] + label > ins > i {
    position: absolute;
    bottom: 0;
    font-style: normal;
    color: $mainBtnBack;
}

input[type='checkbox'] + label > span {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 1em;
    width: 1em;
    height: 1em;
    background: transparent;
    border: 2px solid #9E9E9E;
    border-radius: 2px;
    cursor: pointer;
    transition: all 250ms cubic-bezier(.4, .0, .23, 1);
}

input[type='checkbox'] + label:hover, input[type='checkbox']:focus + label {
    color: black;
}

input[type='checkbox'] + label:hover > span, input[type='checkbox']:focus + label > span {
    background: rgba(255, 255, 255, .1);
}

input[type='checkbox']:checked + label > ins {
    height: 100%;
}

input[type='checkbox']:checked + label > span {
    border: .5em solid $mainBtnBack;
    animation: shrink-bounce 200ms cubic-bezier(.4, .0, .23, 1);
}

input[type='checkbox']:checked + label > span:before {
    content: "";
    position: absolute;
    top: .6em;
    left: .8em;
    border-right: 3px solid transparent;
    border-bottom: 3px solid transparent;
    transform: rotate(45deg);
    transform-origin: 0% 100%;
    animation: checkbox-check 125ms 250ms cubic-bezier(.4, .0, .23, 1) forwards;
}

@keyframes shrink-bounce {
    0% {
        transform: scale(1);
    }
    33% {
        transform: scale(.85);
    }
    100% {
        transform: scale(1);
    }
}

@keyframes checkbox-check {
    0% {
        width: 0;
        height: 0;
        border-color: #212121;
        transform: translate3d(0, 0, 0) rotate(45deg);
    }
    33% {
        width: .2em;
        height: 0;
        transform: translate3d(0, 0, 0) rotate(45deg);
    }
    100% {
        width: .2em;
        height: .5em;
        border-color: #212121;
        transform: translate3d(0, -.5em, 0) rotate(45deg);
    }
}

.responsive-table {
    display: block;
    background-color: white;
    border-radius: 20px;
    max-height: 858px;
    height: 100%;
    width: 90%;
    margin: auto;
    overflow: scroll;
}

.pastille-info {
    display: none;
}

.main-content {
    display: flex;
    flex-wrap: wrap;
    width: 90%;
    margin: auto;
}

.main-container {
    width: 83%;
}

.user-informations {
    width: 90%;
    margin: auto;
    display: flex;
    justify-content: space-evenly;
    padding: 20px;
    background: white;
    border-radius: 20px;
}

.secondary-container {
    width: 17%;
    background: white;
    border-radius: 20px;
    position: fixed;
    padding: 40px;
    height: unset;
    right: 22px;
    bottom: 15px;
    box-shadow: 0 14px 38px 6px rgb(123 123 123 / 13%);

    & .bottom-informations {
        display: flex;
        flex-direction: column;
    }

    & .input-container, .total-price {
        display: flex;
        justify-content: flex-end;
    }

    & label, .total-price p {
        padding-left: 10px;
    }

    & button {
        border: none;
        cursor: pointer;
        background-color: $mainBtnBack;
        color: white;
        padding: 6px;
        border-radius: 6px;
        height: 43px
    }

    & input, label {
        cursor: pointer;
    }
}

#main-table {
    border-collapse: collapse;
    border-radius: 20px;
    overflow-y: scroll;
    width: 100%;
    height: 100%;
    margin: auto;
    box-shadow: 0 14px 38px 6px rgb(123 123 123 / 13%);

    & tbody tr {
        &:nth-child(even) {
            background-color: #f5f5f5;
        }

        &:hover {
            background-color: #ffe4e4;
        }

        width: 100%;
    }

    & .btn {
        cursor: pointer;
        width: 100%;
        background-color: $btnBackground;
        color: #fff;
        border: unset;
        padding: 6px;
        border-radius: 10px;
    }
}

.search-wrapper {
    width: 90%;
    margin: 20px auto;

    & .search-input-contain {
        display: flex;
        background: white;
        width: 288px;
        height: 55px;
        border-radius: 20px;
        padding: 20px;
        border: none;
        align-items: center;
        overflow: hidden;
        box-shadow: 0 14px 38px 6px rgb(123 123 123 / 13%);

        & span {
            display: inline-block;
            padding: 0 0.2rem;
            font-size: 1.2rem;
        }

        & input {
            width: 100%;
            height: 100%;
            padding: .5rem;
            border: none;
            outline: none;
        }
    }
}

.search_result {
    text-align: center;
    font-size: 18px;
    color: rgb(255, 91, 91);
    background-color: white;
    padding: 20px;
    border-radius: 20px;
    width: 90%;
    margin: auto auto 20px;
}

.rows_number {
    margin: 10px;
    text-align: center;
    opacity: 0.6;
}

.refSearch {
    height: 70px;
    min-height: 100%;
}

.btn {
    margin-top: 0;
    margin-bottom: 0;
}

.infos-buttons {
    font-size: 24px;
}

@media only screen and (min-width: 900px) {
    .Debut, .Retour {
        min-width: 140px;
    }
}

@media screen and (max-width: 900px) {
    .Debut,
    .Retour,
    .Service {
        display: none;
    }
}

@media screen and (max-width: 500px) {
    body {
        font-size: 12px;
    }

    table {
        height: unset !important;
        margin-left: 10px;
    }

    .refSearch {
        height: unset;
    }

    #main-table tbody tr {
        display: flex;
        flex-direction: column;
    }
    .Infos {
        width: 30%;
        margin: auto;
    }

    .Commande {
        & a {
            position: relative;
        }
    }

    .pastille-info {
        position: absolute;
        display: block;
        height: 25px;
        width: 25px;
        border-radius: 50px;
    }

    tbody tr td a {
        width: 20px;
    }

    .Emplacement::before {
        content: 'Emplacement: ';
        color: grey;
        font-weight: bold;
    }

    .Nom::before {
        content: 'Nom: ';
        color: grey;
        font-weight: bold;
    }

    .Consigne::before {
        content: 'Consigne: ';
        color: grey;
        font-weight: bold;
    }

    #main-table thead {
        display: none;
    }
}

@media only screen and (max-width: 380px) {
    section {
        padding: 5px !important;
    }
}
</style>
