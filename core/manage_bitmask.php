<?php

function check_right($intToCheck, $right)
{
    if ($intToCheck & $right) {
        return true;
    } else {
        return false;
    }
}

function set_right($intToSet = 0, $right)
{
    return $intToSet | (int) $right;
}
