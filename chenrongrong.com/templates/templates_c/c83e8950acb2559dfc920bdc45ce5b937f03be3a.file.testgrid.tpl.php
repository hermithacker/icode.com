<?php /* Smarty version Smarty-3.1.16, created on 2014-05-12 17:37:57
         compiled from "C:\PhpDevelop\wamp\www\chenrongrong.com\templates\testgrid.tpl" */ ?>
<?php /*%%SmartyHeaderCode:278085370955730b8c4-09015167%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c83e8950acb2559dfc920bdc45ce5b937f03be3a' => 
    array (
      0 => 'C:\\PhpDevelop\\wamp\\www\\chenrongrong.com\\templates\\testgrid.tpl',
      1 => 1399887472,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '278085370955730b8c4-09015167',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_537095573f5ef7_91094158',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_537095573f5ef7_91094158')) {function content_537095573f5ef7_91094158($_smarty_tpl) {?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
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
</html><?php }} ?>
