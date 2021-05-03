<template>
    <div class="alert" id="alert-row" role="alert">
        <div id="messageBox"></div>
        <button type="button"
                class="close"
                id="alert-data-dissmiss"
                data-dismiss="alert"
                aria-hidden="true"
        >&times;
        </button>
    </div>
</template>

<script>
export default {
    name: "FlashPopup.vue",
    props: {
        myMessage: {
            required: true
        }
    },
    data() {
        return {
            defaultMessage: 'Une erreur est survenue, veuillez nous contacter'
        }
    },
    watch: {
        myMessage: function (value) {
            let messages = document.getElementsByClassName('alert');
            let messageDiv = document.getElementById('alert-row');
            let customMessage = document.getElementById('messageBox');
            let secondes = 300;
            for (let i = 0; i < messages.length; i++) {
                let message = messages[i];

                let popupDelete = document.getElementById('alert-data-dissmiss');

                popupDelete.addEventListener('click', e => {
                    message.style.left = "-100%";
                }, {passive: true});

                if (value.exception) {
                    customMessage.innerHTML = this.defaultMessage;
                } else {
                    customMessage.innerHTML = value.message;
                }

                if (value.status === 'error' || value.exception) {
                    messageDiv.classList.remove('alert-warning');
                    messageDiv.classList.remove('alert-info');
                    messageDiv.classList.add("alert-danger");
                } else if (value.status === 'warning') {
                    messageDiv.classList.remove('alert-danger');
                    messageDiv.classList.remove('alert-info');
                    messageDiv.classList.add("alert-warning");
                } else {
                    messageDiv.classList.remove('alert-warning');
                    messageDiv.classList.remove('alert-danger');
                    messageDiv.classList.add("alert-info");
                }

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
    }
}
</script>

<style scoped>

</style>
