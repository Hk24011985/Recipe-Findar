<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Recipies Order</title>
  </head>
  <body>

<?php
//checking all the errors here
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
// Submit the form here and checking for the same
if (!empty($_POST))
{

    require_once ('./commonFunctions.php');
    $list_items = !empty($_FILES['list_items']['name']) ? $_FILES['list_items']['name'] : '';
    $gradiants_items = !empty($_FILES['gradiants_items']['name']) ? $_FILES['gradiants_items']['name'] : '';

    //checking for empty file value
    $files_results = checkEmptyFiles($list_items, $gradiants_items);
    //If files names not empty
    if ($files_results)
    {
        $errors = CheckFilesExtentions($list_items, $gradiants_items);
    }
    if (!empty($errors))
    {
        foreach ($errors as $values)
        {
            echo '<p class="text-danger">' . $values . '</p><br>';
        }

    }
    else
    {
        // fetching data to uploaded files
        $tempItem = $_FILES["list_items"]["tmp_name"];
        $tempGradiant = $_FILES["gradiants_items"]["tmp_name"];
        $target_dir = "./uploads/";
        //Uploading file to destination
        $uploadFileResponse = uploadFilesToDestination($list_items, $gradiants_items, $tempItem, $tempGradiant, $target_dir);
        //If both the files uploaded to the destination
        if ($uploadFileResponse)
        {

            $final_list_array = FridgeItemsArrayGet($target_dir, $list_items);
            // Second file data getting here
            $recipiesFile = $target_dir . basename($gradiants_items);
            $recipies_json = recipesOrderArrayGet($recipiesFile);

            //Checking for json data(Empty or Not)
            $recipies_json_status = CheckOrdesList($recipies_json);
            if ($recipies_json_status)
            {
                $array = recipeFunctionalitySteps($recipies_json, $final_list_array);
                //pr($array);
                // Ordering ingredients date wise
                $array_final = array();
                if(!empty($array)){
                      foreach($array as $k1=>$part){
                          $array     = $part['ingredients'];
                          for( $j=0;  $j < count($array)-1; $j++)
                            {
                                if($array[$j]["used-By"] > $array[$j+1]["used-By"])
                                {
                                           $temp = $array[$j];
                                           $array[$j] = $array[$j+1];
                                           $array[$j+1]=$temp;

                                           $array_final[$k1]['name'] = $part['name'];
                                           $array_final[$k1]['ingredients'] = $array;
                                }else{
                                          //$array_final[$k1]['name'] = $part['name'];
                                          $array_final[$k1]['name'] = $part['name'];
                                          $array_final[$k1]['ingredients'] = $array;
                                }
                            }
                      }
                     unset($array);
                     $array = $array_final;
                     //pr($array);

                      for( $j=0;  $j < count($array)-1; $j++)
                        {
                            if (array_index_compare($array[$j]["ingredients"],$array[$j+1]["ingredients"]))
                            {
                                       $temp = $array[$j];
                                       //swap the two between each other
                                       $array[$j] = $array[$j+1];
                                       $array[$j+1]=$temp;
                            }
                        }
                }
        }else{

              echo '<div class="container">
                    	<div class="row">
                    		<div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                    			<div class="card-header">Oops!!</div>
                    			<div class="card-body">
                    				<h5 class="card-title">Order takeout.</h5> </div>
                    		</div>
                    	</div>
                    </div>';
            }

        }
        else
        {
            echo "There is some issue to upload the file please check";
        }
    ?>

    <div class="container">
        <div class="row">
          <?php if(!empty($array)){
                  $count = 1;
                  foreach($array as $values){
          ?>
            <div class="col-sm-4 py-2">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h4 class="card-title">Order details</h4>
                        <p class="card-text">Your order '<?php echo $values['name'] ?>'[ Order No - <?php echo $count; ?> ]  is being prepared.</p>
                        <span class="btn btn-outline-light">Success</span>
                    </div>
                </div>
            </div>
          <?php   $count++; } } ?>
        </div>
    </div>

      <?php
        die;
    }
}
?>
  <div class="container-fluid mt-4">
      <div class="row">
        <section class="col md-7">
          <div class="card mb-3">
            <div class="card-body">
              <h3 class="col-lg-4 offset-lg-2 text-primary" >Recipe Management</h3>
                  <form  method="post" action="" name="recipe_form" enctype="multipart/form-data" class="col-lg-6 offset-lg-3 mt-4">
                    <div class="form-group">
                      <label for="exampleFormControlFile1">List Items *(<span class="text-danger">.Csv file</span>)</label>
                      <input type="file" required class="form-control-file" id="items" name="list_items" accept=".csv">
                      <span id="items_message" class="text-danger"> Only csv file accepted </span>
                    </div>
                    <div class="form-group">
                      <label for="exampleFormControlFile1">Ingradiants Items *(<span class="text-danger">.Json file</span>)</label>
                      <input type="file" required class="form-control-file" id="gradiants" name="gradiants_items" accept=".json" >
                      <span id="gradiant_message" class="text-danger"> Only Json file accepted </span>
                    </div>

                <button type="submit" class="btn btn-primary mb-5" name="submit" id="submit">Submit</button>
                </form>
              </div>
            </div>
        </section>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
 <!--Js common including here--->
   <script src="./common.js"></script>
  </body>
</html>
