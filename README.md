# Recipe-Finder
Recipe finder 
#start date on 
24-08-2021

##ToDo 
Sorting Orders by Date :

$j=0;
$flag = true;
$temp=0;

function array_index_compare($index1,$index2)
{

    for ($i=0; $i < count($index1); $i++) {
        for ($j=0; $j < count($index2); $j++) {
            if($index1[$i]["used-By"] > $index2[$j]['used-By']){
              return true;
            }

        }
    }
}

 while ( $flag )
 {
    $flag = false;
    for( $j=0;  $j < count($array)-1; $j++)
    {
    
        if (array_index_compare($array[$j]["ingredients"],$array[$j+1]["ingredients"]))
        {
                   $temp = $array[$j];
                   //swap the two between each other
                   $array[$j] = $array[$j+1];
                   $array[$j+1]=$temp;
                   $flag = true; //show that a swap occurred
        
        }
    
    }

}
