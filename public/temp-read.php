<?php
if (file_exists('csv_content.txt')) {
    echo file_get_contents('csv_content.txt');
} else {
    echo "csv_content.txt not found";
}
unlink(__FILE__);
