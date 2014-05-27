<?php
    mysql_connect("localhost", "root", "");
    mysql_select_db("test");
    $result = mysql_query("SELECT * FROM announcements");
    $fields = mysql_num_fields($result);
    $rows   = mysql_num_rows($result);
    $table = mysql_field_table($result, 0);
    echo "Your '".$table."' table has ".$fields." fields and ".$rows." record(s)\n";
    echo "The table has the following fields:\n";
    for ($i=0; $i < $fields; $i++) {
        $type  = mysql_field_type($result, $i);
        $name  = mysql_field_name($result, $i);
        $len   = mysql_field_len($result, $i);
        $flags = mysql_field_flags($result, $i);
        echo $type." ".$name." ".$len." ".$flags."\n";
    }
    mysql_free_result($result);
    mysql_close();
?> 