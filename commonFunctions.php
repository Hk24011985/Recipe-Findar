<?php

//Checking for empty input data
if (!function_exists("checkEmptyFiles"))
{

    function checkEmptyFiles($itemFile = '', $gradiant_file = '')
    {
        if (empty($itemFile) || empty($gradiant_file))
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}

//Checking for Files extentions
if (!function_exists("CheckFilesExtentions"))
{
    function CheckFilesExtentions($list_items, $gradiants_items)
    {

        //get file extentions to validate
        $ext_list_items = pathinfo($list_items, PATHINFO_EXTENSION);
        $ext_gradiants_items = pathinfo($gradiants_items, PATHINFO_EXTENSION);

        $errors = array();
        if (!empty($ext_list_items) && strtolower($ext_list_items != 'csv'))
        {
            $errors[] = 'List items can take only csv format';
        }
        //Checking for files extentions here
        if (!empty($ext_gradiants_items) && strtolower($ext_gradiants_items != 'json'))
        {
            $errors[] = 'Ingradiants items can take only Json format';
        }
        return $errors;
    }
}

//Uploading file to destination
if (!function_exists("uploadFilesToDestination"))
{
    function uploadFilesToDestination($list_items = '', $gradiants_items = '', $tempItem = '', $tempGradiant = '', $target_dir = '')
    {

        //$target_dir = "./uploads/";
        $target_file_items = $target_dir . basename($list_items);
        $target_file_gradiants = $target_dir . basename($gradiants_items);

        //Remove file first if already exists
        if (file_exists($target_dir . '/' . basename($list_items)))
        {
            unlink($target_dir . '/' . basename($list_items));
        }
        if (file_exists($target_dir . '/' . basename($gradiants_items)))
        {
            unlink($target_dir . '/' . basename($gradiants_items));
        }

        if (move_uploaded_file($tempItem, $target_file_items) && move_uploaded_file($tempGradiant, $target_file_gradiants))
        {
            return true;
        }
        else
        {
            return false;
        }

    }
}

if (!function_exists("FridgeItemsArrayGet"))
{
    function FridgeItemsArrayGet($target_dir, $list_items)
    {

        $target_file_items = $target_dir . basename($list_items);
        $csv_target = array_map('str_getcsv', file($target_file_items));
        $final_list_array = array();

        if (!empty($csv_target))
        {
            foreach ($csv_target as $key => $values)
            {
                if ($key == 0)
                { //Removing heading section
                    continue;
                }
                foreach ($values as $valuesfinal)
                {
                    if (!empty($valuesfinal))
                    {
                        $final_list_array[reset($values) ][] = $valuesfinal; //first array get

                    }
                }
            }
        }
        return $final_list_array;
    }
}

if (!function_exists("recipesOrderArrayGet"))
{
    function recipesOrderArrayGet($filePath = '')
    {

        $str = file_get_contents($filePath);
        $recipies_json = json_decode($str, true);

        return $recipies_json;

    }
}

if (!function_exists("CheckOrdesList"))
{
    function CheckOrdesList($data = '')
    {
        if (!empty($data))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

if (!function_exists("recipeFunctionalitySteps"))
{
    function recipeFunctionalitySteps($recipies_json = '', $final_list_array = '')
    {

        foreach ($recipies_json as $k => $values)
        {

            $name_recip = !empty($values['name']) ? $values['name'] : '';
            $ingradiants_array = !empty($values['ingredients']) ? $values['ingredients'] : array();

            //Check Ingradiants and item name
            $IngradiantsStatus = IngradiantsExistOrNot($ingradiants_array, $name_recip);

            if ($IngradiantsStatus['status'] == 'true')
            {

                foreach ($ingradiants_array as $key=>$values)
                {

                    $fridgeItemStatus = CheckItemExistInFridge($final_list_array, $values['item']);
                    //Push used by date to array
                    $recipies_json[$k]['ingredients'][$key]['used-By'] = $final_list_array[$values['item']][3];

                    if ($fridgeItemStatus['status'] == 'true')
                    {   //checking for dates [3]
                        $pastDateStatus = pastDateItemsRemove($final_list_array, $values['item'], $name_recip);
                        //Checking ingradiants used by date
                        if ($pastDateStatus['status'] == 'false')
                        {
                            echo !empty($pastDateStatus['message']) ? $pastDateStatus['message'] : '';
                            //Remove order from main array
                            unset($recipies_json[$k]);
                        }

                    }
                    else
                    {
                        //When no gradiant found in the kitchen
                        echo !empty($fridgeItemStatus['message']) ? $fridgeItemStatus['message'] : '';
                    }

                }
            }
            else
            {
                echo !empty($IngradiantsStatus['message']) ? $IngradiantsStatus['message'] : '';
            }
        }

        return $recipies_json;

    }
}

//Ingradiants exist or not
if (!function_exists("IngradiantsExistOrNot"))
{
    function IngradiantsExistOrNot($ingradiants_array = array() , $name_recipe = '')
    {
        if (!empty($ingradiants_array) && !empty($name_recipe))
        {
            return array(
                'status' => 'true',
                'message' => "success."
            );
        }
        else
        {
            return array(
                'status' => 'false',
                'message' => "Ingradiants are missing."
            );
        }

    }
}

//Item exist or not
if (!function_exists("CheckItemExistInFridge"))
{
    function CheckItemExistInFridge($item_fridge_array = array() , $item_to_search = '')
    {
        if (!empty($item_fridge_array[$item_to_search]))
        {
            return array(
                'status' => 'true',
                'message' => "success."
            );
        }
        else
        {
            return array(
                'status' => 'false',
                'message' => "Order takeout." . $item_to_search
            );
        }

    }
}

//Past date data handle
if (!function_exists("pastDateItemsRemove"))
{
    function pastDateItemsRemove($item_fridge_array = array() , $item_to_search = '', $name_recip = '')
    {

        $date = str_replace('/', '-', $item_fridge_array[$item_to_search][3]);
        $date_format_change = date('Y-m-d', strtotime($date));
        //Checking ingradiants used by date
        if ($date_format_change < date('Y-m-d'))
        {
            return array(
                'status' => 'false',
                'message' => '<div class="container">
        <div class="row">
          <div class="col-sm-4 py-2">
                          <div class="card text-white bg-danger">
                              <div class="card-body">
                                  <h4 class="card-title">Order details</h4>
                                  <p class="card-text">Order '.$name_recip.' not prepare because recipe "'.$item_to_search.'" is past dated</p>
                                  <span class="btn btn-outline-light">Failed</span>
                              </div>
                          </div>
                      </div>
            </div>
          </div>'
            );
        }
        else
        {
            return array(
                'status' => 'true',
                'message' => "success."
            );
        }

    }
}

if (!function_exists("pr"))
{
    function pr($data){
      echo "<pre>";
          print_r($data);
       echo "</pre>";
    }
}

if (!function_exists("array_index_compare"))
{
  function array_index_compare($index1,$index2)
    {
        for ($i=0; $i < count($index1); $i++) {
            for ($j=0; $j < count($index2); $j++) {
              if($i == 0){ //first date was smallest so matchiong here
                if($index1[$i]["used-By"] > $index2[$j]['used-By']){
                    return true;
                }
              }
            }
        }
    }
}
?>
