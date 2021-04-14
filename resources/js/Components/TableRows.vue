<template>
    <table id="main-table">
        <thead>
        <tr>
            <th class="Infos">Infos</th>
            <th class="Debut">Début</th>
            <th class="Retour">Retour</th>
            <th class="Emplacement">Emplacement</th>
            <th class="Nom">Nom / Prénom</th>
            <th class="Consigne">N consigne</th>
            <th class="Service">Service(s)</th>
            <th class="Commande">Commande</th>
        </tr>
        </thead>
        <tbody>
        <tr id="refSearch">
            <td data-label="Infos" class="Infos">
                <button v-on:click="showPopup" class="btn waves-effect waves-light teal lighten-3 infos-buttons">
                    +
                </button>
            </td>
            <td data-label="Début" class="Debut">2020/12/08</td>
            <td data-label="Retour" class="Retour">2021/12/09</td>
            <td data-label="Emplacement" class="Emplacement">Total la défense</td>
            <td data-label="Nom/Prénom" class="Nom">Miguel Molano</td>
            <td data-label="N consigne" class="Consigne">Bring me or not</td>
            <td data-label="Service(s)" class="Service">Pressing</td>
            <td data-label="Commande" class="Commande">
                <a href="/taken/new" class="btn waves-effect waves-light">Recupéré</a>
            </td>
            <td class="orderStatus" style="display: none">En Attente</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    name: "Table",
    methods: {
        showPopup: function () {
            this.$parent.$data.isDisplay = true;
        }
    }
}
</script>

<style scoped lang="scss">
html,
body {
    height: 100%;
    width: 100%;
    display: flex;
    flex-flow: column;
    background-color: #FCFCFC;
}

tbody tr td {
    padding-bottom: 8px;
    padding-top: 8px;
    border-bottom: none;
}

thead tr {
    color: grey;
}

#main-table {
    & tbody tr:hover {
        background-color: #F6F6F6;
    }

    & .btn {
        width: 100%;
        border-radius: 4px;
    }
}

.btn {
    margin-top: 0;
    margin-bottom: 0;
}

.infos-buttons {
    font-size: 24px;
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

    #main-table tbody tr {
        display: flex;
        flex-direction: column;
    }

    tbody tr td {
        display: flex;
        justify-content: space-between;
        padding-left: 15px;
        padding-right: 15px;
    }

    .Infos {
        width: 30%;
        margin: auto;
    }

    tbody tr td a {
        width: 20px;
    }

    .Emplacement::before {
        content: 'Emplacement';
        color: grey;
        font-weight: bold;
    }

    .Nom::before {
        content: 'Nom';
        color: grey;
        font-weight: bold;
    }

    .Consigne::before {
        content: 'Consigne';
        color: grey;
        font-weight: bold;
    }

    #main-table thead {
        display: none;
    }

    #main-table tbody tr {
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.24), 0 1px 2px rgba(0, 0, 0, 0.24);
        transition: all 0.3s cubic-bezier(.25, .8, .25, 1);
        margin-bottom: 15px;
    }

    #main-table tbody tr:hover {
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.22);
    }
}

@media screen and (max-height: 500px) {
    body {
        font-size: 12px;
    }

    table {
        margin-left: 10px;
    }
}
</style>
