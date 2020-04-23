<?php

namespace App\Http\Controllers\admin;

use App\post;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ChartDataController extends Controller
{
      

      function index(){
          return view('homeAdmin.dachboard');
      } 

                

      function getAllMonth(){         //hna kanreturni tableau dial les mois li3ndi f table ela chakl SMia d chhora wla arqam d chhora
          $posts_dates = post::orderby('date','ASC')->pluck('date');
          $posts_dates= json_decode( $posts_dates);
          $month_array = [];
           if(!empty($posts_dates)){
                 foreach( $posts_dates as $unformatted_date){
                     $date = new DateTime( $unformatted_date);
                     $month_no = $date->format('m');  //les mois b arqam
                     $month_name = $date->format('M');  //les mois b smiat
                     $month_array[$month_no]= $month_name;                   
                 }
           }
         return $month_array;
      }
       
       
      function getMonthlyPostCount($month){    //hna kanjiw kanreturni l3adad dial dial les postes dial dak chher li f parametre
        $monthly_post_count = Post::whereMonth('date',$month)->get()->count();
        return $monthly_post_count;
      }


    
   function getMonthlyPostData(){  //enfin kanjiw kan3yto lihom kamlin b kol chher w ch7al fih mn postes
       
       $monthly_post_count_array=array();
       $month_array = $this->getAllMonth();
       $month_name_array = array();
        if(!empty($month_array)){
            foreach($month_array as $month_no => $month_name){
                $monthly_post_count=$this->getMonthlyPostCount($month_no);
                array_push($monthly_post_count_array, $monthly_post_count);
                array_push( $month_name_array, $month_name );
            }
        }
        


        $month_array= $this->getAllMonth();
        //$max_no= max($monthly_post_count_array);
        //$max= round( ($max_no+ 10/2 ) / 10) * 10;
        $monthly_post_data_array = array(
        'months' => $month_name_array,
         'post_count_data' => $monthly_post_count_array,
         //'max'=> $max,
        );

        return $monthly_post_data_array;
   }














}