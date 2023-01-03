import './../style.scss'
$(function () {
    $('#hello').on('click', function (e) {
        e.preventDefault()
        alert('Alert from hello')
    })
});