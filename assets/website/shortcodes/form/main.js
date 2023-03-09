import './../style.scss'
import $ from 'jquery'
import {Notyf} from "notyf";

let notyf = new Notyf()
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
                                notyf.success(response.data);
                                $(this).trigger('reset');
                            } else {
                                notyf.error(response.data);
                            }

                        }
                    )
                    .fail(error => notyf.error(error.message))
            }
        )
    }
)
