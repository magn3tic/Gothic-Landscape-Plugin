(function ($) {
  $(document).ready(function () {
    var form = $(".landscape-selection-form");

    // Validator
    // ---------
    jQuery.validator.addMethod(
      "notEqual",
      function (value, element, param) {
        return this.optional(element) || value !== param;
      },
      "Please choose a value!"
    );

    $(form).validate();

    // Toggle Access Forms
    // -------------------
    
    $(".select-login").on("submit", function (event) {
      event.preventDefault();
    });

    $('.select-login input[name="login_type"]').on("change", function () {
      var to_show = $("input[name='login_type']:checked").val();
      $(".login_type_forms form").hide();
      $("#landscape-selection-form-" + to_show).show();
    });

    // Select 2
    // --------

    var dynamic_select = $(form).find(".dynamic-select select");
    var dynamic_select_w_img = $(form).find(".dynamic-select.w-img select");
    var selectBuilder = $(form).find('select[name="builder_id"]');
    var selectCommunity = $(form).find('select[name="community_id"]');
    var selectModel = $(form).find('select[name="model_id"]');
    var selectPackage = $(form).find('select[name="package_id"]');
    var selectBackyard = $(form).find('select[name="backyard_id"]');

    var select = $(form).find("select2img");

    dynamic_select.select2({ width: "100%" });

    selectBuilder.on("select2:select", function (e) {
      selectCommunity.val("-1").trigger("change");
      selectModel.val("-1").trigger("change");
      selectPackage.val("-1").trigger("change");
      selectBackyard.val("-1").trigger("change");

      var builder = $(this).find(":selected").val();
      selectCommunity.select2({
        width: "100%",
        ajax: {
          url:
            gothic_selections_user_script.base_url +
            "builders/" +
            builder +
            "/communities?format=select2",
          dataType: "json",
          delay: 250,
        },
      });
    });

    selectCommunity.on("select2:select", function (e) {
      selectModel.val("-1").trigger("change");
      selectPackage.val("-1").trigger("change");
      selectBackyard.val("-1").trigger("change");

      var community = $(this).find(":selected").val();

      selectModel.select2({
        width: "100%",
        ajax: {
          url:
            gothic_selections_user_script.base_url +
            "communities/" +
            community +
            "/models?format=select2",
          dataType: "json",
          delay: 250,
          results: function (data) {
            return {
              results: $.map(data, function (item) {
                return {
                  text: item.completeName,
                  dataAttr: item.image,
                  id: item.id,
                };
              }),
            };
          },
        },
      });

      $.ajax({
        url:
          gothic_selections_user_script.base_url +
          "communities/" +
          community +
          "/packages/meta",
      }).done(function (data) {
        if (data.front) {
          $(selectPackage).closest(".dynamic-select").show();
          selectPackage.select2({
            width: "100%",
            ajax: {
              url:
                gothic_selections_user_script.base_url +
                "communities/" +
                community +
                "/packages?format=select2",
              dataType: "json",
              delay: 250,
            },
          });
        } else {
          $(selectPackage).closest(".dynamic-select").hide();
        }

        if (data.back) {
          $(selectBackyard).closest(".dynamic-select").show();
          selectBackyard.select2({
            width: "100%",
            ajax: {
              url:
                gothic_selections_user_script.base_url +
                "communities/" +
                community +
                "/packages?backyard=1&format=select2",
              dataType: "json",
              delay: 250,
            },
          });
        } else {
          $(selectBackyard).closest(".dynamic-select").hide();
        }
      });
    });

    dynamic_select_w_img.on("select2:select", function (e) {
      var image = $(this).find(":selected").data("image"),
        title = $(this).find(":selected").text(),
        container = $(this).closest(".field-container");
      container.children("img").each(function () {
        $(this).remove();
      });
      container.append(
        '<img src="' +
          image +
          '" height="160" width="280" alt="' +
          title +
          '" title="' +
          title +
          '"/>'
      );
    });

    // Show/Hide Package Upgrade Warning
    //----------------------------------
    $('input[name="package_id"],input[name="backyard_id"]').on(
      "change",
      function () {
        $(".select .gothic-field-sublabel").hide();
        $(this).closest(".select").find(".gothic-field-sublabel").show();
      }
    );

    $(".js-remind").on("click", function () {
      $(this).val("true");
    });

    // Lightbox
    //-------------------------------
    $('a[href="#notify-problem"]').fancybox({
      maxWidth: 800,
      maxHeight: 600,
      fitToView: false,
      width: "90%",
      height: "90%",
      autoSize: false,
      closeClick: false,
      openEffect: "none",
      closeEffect: "none",
      helpers: {
        overlay: {
          opacity: 0.1,
          css: { "background-color": "#000" },
        },
      },
    });

    $(".landscape-plans .image a, .thanks .image a, .show .image a").fancybox({
      width: 600,
      fitToView: true,
      maxWidth: "100%",
      autoDimensions: false,
      padding: 0,
    });

    $(".palettes .expand a, .palette .expand a").fancybox({
      width: 800,
      fitToView: false,
      maxWidth: "90%",
    });
  });

  $(window).load(function () {
    if ($(".posts-data-table").length) {
      $(".posts-data-table .special-indicator").each(function (index, item) {
        $(item).closest("tr").addClass("important");
      });
      $(".special-indicator--cancelled").each(function (index, item) {
        $(item).closest("tr").addClass("cancelled");
      });
      $(".special-indicator--voided").each(function (index, item) {
        $(item).closest("tr").addClass("cancelled");
      });
      $(".special-indicator--complete").each(function (index, item) {
        $(item).closest("tr").addClass("completed");
      });

      var table = $(".posts-data-table").DataTable();

      $(".posts-data-table thead th").each(function (i) {
        if ($(this).text() !== "") {
          var isStatusColumn = $(this).text() == "Status" ? true : false;

          // All other non-Status columns (like the example)
          if (isStatusColumn) {
            var select = $('<select><option value="">All</option></select>')
              .insertBefore($(this).closest("table"))
              .on("change", function () {
                var val = $(this).val();

                table
                  .column(i)
                  .search(val ? "^" + $(this).val() + "$" : val, true, false)
                  .draw();
              });
            select.wrap(
              "<div class='status-filter-wrapper c-my-6 d-flex justify-content-center align-items-center'></div>"
            );
            select
              .closest(".status-filter-wrapper")
              .prepend("<span class='c-mr-3'> Filter by status: </span>");

            table
              .column(i)
              .data()
              .unique()
              .sort()
              .each(function (d, j) {
                select.append('<option value="' + d + '">' + d + "</option>");
              });
          }
        }
      });
    }
  });

  $("input[type='email']").on("keyup change paste", function (e) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if (e.target.value.length && emailReg.test(e.target.value)) {
      var label = $(this).parent().find($("label.error"));
      if (label.length) {
        label.remove();
      }
    }
  });
})(jQuery);
