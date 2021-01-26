const $app = {
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
    }
}

document.addEventListener('livewire:load', () => {

    this.livewire.on('app:modal', data => {
        console.log('xablau');
        $app.modal(data)
    })

})
