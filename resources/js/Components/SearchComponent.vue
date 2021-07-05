<template>
    <div class="search-wrapper">
        <div class="search-input-contain">
            <label for="search"><span class="las la-search"></span></label>
            <input id="search" v-model="model" type="search" placeholder="Faire une recherche"/>
        </div>
    </div>
</template>

<script>
import Vue from "vue";

function escapeRegExp(str) {
    return str.replace(/\W|_/g, '[$&]');
}

Vue.filter('highlight', function (words, query) {
    var iQuery = new RegExp(escapeRegExp(query), "ig");
    return words.toString().replace(iQuery, function (matchedTxt, a, b) {
        return '<span class=\'highlight\'>' + matchedTxt + '</span>';
    });
});

export default {
    name: "SearchComponent",
    props: [
        'value'
    ],
    computed: {
        model: {
            get() {
                return this.value
            },
            set(val) {
                this.$emit('input', val)
            }
        }
    },
}
</script>

<style scoped lang="scss">
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
</style>
