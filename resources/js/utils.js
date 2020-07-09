class Utils {
    static copyText(text) {
        const textToCopy = typeof text === 'object' ? text.getAttribute('x-copy') : text;

        const myTemporaryInputElement = document.createElement('input');

        myTemporaryInputElement.type = 'text';
        myTemporaryInputElement.value = textToCopy;

        document.body.appendChild(myTemporaryInputElement);

        myTemporaryInputElement.select();
        document.execCommand('Copy');

        document.body.removeChild(myTemporaryInputElement);
    }
}

module.exports = Utils;
