window.$app = {
    modal: options => {
        Promise.prototype.onClose = Promise.prototype.then

        const swal = Swal.fire({
            icon: options.icon,
            title: options.title,
            html: options.text,
            confirmButtonText: options.confirmText ?? 'Ok',
        })

        return new Promise(resolve => {
            swal.then(() => {
                resolve()
            })
        })
    },
    alert: options => {
        window.dispatchEvent(new CustomEvent('show-alert', {detail: options}))
    }
}

document.addEventListener('livewire:load', () => {

    window.livewire.on('app:modal', data => {
        $app.modal(data)
    })

})
