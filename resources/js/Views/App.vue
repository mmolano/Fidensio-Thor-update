<template>
    <div>
        <div class="content-parent">
            <div class="content card z-depth-0 table-parent">
                <div class="card-content">
                    <TableRows></TableRows>
                    <!-- TODO: mettre la popup ici -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import TableRows from "../Components/TableRows.vue";

export default {
    components: {
        TableRows,
    },
    computed: {},
    data() {
        return {
            message: 'Hello World'
        }
    }
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
}
</style>
