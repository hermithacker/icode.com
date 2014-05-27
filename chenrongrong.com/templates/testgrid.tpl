<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>My First Grid</title>
 
<link rel="stylesheet" type="text/css" media="screen" href="../css/ui-southstreet/jquery-ui-1.10.4.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../css/ui.jqgrid.css" />
 
<!--http://www.trirand.com/blog/jqgrid/jqgrid.html-->
<script src="../js/jquery-1.10.2.js" type="text/javascript"></script>
<script src="../js/src/i18n/grid.locale-en.js" type="text/javascript"></script>
<script src="../js/jquery.jqGrid.min.js" type="text/javascript"></script>
<script src="../js/src/jqDnR.js" type="text/javascript"></script>
<script src="../js/src/jqModal.js" type="text/javascript"></script>
</head>
<script>
$(function () {
    $("#list").jqGrid({
        url: "../class/example.php",
        datatype: "xml",
        mtype: "GET",
        colNames: ["Inv No", "Date", "Amount", "Tax", "Total", "Notes"],
        colModel: [
            { name: "invid", width: 55 },
            { name: "invdate", width: 90 },
            { name: "amount", width: 80, align: "right" },
            { name: "tax", width: 80, align: "right" },
            { name: "total", width: 80, align: "right" },
            { name: "note", width: 150, sortable: false }
        ],
        pager: "#pager",
        rowNum: 10,
        rowList: [10, 20, 30],
        sortname: "invid",
        sortorder: "desc",
        viewrecords: true,
        gridview: true,
        autoencode: true,
        caption: "My first grid"
    }); 
}); 
</script>
<body>
 <table id="list"><tr><td></td></tr></table> 
 <div id="pager"></div> 
</body>
</html>