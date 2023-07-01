jQuery(function ($) {
  const wtpAdminScripts = {
    init: function () {
      let url = window.location.href;
      if (url.includes('action=edit') || url.includes('post-new.php')) {
        this.addProducts();
        this.displayProductDetails();
        this.editProductDetails();
        this.deleteProductDetails();
        this.addShipmentHistory();
        this.displayShipmentHistory();
        this.editShipmentHistory();
        this.deleteShipmentHistory();
        this.sortShipmentHistory();
      }
    },
    addProducts() {
      $(".button-add-wrap .wtp-button.wtp-add-product").on("click", function () {
        Swal.fire({
          title: "Add Item",
          html: wtpAdminScripts.getFieldJSON(),
          showCancelButton: true,
          cancelButtonText: "Cancel",
          confirmButtonText: "Save",
          reverseButtons: true,
          allowOutsideClick: () => !Swal.isLoading(),
          preConfirm: () => {
            wtpAdminScripts.fieldValidation();
          },
        }).then((result) => {
          if (result.isConfirmed) {
            wtpAdminScripts.insertProductDetails();
          }
        });
      });
    },
    getFieldJSON() {
      let parsedProductFieldsJson = $.parseJSON(
        $(".wtp-product-fields-json").attr("json-fields")
      );
      let getFields =
        "<div class='wtp-loading'></div><div class='wtp-wrap-field-modal'>";
      let counter = 0;
      parsedProductFieldsJson.forEach((field) => {
        let hide_field_class = "d-block";
        let required_field = "";

        if (field.hide == 1) {
          hide_field_class = "d-none";
        }

        if (field.required == 1) {
          required_field = "required";
        }

        if (
          field.type == "text" ||
          field.type == "number" ||
          field.type == "hidden" ||
          field.type == "date" ||
          field.type == "time"
        ) {
          getFields += `<div class="wtp-field ${hide_field_class} ${field.name}"><label>${field.label}</label><input type="${field.type}" 
          name="${field.name}" value="${field.value}" ${required_field} /></div>`;
        }

        if (
          field.type == "textarea"
        ) {
          getFields += `<div class="wtp-field ${hide_field_class} ${field.name}"><label>${field.label}</label><textarea ${required_field} name="${field.name}" rows="1" style="height: 38px;">${field.value}</textarea></div>`;
        }

        if (field.type == "select") {
          let splitOptionValue = field.option_value.split(",");
          let optVal = `<option value="">${field.placeholder}</option>`;
          splitOptionValue.forEach((optionValue) => {
            optVal += `<option value="${optionValue}">${optionValue}</option>`;
          });
          getFields += `<div class="wtp-field ${hide_field_class} ${field.name}"><label>${field.label}</label><select ${required_field} name="${field.name}">${optVal}</select></div>`;
        }
        counter++;
      });
      getFields += "</div>";
      return getFields;
    },
    insertProductDetails() {
      let parsedProductFieldsJson = $.parseJSON(
        $(".wtp-product-fields-json").attr("json-fields")
      );
      let getPostID = $("input#post_ID").val();
      let getData = {
        action: "wtp_product_information_save",
        postID: getPostID,
      };
      parsedProductFieldsJson.forEach((field) => {
        let getFieldValue = "";
        if (
          field.type == "text" ||
          field.type == "number" ||
          field.type == "hidden"
        ) {
          getFieldValue = $(`.wtp-field.${field.name} input`).val();
        }
        if (field.type == "select") {
          getFieldValue = $(`.wtp-field.${field.name} select`).val();
        }
        getData[field.name] = getFieldValue;
      });

      $.ajax({
        url: wtp_params.ajax_url,
        type: "POST",
        data: getData,
        dataType: "JSON",
        beforeSend: function () {
          Swal.fire({
            title: "Loading...",
            text: "Please wait",
            showConfirmButton: false,
            allowOutsideClick: false,
          });
          Swal.showLoading();
        },
        success: function (response) {
          let swal_icon = "error";
          let swal_title = "Error";
          let swal_text = "Something went wrong!";

          if (response.status == true) {
            swal_icon = "success";
            swal_title = "Success";
            swal_text = "Product Information has been successfully saved!";
            $("#wtp-product-information").append(
              `<div class="wtp-row" id="wtp-row-${response.fields["wtp-field-product-info-id"]}"></div>`
            );
            setTimeout(function () {
              parsedProductFieldsJson.forEach((field) => {
                if (field.display_metabox == 1) {
                  let field_key = field.name;
                  $(
                    `#wtp-row-${response.fields["wtp-field-product-info-id"]}`
                  ).append(
                    `<div class="wtp-fields ${field_key}"><p>${response.fields[field_key]}</p></div>`
                  );
                }
              });
              let get_product_info_id =
                response.fields["wtp-field-product-info-id"];
              $(
                `#wtp-row-${response.fields["wtp-field-product-info-id"]}`
              ).append(`<div class="wtp-fields wtp-field-action"><div type="button" class="wtp-button wtp-view swal2-styled" 
              name="wtp-product-edit" btn-action="view" prod-info-id="${get_product_info_id}" placeholder="View"><span class="dashicons dashicons-visibility"></span> View</div><div type="button" 
              class="wtp-button wtp-edit swal2-styled" name="wtp-product-edit" prod-info-id="${get_product_info_id}" btn-action="edit"><span class="dashicons dashicons-edit-page"></span> Edit </div>
              <div type="button" class="wtp-button wtp-delete swal2-styled" name="wtp-product-delete" btn-action="delete" prod-info-id="${get_product_info_id}"><span class="dashicons dashicons-trash"></span> Delete</div></div>`);
              wtpAdminScripts.displayProductDetails();
              wtpAdminScripts.editProductDetails();
              wtpAdminScripts.deleteProductDetails();
              wtpAdminScripts.displayShipmentHistory();
              wtpAdminScripts.editShipmentHistory();
              wtpAdminScripts.deleteShipmentHistory();
            }, 1000);
          }
          Swal.fire({
            icon: swal_icon,
            title: swal_title,
            text: swal_text,
          });
        },
      });
    },
    fieldValidation() {
      let parsedProductFieldsJson = $.parseJSON(
        $(".wtp-product-fields-json").attr("json-fields")
      );

      parsedProductFieldsJson.forEach((field) => {
        let getFieldValue = "";
        let required_field = "";

        if (field.required == 1) {
          required_field = "required";

          if (field.type == "text" || field.type == "number" || field.type == "date" || field.type == "time") {
            getFieldValue = $(`.wtp-field.${field.name} input`).val();
          }
          if (field.type == "textarea") {
            getFieldValue = $(`.wtp-field.${field.name} textarea`).val();
          }
          if (field.type == "select") {
            getFieldValue = $(`.wtp-field.${field.name} select`).val();
          }
          if (!getFieldValue) {
            Swal.showValidationMessage(`Please fill out the required fields.`);
            $(`.wtp-field.${field.name} input`).addClass("wtp-error-field");
            $(`.wtp-field.${field.name} select`).addClass("wtp-error-field");
            $(`.wtp-field.${field.name} textarea`).addClass("wtp-error-field");
          } else {
            $(`.wtp-field.${field.name} input`).removeClass("wtp-error-field");
            $(`.wtp-field.${field.name} select`).removeClass("wtp-error-field");
            $(`.wtp-field.${field.name} textarea`).removeClass("wtp-error-field");
          }
        }
      });
    },
    displayProductDetails() {
      $("#wtp-product-information .wtp-button.wtp-view").on("click", function () {
        let productInfoID = $(this).attr("prod-info-id");
        let getPostID = $("input#post_ID").val();

        Swal.fire({
          title: "Item Details",
          html: wtpAdminScripts.getProductInfoDetails(productInfoID, getPostID),
          confirmButtonText: "Okay",
          didOpen: () => {
            Swal.showLoading();
          },
        });
      });
    },
    getProductInfoDetails(productInfoID, getPostID) {
      let getData = {
        action: "wtp_product_information_view",
        postID: getPostID,
        productID: productInfoID,
      };

      $.ajax({
        url: wtp_params.ajax_url,
        type: "POST",
        data: getData,
        dataType: "HTML",
        success: function (response) {
          $(".wtp-view-results").html(response);
          Swal.hideLoading();
        },
      });
      return `<div class="wtp-view-results"></div>`;
    },
    editProductDetails() {
      $("#wtp-product-information .wtp-button.wtp-edit").on("click", function () {
        let productInfoID = $(this).attr("prod-info-id");
        let getPostID = $("input#post_ID").val();

        Swal.fire({
          title: "Edit Item",
          html: wtpAdminScripts.getFieldJSON(),
          showCancelButton: true,
          cancelButtonText: "Cancel",
          confirmButtonText: "Save",
          reverseButtons: true,
          allowOutsideClick: () => !Swal.isLoading(),
          didOpen: () => {
            Swal.showLoading();
            wtpAdminScripts.getCurrentProductDetails(productInfoID, getPostID);
          },
          preConfirm: () => {
            wtpAdminScripts.fieldValidation();
          },
        }).then((result) => {
          if (result.isConfirmed) {
            wtpAdminScripts.updateProductDetails(productInfoID, getPostID);
          }
        });
      });
    },
    getCurrentProductDetails(productInfoID, getPostID) {
      let getData = {
        action: "wtp_product_information_edit",
        postID: getPostID,
        productID: productInfoID,
      };

      $.ajax({
        url: wtp_params.ajax_url,
        type: "POST",
        data: getData,
        dataType: "JSON",
        success: function (response) {
          let parsedProductFieldsJson = $.parseJSON(
            $(".wtp-product-fields-json").attr("json-fields")
          );
          parsedProductFieldsJson.forEach((field) => {
            if (
              field.type == "text" ||
              field.type == "number" ||
              field.type == "hidden"
            ) {
              $(`.wtp-field.${field.name} input`).val(response[field.name]);
            }
            if (field.type == "select") {
              $(`.wtp-field.${field.name} select`).val(response[field.name]);
            }
          });
          Swal.hideLoading();
        },
      });
    },
    updateProductDetails(productInfoID, getPostID) {
      let parsedProductFieldsJson = $.parseJSON(
        $(".wtp-product-fields-json").attr("json-fields")
      );

      let getData = {
        action: "wtp_product_information_update",
        postID: getPostID,
        productID: productInfoID,
      };
      parsedProductFieldsJson.forEach((field) => {
        let getFieldValue = "";
        if (
          field.type == "text" ||
          field.type == "number" ||
          field.type == "hidden"
        ) {
          getFieldValue = $(`.wtp-field.${field.name} input`).val();
        }
        if (field.type == "select") {
          getFieldValue = $(`.wtp-field.${field.name} select`).val();
        }
        getData[field.name] = getFieldValue;
      });

      $.ajax({
        url: wtp_params.ajax_url,
        type: "POST",
        data: getData,
        dataType: "JSON",
        beforeSend: function () {
          Swal.fire({
            title: "Loading...",
            text: "Please wait",
            showConfirmButton: false,
            allowOutsideClick: false,
          });
          Swal.showLoading();
        },
        success: function (response) {
          let swal_icon = "error";
          let swal_title = "Error";
          let swal_text = "Something went wrong!";
          if (response.status == "no-rows-updated") {
            swal_icon = "success";
            swal_title = "Success";
            swal_text = "No rows has been updated!";
          }
          if (response.status == true) {
            swal_icon = "success";
            swal_title = "Success";
            swal_text = "Product Information has been successfully updated!";
            $(`#wtp-row-${response.fields["wtp-field-product-info-id"]}`).html(
              `<img class="wtp-loading-gif" src='${wtp_params.wtp_admin_url}/images/spinner-2x.gif'>`
            );
            setTimeout(function () {
              $(
                `#wtp-row-${response.fields["wtp-field-product-info-id"]} .wtp-loading-gif`
              ).remove();
              parsedProductFieldsJson.forEach((field) => {
                if (field.display_metabox == 1) {
                  let field_key = field.name;
                  $(
                    `#wtp-row-${response.fields["wtp-field-product-info-id"]}`
                  ).append(
                    `<div class="wtp-fields ${field_key}"><p>${response.fields[field_key]}</p></div>`
                  );
                }
              });
              let get_product_info_id =
                response.fields["wtp-field-product-info-id"];
              $(
                `#wtp-row-${response.fields["wtp-field-product-info-id"]}`
              ).append(`<div class="wtp-fields wtp-field-action"><div type="button" class="wtp-button wtp-view swal2-styled" 
              name="wtp-product-edit" btn-action="view" prod-info-id="${get_product_info_id}" placeholder="View"><span class="dashicons dashicons-visibility"></span> View</div><div type="button" 
              class="wtp-button wtp-edit swal2-styled" name="wtp-product-edit" prod-info-id="${get_product_info_id}" btn-action="edit"><span class="dashicons dashicons-edit-page"></span> Edit </div>
              <div type="button" class="wtp-button wtp-delete swal2-styled" name="wtp-product-delete" btn-action="delete" prod-info-id="${get_product_info_id}"><span class="dashicons dashicons-trash"></span> Delete</div></div>`);
              wtpAdminScripts.displayProductDetails();
              wtpAdminScripts.editProductDetails();
              wtpAdminScripts.deleteProductDetails();
              wtpAdminScripts.displayShipmentHistory();
              wtpAdminScripts.editShipmentHistory();
              wtpAdminScripts.deleteShipmentHistory();
            }, 1000);
          }
          Swal.fire({
            icon: swal_icon,
            title: swal_title,
            text: swal_text,
          });
        },
      });
    },
    deleteProductDetails() {
      $("#wtp-product-information .wtp-button.wtp-delete").on("click", function () {
        let productInfoID = $(this).attr("prod-info-id");
        let getPostID = $("input#post_ID").val();

        Swal.fire({
          title: "Delete Item",
          html: '<div class="wtp-delete-details"><p>Are you sure you want to delete this item?</p></div>',
          showCancelButton: true,
          cancelButtonText: "Cancel",
          confirmButtonText: "Yes",
          reverseButtons: true,
          allowOutsideClick: () => !Swal.isLoading(),
        }).then((result) => {
          if (result.isConfirmed) {
            let getData = {
              action: "wtp_product_information_delete",
              postID: getPostID,
              productID: productInfoID,
            };
            $.ajax({
              url: wtp_params.ajax_url,
              type: "POST",
              data: getData,
              dataType: "JSON",
              beforeSend: function () {
                Swal.fire({
                  title: "Loading...",
                  text: "Please wait",
                  showConfirmButton: false,
                  allowOutsideClick: false,
                });
                Swal.showLoading();
              },
              success: function (response) {
                let swal_icon = "error";
                let swal_title = "Error";
                let swal_text = "Something went wrong!";
                if (response.status == true) {
                  swal_icon = "success";
                  swal_title = "Deleted!";
                  swal_text = "Product Information has been successfully deleted!";
                  $(`#wtp-product-information #wtp-row-${response.productID} .wtp-loading-gif`).remove();
                  $(`#wtp-product-information #wtp-row-${response.productID}`).remove();
                }
                Swal.fire({
                  icon: swal_icon,
                  title: swal_title,
                  text: swal_text,
                });
                wtpAdminScripts.displayProductDetails();
                wtpAdminScripts.editProductDetails();
                wtpAdminScripts.displayShipmentHistory();
                wtpAdminScripts.editShipmentHistory();
                wtpAdminScripts.deleteShipmentHistory();
              }
            });
          }
        });
      });
    },
    addShipmentHistory() {
      $("#wtp-shipment-history-information .button-add-wrap .wtp-button.wtp-add-shipment-history").on("click", function () {
        let shipmentHistoryAction = 'Add';
        Swal.fire({
          title: "Add Shipment History",
          html: wtpAdminScripts.getShipmentHistoryJSON(shipmentHistoryAction),
          showCancelButton: true,
          cancelButtonText: "Cancel",
          confirmButtonText: "Save",
          reverseButtons: true,
          allowOutsideClick: () => !Swal.isLoading(),
          preConfirm: () => {
            wtpAdminScripts.fieldValidationSH();
          },
        }).then((result) => {
          if (result.isConfirmed) {
            wtpAdminScripts.insertShipmentHistory();
          }
        });
      });
    },
    getShipmentHistoryJSON(shipmentHistoryAction = '') {
      let parsedSHFieldsJson = $.parseJSON(
        $(".wtp-shipment-history-fields-json").attr("json-fields")
      );
      let getFields =
        "<div class='wtp-loading'></div><div class='wtp-wrap-field-modal'>";
      let counter = 0;
      parsedSHFieldsJson.forEach((field) => {
        let hide_field_class = "d-block";
        let required_field = "";

        if (field.hide == 1) {
          hide_field_class = "d-none";
        }

        if (field.required == 1) {
          required_field = "required";
        }

        if (
          field.type == "text" ||
          field.type == "number" ||
          field.type == "hidden" ||
          field.type == "date" ||
          field.type == "time"
        ) {
          getFields += `<div class="wtp-field ${hide_field_class} ${field.name}"><label>${field.label}</label><input type="${field.type}" 
          name="${field.name}" value="${field.value}" ${required_field} /></div>`;
        }

        if (
          field.type == "textarea"
        ) {
          getFields += `<div class="wtp-field ${hide_field_class} ${field.name}"><label>${field.label}</label><textarea ${required_field} name="${field.name}" rows="1" style="height: 38px;">${field.value}</textarea></div>`;
        }

        if (field.type == "select") {
          let splitOptionValue = field.option_value.split(",");
          let optVal = `<option value="">${field.placeholder}</option>`;
          splitOptionValue.forEach((optionValue) => {
            optVal += `<option value="${optionValue}">${optionValue}</option>`;
          });
          getFields += `<div class="wtp-field ${hide_field_class} ${field.name}"><label>${field.label}</label><select ${required_field} name="${field.name}">${optVal}</select></div>`;
        }
        counter++;
      });
      getFields += "</div>";
      if (shipmentHistoryAction == 'Add') {
        setTimeout(function () { wtpAdminScripts.setDefaultDate(); wtpAdminScripts.setDefaultTime(); }, 1000);
      }
      return getFields;
    },
    fieldValidationSH() {
      let parsedSHFieldsJson = $.parseJSON(
        $(".wtp-shipment-history-fields-json").attr("json-fields")
      );

      parsedSHFieldsJson.forEach((field) => {
        let getFieldValue = "";
        let required_field = "";

        if (field.required == 1) {
          required_field = "required";

          if (field.type == "text" || field.type == "number" || field.type == "date" || field.type == "time") {
            getFieldValue = $(`.wtp-field.${field.name} input`).val();
          }
          if (field.type == "textarea") {
            getFieldValue = $(`.wtp-field.${field.name} textarea`).val();
          }
          if (field.type == "select") {
            getFieldValue = $(`.wtp-field.${field.name} select`).val();
          }
          if (!getFieldValue) {
            Swal.showValidationMessage(`Please fill out the required fields.`);
            $(`.wtp-field.${field.name} input`).addClass("wtp-error-field");
            $(`.wtp-field.${field.name} select`).addClass("wtp-error-field");
            $(`.wtp-field.${field.name} textarea`).addClass("wtp-error-field");
          } else {
            $(`.wtp-field.${field.name} input`).removeClass("wtp-error-field");
            $(`.wtp-field.${field.name} select`).removeClass("wtp-error-field");
            $(`.wtp-field.${field.name} textarea`).removeClass("wtp-error-field");
          }
        }
      });
    },
    insertShipmentHistory() {
      let parsedSHFieldsJson = $.parseJSON(
        $(".wtp-shipment-history-fields-json").attr("json-fields")
      );
      let getPostID = $("input#post_ID").val();
      let getData = {
        action: "wtp_shipment_history_save",
        postID: getPostID,
      };
      parsedSHFieldsJson.forEach((field) => {
        let getFieldValue = "";
        if (
          field.type == "text" ||
          field.type == "number" ||
          field.type == "hidden" ||
          field.type == "date" ||
          field.type == "time"
        ) {
          getFieldValue = $(`.wtp-field.${field.name} input`).val();
        }
        if (field.type == "textarea") {
          getFieldValue = $(`.wtp-field.${field.name} textarea`).val();
        }
        if (field.type == "select") {
          getFieldValue = $(`.wtp-field.${field.name} select`).val();
        }
        getData[field.name] = getFieldValue;
      });

      $.ajax({
        url: wtp_params.ajax_url,
        type: "POST",
        data: getData,
        dataType: "JSON",
        beforeSend: function () {
          Swal.fire({
            title: "Loading...",
            text: "Please wait",
            showConfirmButton: false,
            allowOutsideClick: false,
          });
          Swal.showLoading();
        },
        success: function (response) {
          let swal_icon = "error";
          let swal_title = "Error";
          let swal_text = "Something went wrong!";

          if (response.status == true) {
            swal_icon = "success";
            swal_title = "Success";
            swal_text = "Product Information has been successfully saved!";
            $("#wtp-shipment-history-information").append(
              `<div class="wtp-row" id="wtp-row-${response.fields["wtp-field-shipment-history-id"]}"></div>`
            );
            setTimeout(function () {
              parsedSHFieldsJson.forEach((field) => {
                if (field.display_metabox == 1) {
                  let field_key = field.name;
                  $(
                    `#wtp-row-${response.fields["wtp-field-shipment-history-id"]}`
                  ).append(
                    `<div class="wtp-fields ${field_key}"><p>${response.fields[field_key]}</p></div>`
                  );
                }
              });
              let get_product_info_id = response.fields["wtp-field-shipment-history-id"];

              $(`#wtp-row-${response.fields["wtp-field-shipment-history-id"]}`).append(`<div class="wtp-fields wtp-field-action"><div type="button" class="wtp-button wtp-view swal2-styled" 
              name="wtp-product-edit" btn-action="view" sh-id="${get_product_info_id}" placeholder="View"><span class="dashicons dashicons-visibility"></span> View</div><div type="button" 
              class="wtp-button wtp-edit swal2-styled" name="wtp-product-edit" sh-id="${get_product_info_id}" btn-action="edit"><span class="dashicons dashicons-edit-page"></span> Edit </div>
              <div type="button" class="wtp-button wtp-delete swal2-styled" name="wtp-product-delete" btn-action="delete" sh-id="${get_product_info_id}"><span class="dashicons dashicons-trash"></span> Delete</div></div>`);
              wtpAdminScripts.displayProductDetails();
              wtpAdminScripts.editProductDetails();
              wtpAdminScripts.deleteProductDetails();
              wtpAdminScripts.displayShipmentHistory();
              wtpAdminScripts.editShipmentHistory();
              wtpAdminScripts.deleteShipmentHistory();
            }, 1000);
          }
          Swal.fire({
            icon: swal_icon,
            title: swal_title,
            text: swal_text,
          });
        },
        complete: function () {
          setTimeout(function () {
            wtpAdminScripts.sortShipmentHistory();
          }, 1000);
        }
      });
    },
    displayShipmentHistory() {
      $("#wtp-shipment-history-information .wtp-button.wtp-view").on("click", function () {
        let shipmentHistoryID = $(this).attr("sh-id");
        let getPostID = $("input#post_ID").val();

        Swal.fire({
          title: "Shipment History Details",
          html: wtpAdminScripts.getShipmentHistoryDetails(shipmentHistoryID, getPostID),
          confirmButtonText: "Okay",
          didOpen: () => {
            Swal.showLoading();
          },
        });
      });
    },
    getShipmentHistoryDetails(shipmentHistoryID, getPostID) {
      let getData = {
        action: "wtp_shipment_history_view",
        postID: getPostID,
        shipmentHistoryID: shipmentHistoryID,
      };

      $.ajax({
        url: wtp_params.ajax_url,
        type: "POST",
        data: getData,
        dataType: "HTML",
        success: function (response) {
          $(".wtp-view-results").html(response);
          Swal.hideLoading();
        },
      });
      return `<div class="wtp-view-results"></div>`;
    },
    editShipmentHistory() {
      $("#wtp-shipment-history-information .wtp-button.wtp-edit").on("click", function () {
        let shipmentHistoryID = $(this).attr("sh-id");
        let getPostID = $("input#post_ID").val();

        Swal.fire({
          title: "Edit Item",
          html: wtpAdminScripts.getShipmentHistoryJSON(),
          showCancelButton: true,
          cancelButtonText: "Cancel",
          confirmButtonText: "Save",
          reverseButtons: true,
          allowOutsideClick: () => !Swal.isLoading(),
          didOpen: () => {
            Swal.showLoading();
            wtpAdminScripts.getCurrentShipmentHistory(shipmentHistoryID, getPostID);
          },
          preConfirm: () => {
            wtpAdminScripts.fieldValidationSH();
          },
        }).then((result) => {
          if (result.isConfirmed) {
            wtpAdminScripts.updateShipmentHistory(shipmentHistoryID, getPostID);
          }
        });
      });
    },
    getCurrentShipmentHistory(shipmentHistoryID, getPostID) {
      let getData = {
        action: "wtp_shipment_history_edit",
        postID: getPostID,
        shipmentHistoryID: shipmentHistoryID,
      };

      $.ajax({
        url: wtp_params.ajax_url,
        type: "POST",
        data: getData,
        dataType: "JSON",
        success: function (response) {
          let parsedSHJson = $.parseJSON(
            $(".wtp-shipment-history-fields-json").attr("json-fields")
          );
          parsedSHJson.forEach((field) => {
            if (
              field.type == "text" ||
              field.type == "number" ||
              field.type == "hidden" ||
              field.type == "date" ||
              field.type == "time"
            ) {
              getFieldValue = $(`.wtp-field.${field.name} input`).val(response[field.name]);
            }
            if (field.type == "textarea") {
              getFieldValue = $(`.wtp-field.${field.name} textarea`).val(response[field.name]);
            }
            if (field.type == "select") {
              getFieldValue = $(`.wtp-field.${field.name} select`).val(response[field.name]);
            }
          });
          Swal.hideLoading();
        },
      });
    },
    updateShipmentHistory(shipmentHistoryID, getPostID) {
      let parsedSHJson = $.parseJSON(
        $(".wtp-shipment-history-fields-json").attr("json-fields")
      );

      let getData = {
        action: "wtp_shipment_history_update",
        postID: getPostID,
        shipmentHistoryID: shipmentHistoryID,
      };
      parsedSHJson.forEach((field) => {
        let getFieldValue = "";
        if (
          field.type == "text" ||
          field.type == "number" ||
          field.type == "hidden" ||
          field.type == "date" ||
          field.type == "time"
        ) {
          getFieldValue = $(`.wtp-field.${field.name} input`).val();
        }
        if (field.type == "textarea") {
          getFieldValue = $(`.wtp-field.${field.name} textarea`).val();
        }
        if (field.type == "select") {
          getFieldValue = $(`.wtp-field.${field.name} select`).val();
        }
        getData[field.name] = getFieldValue;
      });

      $.ajax({
        url: wtp_params.ajax_url,
        type: "POST",
        data: getData,
        dataType: "JSON",
        beforeSend: function () {
          Swal.fire({
            title: "Loading...",
            text: "Please wait",
            showConfirmButton: false,
            allowOutsideClick: false,
          });
          Swal.showLoading();
        },
        success: function (response) {
          let swal_icon = "error";
          let swal_title = "Error";
          let swal_text = "Something went wrong!";
          if (response.status == "no-rows-updated") {
            swal_icon = "success";
            swal_title = "Success";
            swal_text = "No rows has been updated!";
          }
          if (response.status == true) {
            swal_icon = "success";
            swal_title = "Success";
            swal_text = "Shipment History has been successfully updated!";
            $(`#wtp-row-${response.fields["wtp-field-shipment-history-id"]}`).html(
              `<img class="wtp-loading-gif" src='${wtp_params.wtp_admin_url}/images/spinner-2x.gif'>`
            );
            setTimeout(function () {
              $(
                `#wtp-row-${response.fields["wtp-field-shipment-history-id"]} .wtp-loading-gif`
              ).remove();
              parsedSHJson.forEach((field) => {
                if (field.display_metabox == 1) {
                  let field_key = field.name;
                  $(
                    `#wtp-row-${response.fields["wtp-field-shipment-history-id"]}`
                  ).append(
                    `<div class="wtp-fields ${field_key}"><p>${response.fields[field_key]}</p></div>`
                  );
                }
              });
              let get_sh_id =
                response.fields["wtp-field-shipment-history-id"];
              $(
                `#wtp-row-${response.fields["wtp-field-shipment-history-id"]}`
              ).append(`<div class="wtp-fields wtp-field-action"><div type="button" class="wtp-button wtp-view swal2-styled" 
              name="wtp-product-edit" btn-action="view" sh-id="${get_sh_id}" placeholder="View"><span class="dashicons dashicons-visibility"></span> View</div><div type="button" 
              class="wtp-button wtp-edit swal2-styled" name="wtp-product-edit" sh-id="${get_sh_id}" btn-action="edit"><span class="dashicons dashicons-edit-page"></span> Edit </div>
              <div type="button" class="wtp-button wtp-delete swal2-styled" name="wtp-product-delete" btn-action="delete" sh-id="${get_sh_id}"><span class="dashicons dashicons-trash"></span> Delete</div></div>`);
              wtpAdminScripts.displayProductDetails();
              wtpAdminScripts.editProductDetails();
              wtpAdminScripts.deleteProductDetails();
              wtpAdminScripts.displayShipmentHistory();
              wtpAdminScripts.editShipmentHistory();
            }, 1000);
          }
          Swal.fire({
            icon: swal_icon,
            title: swal_title,
            text: swal_text,
          });
        },
        complete: function () {
          setTimeout(function () {
            wtpAdminScripts.sortShipmentHistory();
          }, 1000);
        }
      });
    },
    deleteShipmentHistory() {
      $("#wtp-shipment-history-information .wtp-button.wtp-delete").on("click", function () {
        let shipmentHistoryID = $(this).attr("sh-id");
        let getPostID = $("input#post_ID").val();

        Swal.fire({
          title: "Delete Shipment History",
          html: '<div class="wtp-delete-details"><p>Are you sure you want to delete this item?</p></div>',
          showCancelButton: true,
          cancelButtonText: "Cancel",
          confirmButtonText: "Yes",
          reverseButtons: true,
          allowOutsideClick: () => !Swal.isLoading(),
        }).then((result) => {
          if (result.isConfirmed) {
            let getData = {
              action: "wtp_shipment_history_delete",
              postID: getPostID,
              shipmentHistoryID: shipmentHistoryID,
            };
            $.ajax({
              url: wtp_params.ajax_url,
              type: "POST",
              data: getData,
              dataType: "JSON",
              beforeSend: function () {
                Swal.fire({
                  title: "Loading...",
                  text: "Please wait",
                  showConfirmButton: false,
                  allowOutsideClick: false,
                });
                Swal.showLoading();
              },
              success: function (response) {
                let swal_icon = "error";
                let swal_title = "Error";
                let swal_text = "Something went wrong!";
                if (response.status == true) {
                  swal_icon = "success";
                  swal_title = "Deleted!";
                  swal_text = "Product Information has been successfully deleted!";
                  $(`#wtp-shipment-history-information #wtp-row-${response.shipmenthistoryID} .wtp-loading-gif`).remove();
                  $(`#wtp-shipment-history-information #wtp-row-${response.shipmenthistoryID}`).remove();
                }
                Swal.fire({
                  icon: swal_icon,
                  title: swal_title,
                  text: swal_text,
                });
                wtpAdminScripts.displayProductDetails();
                wtpAdminScripts.editProductDetails();
                wtpAdminScripts.displayShipmentHistory();
                wtpAdminScripts.editShipmentHistory();
              },
              complete: function () {
                setTimeout(function () {
                  wtpAdminScripts.sortShipmentHistory();
                }, 1000);
              }
            });
          }
        });
      });
    },
    sortShipmentHistory() {
      let getPostID = $("input#post_ID").val();
      let getData = {
        action: "wtp_shipment_history_sort",
        postID: getPostID,
      };
      $.ajax({
        url: wtp_params.ajax_url,
        type: "POST",
        data: getData,
        dataType: "HTML",
        beforeSend: function () {
          Swal.fire({
            title: "<h6>Sorting Shipment History Details...</h6>",
            text: "Please wait",
            showConfirmButton: false,
            allowOutsideClick: false,
          });
          Swal.showLoading();
        },
        success: function (response) {
          Swal.hideLoading();
          Swal.close();
          $('.wtp-sh-wrap-rows').html(response);
          wtpAdminScripts.displayProductDetails();
          wtpAdminScripts.editProductDetails();
          wtpAdminScripts.displayShipmentHistory();
          wtpAdminScripts.editShipmentHistory();
          wtpAdminScripts.deleteShipmentHistory();
        }
      });
    },
    setDefaultDate() {
      let today = new Date();
      $(".wtp-field input[type='date']").val(today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2));
    },
    setDefaultTime() {
      var time = new Date();
      $(".wtp-field input[type='time']").val(time.getHours() + ':' + time.getMinutes());
    }
  };
  $(document).ready(function () {
    wtpAdminScripts.init();
  });
});
