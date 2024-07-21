<?php

if (!function_exists('assets')) {
    function assets($param) {
        // Logika helper Anda
        return asset('assets/'.$param);
    }
}