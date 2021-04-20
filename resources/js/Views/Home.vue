<template>
    <section>
        <Popup v-if="isDisplay" :order-data="orderData"></Popup>
        <div class="container-global">
            <input type="checkbox" id="nav-toggle">
            <SideBar :active-url="typeOfStatus"></SideBar>
            <!--       TODO: tranfor le app.js en login qui redirige ici       -->
            <div class="container-fluid">
                <Header :user-name="user"></Header>
                <TableRows :type-of-status="typeOfStatus"></TableRows>
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
    name: 'Home',
    props: {
        user: String,
    },
    components: {
        Header,
        Popup,
        SideBar,
        TableRows,
    },
    data() {
        return {
            isDisplay: false,
            orderData: [],
            typeOfStatus: ''
        }
    },
};
</script>

<style lang="scss">
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
    margin-top: 90px;
    margin-left: 385px;
    transition: margin-left 300ms;
}
</style>
