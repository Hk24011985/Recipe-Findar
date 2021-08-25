<?php

require 'commonFunctions.php';
  
class RecipeTest extends \PHPUnit\Framework\TestCase {
  
  public function testThatStringsMatch(){  
      $string= 'hello';
      $string2 = 'hello';      
      $this->assertSame($string,$string2);  
  } 
  
  public function testEmptyFilesName(){
      
      $firstfile = 'test.csv';
      $secondfile = 'test.json';
      
      $res  = checkEmptyFiles($firstfile,$secondfile);      
      $this->assertEquals($res,1);
      
      //test for false
      $thirdfile = '';
      $res  = checkEmptyFiles($firstfile,$thirdfile);      
      $this->assertEquals($res,0);
  
  }
  
  public function testFilesExtentions(){
    
    $extone = 'test.pdf'; //csv to pdf
    $exttwo = 'test.json';    
    $res  = CheckFilesExtentions($extone,$exttwo); 
     //fwrite(STDERR, print_r($res, TRUE));
    $this->assertSame($res[0],'List items can take only csv format' );
    
    $extone2 = 'test.csv';
    $exttwo2 = 'test.pdf';   //json to pdf
    
    $res2  = CheckFilesExtentions($extone2,$exttwo2);     
    $this->assertSame($res2[0],'Ingradiants items can take only Json format' );
  
  }
  
  public function testrecipesOrderArrayGet(){
    
      $recipiesFile = "./uploads/recipes.json";
      $res  =  recipesOrderArrayGet($recipiesFile);
      $this->assertIsArray($res);
      
    //  $recipiesFile1 = "./uploads/recipess.json";
    //  $res1  =  recipesOrderArrayGet($recipiesFile1);
      //$this->assertIsArray($res1);
  
  }
  
  
  
   
}


?>