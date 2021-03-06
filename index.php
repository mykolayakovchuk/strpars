<?php

//Строка после разбиения на токены
$tokens=["a","<","b",":","с",">","d","e"];

//функция анализа верхушки стека
function stackAnalize($array, $stackPointer){
    switch ($array[$stackPointer]) {
        case "<":
        case ":":
            //echo ":< detected<br>";
            break;
        case ">":
            //echo "> detected<br>";
            break;
        default:
            if ($array[$stackPointer-1]!="<" &&
                $array[$stackPointer-1]!=":" &&
                $array[$stackPointer-1]!=">" &&
                $array[$stackPointer-1]!="stackBottom"){
                    //echo ("concat(".$array[$stackPointer]."+".($array[$stackPointer-1]).")<br>");
                    break;
            }else{
            break;}
    }
}

//наполнение и работа со стеком
$stack=["stackBottom"];
$stackPointer=0;//указатель установлен на дно стека
foreach ($tokens as $key=>$value){
    array_push($stack, $value);
    $stackPointer++;
    stackAnalize($stack, $stackPointer);
}
//sdfsdfsdfgsdfg
print_r($tokens);

echo ("<br>-------------------------------<br>");
print_r($stack);
echo ("<br>stackPointer=".$stackPointer);
?>