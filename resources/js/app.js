import Alpine from 'alpinejs'

window.Alpine = Alpine

// Toast helper
window.toast = function () {
    return {
        show: false,
        message: '',
        type: 'success',

        fire(msg, type = 'success') {
            this.message = msg
            this.type = type
            this.show = true

            setTimeout(() => {
                this.show = false
            }, 3000)
        }
    }
}

Alpine.start()
