<?php

//Строка после разбиения на токены
$tokens=["a","<","b",":","с",">","d","e"];
//$tokens=["d","e", ["f", "g"], ["h", "i"], "j"];
echo  (count($tokens));
//функция анализа верхушки стека
function stackProduce($array){
    $stackPointer=count($array)-1;
    switch ($array[$stackPointer]) {
        case "<":
        case ":":
            break;
        case ">":
            //функция произведения выражения < : >
            orProd($array);
            break;
        default:
            if ($array[$stackPointer-1]!="<" &&
                $array[$stackPointer-1]!=":" &&
                $array[$stackPointer-1]!=">" &&
                $array[$stackPointer-1]!="stackBottom"
                ){
                    //функция объединения значений
                    $array[$stackPointer-1]=concat ($array[$stackPointer-1] , $array[$stackPointer]);
                    array_pop($array);
            }else{
            break;}
    }
return $array;
}

//функция объединения значений
function concat ($element1, $element2){
    switch (is_array($element1)){
        case true:{
            switch (is_array($element2)){
                case true://оба элемента массивы (11)
                    $resultArray=[];
                    foreach($element1 as $value1){
                        foreach($element2 as $value2){
                            array_push($resultArray, $value1." ".$value2);
                        }
                    }
                    return $resultArray;
                    break;

                case false://первый массив, второй не массив(10)
                    $resultArray=[];
                    foreach($element1 as $value){
                        array_push($resultArray, $value." ".$element2);
                    }
                    return $resultArray;
                    break;
            }
        }
        case false:{
            switch (is_array($element2)){
                case true:// (01)
                    $resultArray=[];
                    foreach($element2 as $value){
                        array_push($resultArray, $element1." ".$value);
                    }
                    return $resultArray;
                    break;

                case false: // (00)
                    return $element1." ".$element2;
                    break;
            }
        }
    }
}

//функция произведения выражения < : >
function orProd($array){
    array_pop($array);
    while ($array[count($array)-2]!="<") {
        $stackPointer=count($array)-1;
        switch ($array[$stackPointer-1]){
            case ":":
                             
                echo ("<br>stack[".$stackPointer."] ------ "."ELEMENT:".$array[$stackPointer]."---");
                array_pop($array);
                print_r($array);
                break;
            default:
                echo ("<br>stack[".$stackPointer."] ------ "."ELEMENT:".$array[$stackPointer]."---");
                $array[$stackPointer-1]=concat ($array[$stackPointer-1] , $array[$stackPointer]);
                array_pop($array);
                print_r($array);
                break;
        }
    }
    echo ("<br>");
    print_r($array);
}

//наполнение и работа со стеком
$stack=["stackBottom"];
$stackPointer=0;//указатель установлен на дно стека
foreach ($tokens as $key=>$value){
    //echo ("<br>---0---------------------------<br>");
    //print_r($stack);
    array_push($stack, $value);
    $stack=stackProduce($stack);
    //echo ("<br>----1--------------------------<br>");
    //print_r($stack);
    
}
//sdfsdfsdfgsdfg
echo ("<br>-----tokens:--------------------<br>");
print_r($tokens);

echo ("<br>-----stack:--------------------<br>");
print_r($stack);

?>