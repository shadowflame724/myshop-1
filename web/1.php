<?php

$password = "my_password";

$res = md5($password);

echo $res;























die();

$data = array(
    'A' => array(
        'B' => array( 'X', 'D', 'Y'),
        'C' => array( 'Z' ),
        'G' => array(
            'H' => array( 'J', 'K', 'L'),
            'I' => array( 'M' )
        )
    )
);


function tree($data, $level = 0)
{
    if (!is_array($data))
        return '';

    $res = '';
    foreach ($data as $key => $value)
    {
        // выводим рутовый эллемент (ключ рутового эллемента)
        $res .= '<br/>' . str_repeat("-", $level * 5) . $key;

        // если есть дети то заходим в функцию еще раз
        if(is_array($value)) {
            $res .= tree($value,$level+1);
        }

        // если детей нет, то выводим значение рутового эллемента
        if(is_string($value)) {
            $res .= '<br/>' . str_repeat("-", ($level + 1) * 5) . $value;
        }
    }

    return $res;
}
//echo tree($data, 0);

function foo($item)
{
    var_dump($item);
}

var_dump($data);