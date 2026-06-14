<?php
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "OPCache reset successfully!";
} else {
    echo "OPCache not enabled or not supported in this PHP environment.";
}
