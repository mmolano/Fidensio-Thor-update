<template>
    <section>
        <div class="login-container">
            <form method="POST" @submit="submit">
                <input type="hidden" name="_token" :value="csrf">
                <img src="/img/logo.png" alt="fidensio logo">
                <div class="input-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" v-model="email" :class="{'input-colored': email}"/>
                </div>
                <div class="input-field">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" v-model="password"
                           :class="{'input-colored': password}" autocomplete="password">
                </div>
                <div class="center-align" id="login-button-div">
                    <button class="btn waves-effect waves-light" type="submit" name="action" id="login-button"
                            :class="{'disabled': !email}" :disabled="!email">
                        Connexion
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>

<script>
import axios from 'axios';

export default {
    name: "Login",
    data() {
        return {
            email: '',
            password: ''
        }
    },
    methods: {
        submit: function () {
            axios.post('/login', {
                'email': this.email,
                'password': this.password
            }).then(response => {
                console.log(response.data)
            }).catch(err => {
                console.log(err)
            });
        },
        flashPopupMessage: function () {
            let messages = document.getElementsByClassName('alert');
            let secondes = 200;
            for (let i = 0; i < messages.length; i++) {
                let message = messages[i];

                let popupDelete = document.getElementById('alert-data-dissmiss');

                popupDelete.addEventListener('click', e => {
                    message.style.left = "-100%";
                });

                setTimeout(() => {
                    message.style.left = "42px";
                }, secondes);

                secondes += 2800;
                if (!message.classList.contains('alert-important')) {
                    setTimeout(() => {
                        message.style.left = "-100%";
                    }, secondes);
                }
                secondes -= 1000;
            }
        }
    },
    computed: {
        csrf() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        },
    },
    created() {
        this.flashPopupMessage();
    }
}
</script>

<style scoped lang="scss">
@import "resources/sass/assets/variable";

section {
    position: relative;
    background-color: $main !important;
}

.login-container {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    margin: auto;
    max-width: 500px;
    height: fit-content;
    width: 100%;
    padding: 50px;

    & form {
        text-align: center;

        & img {
            width: 100%;
            max-width: 370px;
        }

        & .input-field {
            display: flex;
            flex-direction: column;
            margin: 22px 0;
            color: white;

            & label {
                text-align: left;
                margin-bottom: 10px;
            }

            & input {
                background-color: unset;
                border: unset;
                border-bottom: 1px solid white;
                color: white;
                padding: 0 0 3px;

                &:focus {
                    outline: none;
                    border-bottom: 1px solid $mainBtnBack;
                }

                &.input-colored {
                    border-bottom: 1px solid $mainBtnBack;
                }
            }
        }

        & button {
            padding: 14px;
            border-radius: 20px;
            border: none;
            cursor: pointer;
            background-color: $mainBtnBack;
            color: white;

            &.disabled {
                cursor: not-allowed;
                color: black;
                background-color: #caccd0;
            }
        }
    }
}
</style>
