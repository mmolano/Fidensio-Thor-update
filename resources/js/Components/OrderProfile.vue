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
                               :product="item" :selected-product="selectedProduct"></CardSelection>
            </div>
        </div>
        <div class="secondary-container-opener" @click="isMobileDisplay = !isMobileDisplay" v-bind:style="{'display': isMobileDisplay ? 'none': ''}">
            <div class="chart-counter">{{ totalSelection }}</div>
            <span class="las la-shopping-cart"></span>
        </div>
        <div class="secondary-container" v-bind:style="{'bottom': isMobileDisplay ? '0px': ''}">
            <div class="close-toggle" @click="isMobileDisplay = !isMobileDisplay">
                <span class="las la-times"></span>
            </div>
            <form method="POST" @submit.prevent="sendProducts">
                <input type="hidden" name="_token" :value="csrf">
                <div class="bottom-informations">
                    <div class="selected-times-wrapper">
                        <div class="selected-times-row" v-for="(item, index) in selectedProduct">
                            {{ item.name }} x{{ item.quantity ? item.quantity : '1' }}
                        </div>
                    </div>
                    <div class="input-container">
                        <label for="comment"></label><input id="comment" type="text" v-model="comment"
                                                            placeholder="Commentaire Pressing" :class="{'input-colored': comment}"/>
                    </div>
                    <div class="input-container">
                        <label for="numberPress"></label><input id="numberPress" type="text" v-model="numberPress"
                                                                placeholder="Numéro de pressing" :class="{'input-colored': numberPress}"/>
                    </div>
                    <div class="input-container">
                        <input id="one" type="checkbox" v-model="gift"/>
                        <label for="one">
                            <span></span>
                            Offrir au client
                            <ins><i>Offrir au client</i></ins>
                        </label>
                    </div>
                    <div class="total-price">
                        <h4>Total: </h4>
                        <p :inner-html.prop="gift === false ? (finalPrice / 100) +  ' €' : 0 + ' €'"></p>
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
            gift: false,
            comment: '',
            numberPress: '',
            totalSelection: 0,
            isMobileDisplay: false
        }
    },
    methods: {
        loadProducts: function () {
            axios.get('/getProduct?id=' + this.orderData.userId).then(res => {
                if (res.status === 200) {
                    this.productData = res.data.products;
                }
            }).catch(err => {
                this.$parent.$data.viewOrderProfile = false;
                this.$parent.$data.message = err.response.data
            });
        },
        sendProducts: function () {
            if (this.gift) {
                this.selectedProduct.forEach((element, index) => {
                    element.name = 'Cadeau: ' + element.name
                    element.finalPrice = 0;
                    element.price = 0;
                });
            }
            axios.post('/pay/order', {
                'id': this.orderData.id,
                'details': this.selectedProduct,
                'finalPrice': this.finalPrice,
                'comment': this.comment,
                'numberPress': this.numberPress
            }).then(res => {
                if (res.status === 200) {
                    this.$parent.$data.message = res.data
                    this.$parent.$data.viewOrderProfile = false;
                }
            }).catch(err => {
                this.$parent.$data.message = err.response.data
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

input[type='checkbox'] {
    height: 0;
    width: 0;
}

input[type='text'] {
    cursor: initial !important;
    min-width: 180px;
    width: 100%;
    padding: 10px 6px 5px;
    margin: 10px auto;
    border: unset;
    border-bottom: 1px solid black;

    &:focus {
        outline: none;
        border-bottom: 1px solid $mainBtnBack;
    }

    &.input-colored {
        border-bottom: 1px solid $mainBtnBack;
    }
}

.close-toggle {
    display: none;
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

.selected-times-row {
    padding: 0 0 8px 18px;
}

.chart-counter {
    font-size: 20px;
    position: absolute;
    top: -6px;
    right: -3px;
    background-color: #ee7ca6;
    border-radius: 50px;
    color: white;
    height: 30px;
    width: 30px;
}

.secondary-container-opener {
    display: none;
}

.main-container {
    width: 83%;
}

.user-informations {
    width: 90%;
    margin: auto;
    display: flex;
    padding: 20px;
    background: white;
    border-radius: 20px;
    flex-wrap: wrap;

    & .row {
        margin: 20px;
    }
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

@media only screen and (max-width: 1434px) {
    .secondary-container {
        width: 28%;
    }
}

@media only screen and (max-width: 900px) {
    .secondary-container {
        width: 38%;
    }
}

@media only screen and (max-width: 790px) {
    .secondary-container {
        bottom: -100%;
        width: 100%;
        right: 0;
        transition: bottom 300ms;
        border-radius: 0;
        padding: 70px;
        z-index: 999;
        max-height: 100%;
    }
    .secondary-container-opener {
        display: block;
        position: fixed;
        bottom: 22px;
        right: 22px;
        height: 70px;
        text-align: center;
        width: 70px;
        padding: 10px;
        font-size: 35px;
        border-radius: 177px;
        background-color: white;
        box-shadow: 0 14px 38px 6px rgb(123 123 123 / 13%);
    }

    .selected-times-wrapper {
        max-height: 280px;
        overflow: scroll;
    }

    .close-toggle {
        display: flex;
        width: 50px;
        height: 50px;
        border-radius: 50px;
        background-color: #ee7ca6;
        font-size: 30px;
        padding: 9px;
        color: white;
        top: 6px;
        right: 11px;
        position: absolute;
    }
}

@media screen and (max-width: 500px) {
    body {
        font-size: 12px;
    }

    .refSearch {
        height: unset;
    }

    .main-container {
        width: 100%;
    }

    .pastille-info {
        position: absolute;
        display: block;
        height: 25px;
        width: 25px;
        border-radius: 50px;
    }

    .secondary-container {
        padding: 39px;
    }

    .card-container {
        max-width: 100%;
        margin-right: 0;
    }
}

@media only screen and (max-width: 380px) {
    section {
        padding: 5px !important;
    }
}
</style>
