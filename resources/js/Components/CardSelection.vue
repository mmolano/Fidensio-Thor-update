<template>
    <div class="card-container">
        <div class="card-head">
            <h2 :inner-html.prop="product.name | highlight(this.$parent.search)"></h2>
        </div>
        <div class="card-price">
            <h4>Prix: </h4>
            <p :inner-html.prop="product.price / 100 | highlight(this.$parent.search)"></p>
            <span>&euro;</span>
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
        product: Object
    },
    data() {
        return {
            productCount: 0,
        }
    },
    methods: {
        iteration(type, price, product) {
            if (type === 'add') {
                this.productCount += 1;
                this.$parent.finalPrice += price;
                if (this.$parent.selectedProduct.length > 0) {
                    this.$parent.selectedProduct.forEach((element, index) => {
                        console.log(element, index)
                        console.log(this.element, this.index)
                            if (product.name === element.name) {
                                //TODO voir pourquoi le element.name ne correspond pas à celui lorsqu'on clique
                                element.price = price
                                element.numberOfSelect += 1;
                            } else {
                                product.numberOfSelect = 1
                                return this.$parent.selectedProduct.push(product)
                            }
                        }
                    )
                } else {
                    product.numberOfSelect = 1
                    this.$parent.selectedProduct.push(product)
                }

            } else if (this.productCount > 0 && type === 'decrease') {
                this.productCount -= 1;
                this.$parent.finalPrice -= price;

                this.$parent.selectedProduct.forEach((element, index) => {
                        if (element.numberOfSelect > 1) {
                            element.numberOfSelect -= 1
                        } else {
                            this.$parent.selectedProduct.splice(index, 1);
                        }
                    }
                )

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
