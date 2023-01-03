import './../style.scss'
$(function () {
    $('#dolly').on('click', function (e) {
        e.preventDefault()
        alert('Alert from dolly')
    })
});