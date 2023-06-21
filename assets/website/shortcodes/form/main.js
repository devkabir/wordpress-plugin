import './../style.scss';
import $ from 'jquery';
import {Notyf} from 'notyf';

let notify = new Notyf();
$(document).ready(
    function () {
        $('#quote-form').on(
            'submit',
            function (e) {
                e.preventDefault();
                $.ajax(
                    {
                        method: 'POST',
                        url: window.location.origin + '/wp-admin/admin-ajax.php',
                        accepts: 'application/json',
                        data: {
                            nonce: your_plugin_name.nonce, action: 'save_message', message: $(this).serializeArray()
                        }
                    }
                )
                    .done(
                        response => {
                            if (response.success) {
                                notify.success(response.data);
                                $(this).trigger('reset');
                            } else {
                                notify.error(response.data);
                            }

                        }
                    )
                    .fail(error => notify.error(error.message));
            }
        );
    }
);
