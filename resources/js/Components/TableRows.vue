<template>
    <section>
        <SearchComponent v-model="search"></SearchComponent>
        <div class="responsive-table" v-if="filteredOrder.length !== 0">
            <table id="main-table">
                <thead>
                <tr>
                    <th class="Infos">Infos</th>
                    <th class="Debut">Début</th>
                    <th class="Retour">Retour</th>
                    <th class="Emplacement">Emplacement</th>
                    <th class="Nom">Prénom / Nom</th>
                    <th class="Consigne">N° consigne</th>
                    <th class="Service">Service(s)</th>
                    <th class="Commande" v-if="typeOfStatus !== '?type=finished'">Commande</th>
                </tr>
                </thead>
                <tbody>
                <tr class="refSearch" v-for="order in filteredOrder">
                    <td data-label="Infos" class="Infos">
                        <button @click="showPopup(order)"
                                class="btn waves-effect waves-light teal lighten-3 infos-buttons">
                            +
                        </button>
                    </td>
                    <td data-label="Début" class="Debut"><span
                        v-bind:style="{'background-color': getDateDiff(order.deliveryDate)[0], 'color': getDateDiff(order.deliveryDate)[1]}"
                        :inner-html.prop="dateFormat(order.createdAt) | highlight(search)"></span>
                    </td>
                    <td data-label="Retour" class="Retour"
                        :inner-html.prop="dateFormat(order.deliveryDate) | highlight(search)"></td>
                    <td data-label="Emplacement" class="Emplacement"
                        :inner-html.prop="order.company.name | highlight(search)"></td>
                    <td data-label="Nom/Prénom" class="Nom"
                        :inner-html.prop="order.userData.firstName + ' ' + order.userData.lastName | highlight(search)">
                    </td>
                    <td data-label="N consigne" class="Consigne"
                        :inner-html.prop="order.locker.length === 0 ? 'Bring me' : 'Classic'">
                    </td>
                    <td data-label="Service(s)" class="Service" :inner-html.prop="order.service.name"></td>
                    <td data-label="Commande" class="Commande" v-if="typeOfStatus !== '?type=finished'">
                        <span
                            class="pastille-info"
                            v-bind:style="{'background-color': getDateDiff(order.deliveryDate)[0], 'color': getDateDiff(order.deliveryDate)[1]}"></span>
                        <button v-if="typeOfStatus === ''" @click="sendProcessing(order.id, 2)"
                                class="btn waves-effect waves-light">Récupéré
                        </button>

                        <button v-if="typeOfStatus === '?type=pickupDone'" @click="viewOrder(order)"
                                class="btn waves-effect waves-light">Payer
                        </button>

                        <button v-if="typeOfStatus === '?type=processing'" @click="sendProcessing(order.id, 5)"
                                class="btn waves-effect waves-light">Compléter
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="search_result" v-if="filteredOrder.length === 0">Aucune commande trouvée
        </div>
        <div class="rows_number">
            <span v-if="search">Affichage de {{ filteredOrder.length }} résultats</span>
            <span v-if="!search">Affichage de {{ filteredOrder.length }} résultats sur {{ orders.length }}</span>
        </div>
        <div class="pagination_container" v-if="!search">
            <jw-pagination :pageSize="pageSize" :items="orders" @changePage="onChangePage"
                           :labels="customLabels"></jw-pagination>
        </div>
    </section>

</template>

<script>

import JwPagination from 'jw-vue-pagination';
import SearchComponent from "./SearchComponent";
import moment from "moment";
import axios from "axios";

const customLabels = {
    first: '<<',
    last: '>>',
    previous: '<',
    next: '>'
};

export default {
    name: "Table",
    components: {
        JwPagination,
        SearchComponent
    },
    props: {
        typeOfStatus: String
    },
    data() {
        return {
            colors: [],
            search: '',
            customLabels,
            pageSize: 9,
            pagination: [],
            orders: [],
            searchOrderUrl: 'getData'
        }
    },
    methods: {
        showPopup(value) {
            this.$parent.$data.orderData = value;
            this.$parent.$data.isDisplay = true;
        },
        viewOrder(order) {
            this.$parent.$data.orderData = order;
            this.$parent.$data.viewOrderProfile = true;
        },
        onChangePage(pageOfItems) {
            this.pagination = pageOfItems;
        },
        dateFormat: function (date) {
            return moment(date, 'YYYY-MM-DD').format('DD-MM-YYYY');
        },
        getDateDiff: function (date) {
            if (moment(date).isSame(moment().startOf('day'), 'd')) {
                return this.colors = ['#96E4A0', '#0b4a00']
            } else if (moment(date).isSame(moment().subtract(1, 'days').startOf('day'), 'd')) {
                return this.colors = ['#ffa8a8', '#d00000']
            } else {
                return this.colors = ['#7bc6f9', '#005c9a']
            }
        },
        loadOrders: function () {
            axios.get(this.searchOrderUrl + this.typeOfStatus).then(res => {
                if (res.status === 200) {
                    this.orders = res.data;
                }
            }).catch(err => {
                this.$parent.$data.message = err.response.data
            });
        },
        sendProcessing: function (orderId, status) {
            axios.post('/update', {
                'orderId': orderId,
                'status': status
            }).then(res => {
                if (res.status === 200) {
                    this.$parent.$data.message = res.data
                    this.loadOrders();
                }
            }).catch(err => {
                this.$parent.$data.message = err.response.data
            });
        },
        setLoading(isLoading) {
            if (isLoading) {
                this.$parent.$data.refCount++;
                this.$parent.$data.isLoading = true;
            } else if (this.$parent.$data.refCount > 0) {
                this.$parent.$data.refCount--;
                this.$parent.$data.isLoading = (this.$parent.$data.refCount > 0);
            }
        }
    },
    computed: {
        filteredOrder: function () {
            if (!this.search) {
                return this.pagination
            }

            const filterValue = this.search.toLowerCase();

            const filter = event =>
                event.userData.firstName.toLowerCase().includes(filterValue) ||
                event.userData.lastName.toLowerCase().includes(filterValue) ||
                (event.userData.firstName.toLowerCase() + ' ' + event.userData.lastName.toLowerCase()).includes(filterValue) ||
                event.company.name.toLowerCase().includes(filterValue) ||
                moment(event.deliveryDate).format('DD-MM-YYYY').toLowerCase().includes(filterValue) ||
                moment(event.createdAt).format('DD-MM-YYYY').toLowerCase().includes(filterValue)

            return this.orders.filter(filter)
        }
    },
    mounted() {
        this.loadOrders();
    },
    watch: {
        typeOfStatus: function (newUrl) {
            axios
                .get(this.searchOrderUrl + newUrl)
                .then(res => {
                    console.log(res);
                    if (res.status === 200) {
                        this.orders = res.data;
                    }
                })
                .catch(err => {
                    this.$parent.$data.message = err.response.data
                    if (err.response.status === 500) {
                        window.location = '/logout';
                    }
                });
        }
    },
    created() {
        axios.interceptors.request.use((config) => {
            this.setLoading(true);
            return config;
        }, (error) => {
            this.setLoading(false);
            return Promise.reject(error);
        });

        axios.interceptors.response.use((response) => {
            this.setLoading(false);
            return response;
        }, (error) => {
            this.setLoading(false);
            return Promise.reject(error);
        });
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
        display: block;
        height: 25px;
        width: 25px;
        margin: auto auto 10px;
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
