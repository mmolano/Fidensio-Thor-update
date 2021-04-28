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
    watch: {
        myMessage: function (value) {
            let messages = document.getElementsByClassName('alert');
            let messageDiv = document.getElementById('alert-row');
            let customMessage = document.getElementById('messageBox');
            let secondes = 200;
            for (let i = 0; i < messages.length; i++) {
                let message = messages[i];

                let popupDelete = document.getElementById('alert-data-dissmiss');

                popupDelete.addEventListener('click', e => {
                    message.style.left = "-100%";
                }, {passive: true});

                customMessage.innerHTML = value.message;

                if (value.status === 'error') {
                    messageDiv.classList.remove('alert-info');
                    messageDiv.classList.add("alert-danger");
                } else {
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
