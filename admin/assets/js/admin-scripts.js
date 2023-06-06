jQuery(function ($) {
  const adminScripts = {
    init: function () {
      this.addProducts();
      this.displayProductDetails();
      this.editProductDetails();
    },
    addProducts() {
      $(".button-add-wrap .wtp-button").on("click", function () {
        Swal.fire({
          title: "Add Item",
          html: adminScripts.getFieldJSON(),
          showCancelButton: true,
          cancelButtonText: "Cancel",
          confirmButtonText: "Save",
          reverseButtons: true,
          allowOutsideClick: () => !Swal.isLoading(),
          preConfirm: () => {
            adminScripts.fieldValidation();
          },
        }).then((result) => {
          if (result.isConfirmed) {
            adminScripts.insertProductDetails();
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
          field.type == "hidden"
        ) {
          getFields += `<div class="wtp-field ${hide_field_class} ${field.name}"><label>${field.label}</label><input type="${field.type}" 
          name="${field.name}" value="${field.value}" ${required_field} /></div>`;
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
              adminScripts.displayProductDetails();
              adminScripts.editProductDetails();
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

          if (field.type == "text" || field.type == "number") {
            getFieldValue = $(`.wtp-field.${field.name} input`).val();
          }
          if (field.type == "select") {
            getFieldValue = $(`.wtp-field.${field.name} select`).val();
          }
          if (!getFieldValue) {
            Swal.showValidationMessage(`Please fill out the required fields.`);
            $(`.wtp-field.${field.name} input`).addClass("wtp-error-field");
            $(`.wtp-field.${field.name} select`).addClass("wtp-error-field");
          } else {
            $(`.wtp-field.${field.name} input`).removeClass("wtp-error-field");
            $(`.wtp-field.${field.name} select`).removeClass("wtp-error-field");
          }
        }
      });
    },
    displayProductDetails() {
      $(".wtp-button.wtp-view").on("click", function () {
        let productInfoID = $(this).attr("prod-info-id");
        let getPostID = $("input#post_ID").val();

        Swal.fire({
          title: "Item Details",
          html: adminScripts.getProductInfoDetails(productInfoID, getPostID),
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
      $(".wtp-button.wtp-edit").on("click", function () {
        let productInfoID = $(this).attr("prod-info-id");
        let getPostID = $("input#post_ID").val();

        Swal.fire({
          title: "Edit Item",
          html: adminScripts.getFieldJSON(),
          showCancelButton: true,
          cancelButtonText: "Cancel",
          confirmButtonText: "Save",
          reverseButtons: true,
          allowOutsideClick: () => !Swal.isLoading(),
          didOpen: () => {
            Swal.showLoading();
            adminScripts.getCurrentProductDetails(productInfoID, getPostID);
          },
          preConfirm: () => {
            adminScripts.fieldValidation();
          },
        }).then((result) => {
          if (result.isConfirmed) {
            adminScripts.updateProductDetails(productInfoID, getPostID);
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
          console.log(response);
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
            swal_text = "Product Information has been successfully saved!";
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
              adminScripts.displayProductDetails();
              adminScripts.editProductDetails();
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
  };
  $(document).ready(function () {
    adminScripts.init();
  });
});
