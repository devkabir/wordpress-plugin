import "./../style.scss";
import { Notyf } from "notyf";

let notify = new Notyf();
let isLoading = false;




(function ($) {
  'use strict';


  /**
     * Handles the form submission event.
     * @param {Event} e - The form submission event.
     */
  $("#quote-form").on("submit", function (e) {
    e.preventDefault();
    let submitButton = $(this).find("button");

    // Check if already loading
    if (true === isLoading) {
      return;
    }

    isLoading = true;
    submitButton.text("Sending...");

    // Make AJAX request
    $.ajax({
      method: "POST",
      url: window.location.origin + "/wp-admin/admin-ajax.php",
      accepts: "application/json",
      data: {
        nonce: your_plugin_name.nonce,
        action: "save_message",
        message: $(this).serializeArray(),
      },
    })
      .done((response) => {
        // Handle success response
        if (response.success) {
          notify.success(response.data);
          $(this).trigger("reset");
        } else {
          // Handle error response
          notify.error(response.data);
        }
      })
      .fail((error) => notify.error(error.message))
      .always(() => {
        isLoading = false;
        submitButton.empty().text("Save");
      });
  });
})(jQuery);
