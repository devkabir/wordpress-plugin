import './../style.scss'
import $ from 'jquery'

$('#quote-form').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        method: 'POST',
        url: 'https://your-domain-name.dev/wp-admin/admin-ajax.php',
        accepts: 'application/json',
        data: {
            nonce: window.your_plugin_name.nonce,
            action: 'save_quote',
            quote: $(this).serialize()
        }
    }).done(response => alert('OK'))
        .fail(error => alert(error))
})