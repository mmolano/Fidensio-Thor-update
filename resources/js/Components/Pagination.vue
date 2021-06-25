<template>
    <ul v-if="pager.pages && pager.pages.length" class="pagination" :style="ulStyles">
        <li class="page-item first" :class="{'disabled': pager.currentPage === 1}" :style="liStyles">
            <a class="page-link" @click="setPage(1, firstPageOfItems) && getOrders(firstPageOfItems)" :style="aStyles">{{
                    labels.first
                }}</a>
        </li>
        <li class="page-item previous" :class="{'disabled': pager.currentPage === 1}" :style="liStyles">
            <a class="page-link"
               @click="setPage(pager.currentPage - 1, previousPageOfItems) && getOrders(previousPageOfItems)"
               :style="aStyles">{{ labels.previous }}</a>
        </li>
        <li v-for="page in pager.pages" :key="page" class="page-item page-number"
            :class="{'active': pager.currentPage === page}" :style="liStyles">
            <a class="page-link" @click="setPage(page) && getOrders(page)" :style="aStyles">{{ page }}</a>
        </li>
        <li class="page-item next" :class="{'disabled': pager.currentPage === pager.totalPages}" :style="liStyles">
            <a class="page-link" @click="setPage(pager.currentPage + 1, nextPageOfItems) && getOrders(nextPageOfItems)"
               :style="aStyles">{{ labels.next }}</a>
        </li>
        <li class="page-item last" :class="{'disabled': pager.currentPage === pager.totalPages}" :style="liStyles">
            <a class="page-link" @click="setPage(pager.totalPages, lastPageOfItems) && getOrders(lastPageOfItems)"
               :style="aStyles">{{ labels.last }}</a>
        </li>
    </ul>
</template>

<script>
import paginate from 'jw-paginate';
import axios from "axios";

const defaultLabels = {
    first: '<<',
    last: '>>',
    previous: '<',
    next: '>'
};

const defaultStyles = {
    ul: {
        margin: 0,
        padding: 0,
        display: 'inline-block'
    },
    li: {
        listStyle: 'none',
        display: 'inline',
        textAlign: 'center'
    },
    a: {
        cursor: 'pointer',
        padding: '6px 12px',
        display: 'block',
        float: 'left'
    }
};

export default {
    name: "Pagination",
    props: {
        items: {
            type: Array,
            required: true,
            default: []
        },
        typeOfStatus: {
            type: String,
            required: true
        },
        numberOfItems: {
            type: Number,
            required: true
        },
        nextPageOfItems: {
            type: Number,
            required: true
        },
        previousPageOfItems: {
            type: Number,
            required: true
        },
        firstPageOfItems: {
            type: Number,
            required: true,
            default: 1
        },
        lastPageOfItems: {
            type: Number,
            required: true
        },
        initialPage: {
            type: Number,
            default: 1
        },
        pageSize: {
            type: Number,
            default: 10
        },
        maxPages: {
            type: Number,
            default: 10
        },
        labels: {
            type: Object,
            default: () => defaultLabels
        },
        styles: {
            type: Object
        },
        disableDefaultStyles: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            pager: {},
            ulStyles: {},
            liStyles: {},
            aStyles: {},
        }
    },
    created() {
        if (!this.$listeners.changePage) {
            throw 'Missing required event listener: "changePage"';
        }
        // set default styles unless disabled
        if (!this.disableDefaultStyles) {
            this.ulStyles = defaultStyles.ul;
            this.liStyles = defaultStyles.li;
            this.aStyles = defaultStyles.a;
        }
        // merge custom styles with default styles
        if (this.styles) {
            this.ulStyles = {...this.ulStyles, ...this.styles.ul};
            this.liStyles = {...this.liStyles, ...this.styles.li};
            this.aStyles = {...this.aStyles, ...this.styles.a};
        }
        // set to initial page

        const {items, pageSize, maxPages, numberOfItems} = this;
        // get new pager object for specified page
        const pager = paginate(numberOfItems, this.firstPageOfItems, pageSize, maxPages);
        // get new page of items from items array

        const pageOfItems = items.slice(pager.startIndex, pager.endIndex + 1);

        // update pager
        this.pager = pager;
        // emit change page event to parent component
        this.$emit('changePage', pageOfItems, this.firstPageOfItems);
    },
    methods: {
        setPage(page) {
            this.getOrders(page);
        },
        getOrders(page) {
            axios.get('getData' + this.typeOfStatus + '&page=' + page).then(res => {
                res.data.data.forEach(order => {
                    delete order.user.password
                })
                this.$parent.orders = res.data;
                const {pageSize, maxPages, numberOfItems} = this;
                // get new pager object for specified page
                const pager = paginate(numberOfItems, page, pageSize, maxPages);
                // get new page of items from items array

                const pageOfItems = res.data.data;
                // update pager
                this.pager = pager;
                // emit change page event to parent component
                this.$emit('changePage', pageOfItems, page);
            }).catch(err => {
                this.message = err.response.data
            });
        }
    },
    watch: {
        typeOfStatus: function () {
            this.setPage(1);
        }
    }
}
</script>
