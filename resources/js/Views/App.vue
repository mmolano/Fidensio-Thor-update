<template>
    <section>
        <Popup v-if="isDisplay"></Popup>
        <div class="container-global">
<!--            <Header></Header>-->
            <SideBar></SideBar>
            <!--       TODO: tranfor le app.js en login qui redirige ici       -->
            <div class="container-fluid">
                <TableRows v-bind:orders="orders"></TableRows>
            </div>
        </div>
    </section>
</template>
<script>
import TableRows from "../Components/TableRows";
import SideBar from "../Components/SideBar";
import Popup from "../Components/Popup";
import Header from "../Components/Header";

export default {
    components: {
        Header,
        Popup,
        SideBar,
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
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap");

* {
    padding: 0;
    margin: 0;
    box-sizing: border-box;
    list-style-type: none;
    text-decoration: none;
    font-family: 'Poppins', sans-serif;
}

html, body, .container-global {
    height: 100%;
    width: 100%;
    display: flex;
    flex-flow: column;
    background-color: #FCFCFC;
}

.container-fluid {
    height: 100%;
    margin-left: 345px;
}

</style>
