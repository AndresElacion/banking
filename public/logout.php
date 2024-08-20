<?php
    require_once '../includes/auth.php';

    if (logoutUser()) {
        return true;
    }
?>