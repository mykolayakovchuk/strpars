<?php

//Строка после разбиения на токены
$tokens=["a","<","b",":","с",">"];

//наполнение и работа со стеком
$stack=["stackBottom"];
$stackPointer=0;//указатель установлен на дно стека
foreach ($tokens as $key=>$value){
    array_push($stack, $value);
    $stackPointer++;
    
}

print_r($tokens);
echo ("<br>-------------------------------<br>");
print_r($stack);
echo ("<br>stackPointer=".$stackPointer)
?>