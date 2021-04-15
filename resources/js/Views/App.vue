<template>
    <section>
        <Popup v-if="isDisplay"></Popup>
        <div class="container-global">
            <Header></Header>
            <div class="container-fluid">
                <NavBarPanel></NavBarPanel>
                <!--            TODO: mettre la search bar en version mobile + tranfor le app.js en login qui redirige ici       -->
                <div class="content-parent">
                    <div class="content card z-depth-0 table-parent">
                        <div class="card-content">
                            <TableRows v-bind:orders="orders"></TableRows>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import TableRows from "../Components/TableRows.vue";
import NavBarPanel from "../Components/NavBarPanel";
import Popup from "../Components/Popup";
import Header from "../Components/Header";

export default {
    components: {
        Header,
        Popup,
        NavBarPanel,
        TableRows,
    },
    props: {
        orders: Array
    },
    data() {
        return {
            isDisplay: false,
        }
    },
};
</script>

<style scoped lang="scss">
.content-parent {
    flex: 3;
    display: flex;
    flex-flow: column;

    & .table-parent {
        background-color: #FCFCFC;
    }
}

html, body, .container-global {
    height: 100%;
    width: 100%;
    display: flex;
    flex-flow: column;
    background-color: #FCFCFC;
}

.container-fluid {
    margin-left: 1%;
    margin-right: 1%;
    flex: 1 1 auto;
    display: flex;
    overflow: auto;
}

@media screen and (min-width: 1440px) {
    .content-parent {
        flex: 7;
        display: flex;
        flex-flow: column;
    }
}

@media screen and (min-width: 900px) and (max-width: 1024px) {
    .content-parent {
        flex: 5;
        display: flex;
        flex-flow: column;
    }
}

@media screen and (max-width: 900px) {
    .content-parent {
        flex: 1;
    }

    .container-fluid {
        display: flex;
        flex-direction: column;
    }
}
</style>
