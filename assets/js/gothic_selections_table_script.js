// "use strict";
//
// let currentFilter = "",
// 	prevFilter = "",
// 	orderAsc = true;
//
// const toggleOrder = () => {
// 	if (currentFilter === prevFilter) {
// 		orderAsc = !orderAsc;
// 	} else {
// 		orderAsc = true;
// 	}
// }
//
// const sortTable = (array, sortKey) => {
// 	return array.sort((a, b) => {
// 		let x = a[sortKey],
// 		y = b[sortKey];
//
// 	return orderAsc ? x - y : y - x;
// });
// }
//
// const renderTable = tableData => {
// 	return (`${tableData.map(item => {
// 		return (
// 		`<tr>
//                         <td>${item.id}</td>
//                         <td>${item.home_buyer}</td>
//                         <td>${item.email}</td>
//                         <td>${item.lot}</td>
//                         <td>${item.status}</td>
//                     </tr>`
// 		)
// 		}).join('')}`);
// }
//
// const appendTable = (table, destination) => {
// 	document.querySelector(destination).innerHTML = table;
// }
//
// const handleSortClick = () => {
// 	const filters = document.querySelectorAll('table#orders th');
//
// 	Array.prototype.forEach.call(filters, filter => {
// 		filter.addEventListener("click", () => {
// 		if (!filter.dataset.filterValue) return false;
//
// 	Array.prototype.forEach.call(filters, filter => {
// 		filter.classList.remove('active');
// });
// 	filter.classList.add('active');
// 	currentFilter = filter.dataset.filterValue;
// 	toggleOrder();
// 	init();
// });
// })
// }
//
// const initTable = (data) => {
// 	let newTableData = sortTable(data, currentFilter),
// 		tableOutput = renderTable(newTableData);
//
// 	appendTable(tableOutput, 'table#orders tbody');
//
// 	prevFilter = currentFilter;
// }
//
// initTable();
// handleSortClick();

"use strict";

jQuery(function ($) {
  $(".select-login").on("submit", function (event) {
    event.preventDefault();
  });

  $('.select-login input[name="login_type"]').on("change", function () {
    var to_show = $("input[name='login_type']:checked").val();
    $(".login_type_forms form").hide();
    $("#landscape-selection-form-" + to_show).show();
  });
});

var currentFilter = "",
  prevFilter = "",
  orderAsc = true;

var toggleOrder = function toggleOrder() {
  if (currentFilter === prevFilter) {
    orderAsc = !orderAsc;
  } else {
    orderAsc = true;
  }
};

var sortTable = function sortTable(array, sortKey) {
  return array.sort(function (a, b) {
    var x = a[sortKey],
      y = b[sortKey];
    return orderAsc ? x - y : y - x;
  });
};

var renderTable = function renderTable(tableData) {
  return "".concat(
    tableData
      .map(function (item) {
        return "<tr><td>"
          .concat(item.id, "</td><td>")
          .concat(item.home_buyer, "</td><td>")
          .concat(item.email, "</td><td>")
          .concat(item.lot, "</td><td>")
          .concat(item.status, "</td></tr>");
      })
      .join("")
  );
};

var appendTable = function appendTable(table, destination) {
  document.querySelector(destination).innerHTML = table;
};

var handleSortClick = function handleSortClick() {
  var filters = document.querySelectorAll("table#orders th");
  Array.prototype.forEach.call(filters, function (filter) {
    filter.addEventListener("click", function () {
      if (!filter.dataset.filterValue) return false;
      Array.prototype.forEach.call(filters, function (filter) {
        filter.classList.remove("active");
      });
      filter.classList.add("active");
      currentFilter = filter.dataset.filterValue;
      toggleOrder();
      init();
    });
  });
};

var initTable = function initTable(data) {
  var newTableData = sortTable(data, currentFilter),
    tableOutput = renderTable(newTableData);
  appendTable(tableOutput, "table#orders tbody");
  prevFilter = currentFilter;
};

// initTable();
// handleSortClick();
