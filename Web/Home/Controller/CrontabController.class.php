<?php
namespace Home\Controller;
use Think\Controller;
class CrontabController extends Controller {
    
    
    public function push(){
        //$News = M('News');

        echo time();
        echo "<br>";
        echo "ok";
        file_put_contents('E:/a.txt',time()."\n",FILE_APPEND);

    }
    


}