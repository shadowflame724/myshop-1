<?php

function foo($x, $n)
{
    if ($n == 0) {
        return 1;
    }

    $a = foo($x, $n - 1);
    $result = $x * $a;

    echo $x . " * " . $a . " = " . $result . " (".$n.")<br />";

    return $result;
}

$res = foo(2, 6);
echo $res;