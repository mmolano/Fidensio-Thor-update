<template>
    <section>
        <div class="login-container">
            <form method="POST" @submit="submit">
                <img src="/img/logo.png" alt="fidensio logo">
                <div class="input-field">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" v-model="email"/>
                </div>
                <div class="input-field">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" v-model="password">
                </div>
                <div class="center-align" id="login-button-div">
                    <button class="btn waves-effect waves-light" type="submit" name="action" id="login-button">
                        Connexion
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>

<script>
export default {
    name: "Login",
    data() {
        return {
            email: '',
            password: ''
        }
    },
    methods: {
        submit: async function () {
            console.log(this.email)
            await axios.post('/login', {
                'email': this.email,
                'password': this.password
            }).then(res => {
                if (res.status === 200) {
                    alert('success')
                }
            }).catch(err => {
                console.log(err)
            });
        }
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
    border-radius: 20px;
    background-color: #4d5261;

    & form {
        text-align: center;

        & img {
            width: 100%;
            max-width: 370px;
        }

        & .input-field {
            display: flex;
            flex-direction: column;
        }
    }
}
</style>
