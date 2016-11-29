<?php
/**
 * Created by sunny.
 * User: sunny
 * For Darling
 * Date: 2016/11/24
 * Time: 16:14
 */
namespace sunny;

class Router{
    //请求类
    protected $request;
    //整个路由路径
    public static $module;
    public static $controller;
    public static $action;
    public function __construct()
    {
        $this->request=new Request();
        self::$module=$this->request->input($_GET,'g');
        self::$controller=$this->request->input($_GET,'c');
        self::$action=$this->request->input($_GET,'a');
        if(empty(self::$module)){
            self::$module=Config::get('default_module');
        }
        if(empty(self::$controller)){
            self::$controller='index';
        }
        if(empty(self::$action)){
            self::$action='index';
        }
    }

    public function dispatch()
    {
        //引入新增的文件
        $classname='\\app\\'.self::$module.'\\controller\\'.self::$controller;
        self::invokeMethod(array($classname,self::$action));
    }

    /**
     * 调用反射执行类的方法 支持参数绑定
     * @access public
     * @param string|array $method 方法
     * @param array        $vars   变量
     * @return mixed
     */
    public static function invokeMethod($method,$args=[])
    {
        if (is_array($method)) {
            $class   = is_object($method[0]) ? $method[0] : new $method[0]();
            $reflect = new \ReflectionMethod($class, $method[1]);
        } else {
            // 静态方法
            $reflect = new \ReflectionMethod($method);
        }
        return $reflect->invokeArgs(isset($class) ? $class : null, $args);
    }

}