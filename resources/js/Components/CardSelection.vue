<template>
    <div class="card-container">
        <div class="card-head">
            <h2 :inner-html.prop="product.name | highlight(this.$parent.search)"></h2>
        </div>
        <div class="card-price">
            <h4>Prix: </h4>
            <p :inner-html.prop="product.price / 100 + ' €'| highlight(this.$parent.search)"></p>
        </div>
        <div class="card-quantity">
            <h4>Quantité: </h4>
            <div class="card-quantity-selection">
                <button @click="iteration('decrease', product.price, product)" :class="{'disabled': productCount === 0}"
                        :disabled="productCount === 0">-
                </button>
                <span>{{ productCount }}</span>
                <button @click="iteration('add', product.price, product)">+</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "CardSelection",
    props: {
        product: Object,
        selectedProduct: Array
    },
    data() {
        return {
            productCount: 0,
        }
    },
    methods: {
        checkProduct(product) {
            return this.selectedProduct.filter(
                function (element) {
                    return element.name === product.name
                }
            ).length > 0;
        },
        iteration(type, price, product) {
            if (type === 'add') {
                this.productCount += 1;
                this.$parent.totalSelection += 1;
                this.$parent.finalPrice += price;
                if (this.selectedProduct.length > 0) {
                    if (!this.checkProduct(product, price)) {
                        product.quantity = 1;
                        product.finalPrice = price;
                        this.$parent.selectedProduct.push(product);
                    } else {
                        product.quantity += 1;
                        product.finalPrice += price;
                    }
                } else {
                    product.quantity = 1;
                    product.finalPrice = price;
                    return this.$parent.selectedProduct.push(product);
                }

            } else if (this.productCount > 0 && type === 'decrease') {
                this.productCount -= 1;
                this.$parent.finalPrice -= price;
                this.$parent.totalSelection -= 1;

                if (this.checkProduct(product, price)) {
                    if (product.quantity > 1) {
                        product.quantity -= 1;
                        product.finalPrice -= price;
                    } else {
                        const getElement = (element) => element.name === product.name;
                        const index = this.selectedProduct.findIndex(getElement)
                        this.$parent.selectedProduct.splice(index, 1)
                    }
                }
            }
        },
    }
}
</script>

<style scoped lang="scss">
@import "resources/sass/assets/variable";

h4 {
    font-weight: bold;
}

.card-container {
    max-width: 350px;
    width: 100%;
    padding: 34px;
    background-color: white;
    border-radius: 20px;
    margin: 30px 30px 30px 0;
    box-shadow: 0 14px 38px 6px rgb(123 123 123 / 13%);

    & .card-desc {
        max-height: 135px;
        height: 100%;
        overflow-y: scroll;
    }

    & .card-quantity-selection {
        & span {
            padding: 7px;
        }

        & button {
            border: none;
            cursor: pointer;
            background-color: $mainBtnBack;
            color: white;
            padding: 6px;
            border-radius: 6px;

            &.disabled {
                cursor: not-allowed;
                color: black;
                background-color: #caccd0;
            }
        }
    }
}

.card-head, .card-desc, .card-price, .card-quantity, h4 {
    padding-bottom: 10px;
}
</style>
