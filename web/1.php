<?php
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


function rep($level) {
    return '<br/>' . str_repeat("-", $level * 5);
}

function tree($data, $level = 0)
{
    if (!is_array($data))
        return '';

    $res = '';
    foreach ($data as $key => $value)
    {
        // выводим рутовый эллемент (ключ рутового эллемента)
        $res .= rep($level) . $key;

        // если есть дети то заходим в функцию еще раз
        if(is_array($value)) {
            $res .= tree($value,$level+1);
        }

        // если детей нет, то выводим значение рутового эллемента
        if(is_string($value)) {
            $res .= rep($level + 1) . $value;
        }
    }

    return $res;
}
echo tree($data, 0);