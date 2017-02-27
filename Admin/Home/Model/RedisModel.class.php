<?php
/**
 * Created by PhpStorm.
 * User: ZengZhiQiang
 * Date: 2016/11/07
 * Time: 14:04
 */
namespace Home\Model;
class RedisModel
{
    private static $_instance = null;
    public $redis = null;

    /** 查询队列第一个值
     * @param $key 键
     * @return bool|string
     */
    public function getFirstUser($key){
        $user_id = 0;
        if(!$key){
            $user_id = 0;
        }else{
            if($this->redis != false){
                $user_list = $this->redis->LRANGE($key,0,-1);
                $user_id = $user_list[0] ? $user_list[0] : 0;
            }
        }
        return $user_id;
    }

    /** 更新队列
     * @param $key 键
     * @return bool|string
     */
    public function updateUserList($key){
        if(!$key){
            return false;
        }else{
            if($this->redis != false){
                $list = $this->getLRANGE($key);
                if(empty($list)){
                    return true;
                }
                $value = $this->redis->LPOP($key);
                return $this->redis->RPUSH($key,$value);
            }
            return false;
        }
    }

    /** 查询队列
     * @param $key 键
     * @return bool|string
     */
    public function getLRANGE($key){
        $user_list = array();
        if(!$key){
            $user_list = array();
        }else{
            if($this->redis != false){
                $user_list = $this->redis->LRANGE($key,0,-1);
            }
        }
        return $user_list;
    }
    /** 把值插入队列
     * @param $key  键
     * @param $value  值
     * @return bool|string
     */
    public function setUserList($key,$value){
        if(!$key || !$value){
            return false;
        }
        if($this->redis != false){
            return $this->redis->RPUSH($key,$value);
        }
        return false;

    }
    /** 把值插入队列
     * @param  $key  键
     * @param  $value  值
     * @return bool|string
     */
    public function delUserList($key,$value,$type = 0){
        if(!$key || !$value){
            return false;
        }
        if($this->redis != false){
            return $this->redis->lRem($key,$value,$type);
        }
        return false;
    }

    /**redis单例模式
     * @return RedisModel|null
     */
    public static function instance(){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self;
            self::$_instance->redis = new \Redis();
            $connect = self::$_instance->redis->connect(C('REDIS.host'), C('REDIS.port'), 2);//redis进行第一次连接
            if($connect == false){
                $connect = self::$_instance->redis->connect(C('REDIS.host'), C('REDIS.port'), 2);//redis进行第二次重新连接
                if($connect == false){
                    self::$_instance->redis = false;
                }
            }else{
                self::$_instance->redis->connect(C('REDIS.host'), C('REDIS.port'), 2);
            }
        }
        return self::$_instance;
    }
}