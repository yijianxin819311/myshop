<?php

namespace  App\Http\Tools;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use DB;
class Wechat{
    public  $request;
    public  $client;
    public  $app;
    public function __construct(Request $request,Client $client)
    {
        $this->request = $request;
        $this->client = $client;
        $this->app = $app = app('wechat.official_account');
    }
    //通过openid 获得用户详细信息
    public function wechat_user_info($openid){
       
        $access_token = $this->get_access_token();
        $wechat_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
        $user_info = json_decode($wechat_user,1);
        return $user_info;
    }
   // public function wechat_user_info(){
   //      $openid = DB::table('wechat_openid')->value('openid');
   //      $access_token = $this->get_access_token();
   //      $wechat_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
   //      $user_info = json_decode($wechat_user,1);
   //      //dd($user_info);
   //      return $user_info;
   //  }
     /**
     * 根据标签id获取标签粉丝
     */
    public function tag_user($tag_id)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token='.$this->get_access_token();
        $data = [
            'tagid' => $tag_id,
            'next_openid' => ''
        ];
        $re = $this->post($url,json_encode($data));
        return json_decode($re,1);
    }
    /**
     * 上传微信素材资源
     */
    public function upload_source($up_type,$type,$title='',$desc=''){
    	//dd($type);
        $file = $this->request->file($type);
        $file_ext = $file->getClientOriginalExtension(); //获取文件扩展名
        //重命名
        $new_file_name = time().rand(1000,9999). '.'.$file_ext;
        //文件保存路径
        //保存文件
        $save_file_path = $file->storeAs('weixin/video',$new_file_name);
        // dd($save_file_path); 
        //返回保存成功之后的文件路径
        $path = './storage/'.$save_file_path;
        // dd($path);
        if($up_type  == 1){
            $url='https://api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $this->get_access_token().'&type='.$type;
        }elseif($up_type == 2){ 
            $url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->get_access_token().'&type='.$type;
        }
        $multipart = [
            [
                'name' => 'media',
                'contents' => fopen(realpath($path), 'r')
            ],
        ];
        
        if($type == 'video' && $up_type == 2){
            $multipart[] = [
                    'name'     => 'description',
                    'contents' => json_encode(['title'=>$title,'introduction'=>$desc])
            ];
        }
        $response = $this->client->request('POST',$url,[
            'multipart' => $multipart
        ]);
        //返回信息
        $body = $response->getBody();
        //dd($body);
        //echo $body;die;//打印media_id
        unlink($path);//unlink删除文件
    
        return $body;
    }
    /**
     * 根据openid发送模板消息
     * @param $openid
     * @return bool|string
     */
    public function push_template($openid)
    {
        //$openid = 'otAUQ1XOd-dph7qQ_fDyDJqkUj90';
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->get_access_token();
        $data = [
            'touser'=>$openid,
            'template_id'=>'erO8fzlj0BXY_71coD9DqnJxNhPj6Qx2zOO1YBp-gmI',
            'url'=>'http://www.baidu.com',
            'data' => [
                'first' => [
                    'value' => '商品名称',
                    'color' => ''
                ],
                'keyword1' => [
                    'value' => '低价',
                    'color' => ''
                ],
                'keyword2' => [
                    'value' => '是低价',
                    'color' => ''
                ],
                'remark' => [
                    'value' => '备注',
                    'color' => ''
                ]
            ]
        ];
        $re = $this->post($url,json_encode($data));
        return $re;
    }
    /**
     * post请求
     * @param $url
     * @param $data
     * @return bool|string
     */
    public function post($url, $data = []){
        //初使化init方法
        $ch = curl_init();
        //指定URL
        curl_setopt($ch, CURLOPT_URL, $url);
        //设定请求后返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //声明使用POST方式来进行发送
        curl_setopt($ch, CURLOPT_POST, 1);
        //发送什么数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //忽略header头信息
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        //发送请求
        $output = curl_exec($ch);
        //关闭curl
        curl_close($ch);
        //返回数据
        return $output;
    }
    /**
     * get请求
     * @param $url
     */
    public function get($url)
    {
        //1初始化
        $ch = curl_init();
        //2设置
        curl_setopt($ch,CURLOPT_URL,$url); //访问地址
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1); //返回格式
        //请求网址是https
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); // 对认证证书来源的检查
        //3执行
        $content = curl_exec($ch);
        //4关闭
        curl_close($ch);
        return $content;
    }
    /**
     * 获取access_token
     */
    public function get_access_token(){
        //获取access_token
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $access_token_key = 'wechat_access_token';
        if($redis->exists($access_token_key)){
            //去缓存拿
            $access_token = $redis->get($access_token_key);
        }else{
            //去微信接口拿
            $access_re = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET'));
            $access_result = json_decode($access_re,1);
            $access_token = $access_result['access_token'];
            $expire_time = $access_result['expires_in'];
            //加入缓存
            $redis->set($access_token_key,$access_token,$expire_time);
        }
        return $access_token;
    }
    public function jiekou()
    {
        $info = '{"resultcode":"200","reason":"查询成功!","result":[{"city":"北京","b90":"-","b93":"6.50","b97":"6.97","b0":"6.21","92h":"6.54","95h":"6.97","98h":"7.95","0h":"6.21"},{"city":"上海","b90":"-","b93":"6.51","b97":"6.93","b0":"6.15","92h":"6.51","95h":"6.93","98h":"7.63","0h":"6.15"},{"city":"江苏","b90":"-","b93":"6.52","b97":"6.94","b0":"6.14","92h":"6.52","95h":"6.94","98h":"7.82","0h":"6.14"},{"city":"天津","b90":"-","b93":"6.53","b97":"6.90","b0":"6.17","92h":"6.53","95h":"6.90","98h":"7.82","0h":"6.17"},{"city":"重庆","b90":"-","b93":"6.62","b97":"6.99","b0":"6.25","92h":"6.62","95h":"6.99","98h":"7.88","0h":"6.25"},{"city":"江西","b90":"-","b93":"6.51","b97":"6.99","b0":"6.21","92h":"6.51","95h":"6.99","98h":"7.99","0h":"6.21"},{"city":"辽宁","b90":"-","b93":"6.52","b97":"6.95","b0":"6.09","92h":"6.52","95h":"6.95","98h":"7.57","0h":"6.09"},{"city":"安徽","b90":"-","b93":"6.51","b97":"6.99","b0":"6.20","92h":"6.51","95h":"6.99","98h":"7.82","0h":"6.20"},{"city":"内蒙古","b90":"-","b93":"6.49","b97":"6.92","b0":"6.06","92h":"6.49","95h":"6.92","98h":"7.60","0h":"6.06"},{"city":"福建","b90":"-","b93":"6.52","b97":"6.96","b0":"6.17","92h":"6.52","95h":"6.96","98h":"7.62","0h":"6.17"},{"city":"宁夏","b90":"-","b93":"6.46","b97":"6.82","b0":"6.07","92h":"6.46","95h":"6.82","98h":"8.01","0h":"6.07"},{"city":"甘肃","b90":"-","b93":"6.44","b97":"6.88","b0":"6.08","92h":"6.44","95h":"6.88","98h":"7.32","0h":"6.08"},{"city":"青海","b90":"-","b93":"6.50","b97":"6.97","b0":"6.11","92h":"6.50","95h":"6.97","98h":"0","0h":"6.11"},{"city":"广东","b90":"-","b93":"6.57","b97":"7.11","b0":"6.18","92h":"6.57","95h":"7.11","98h":"7.99","0h":"6.18"},{"city":"山东","b90":"-","b93":"6.52","b97":"7.00","b0":"6.16","92h":"6.52","95h":"7.00","98h":"7.72","0h":"6.16"},{"city":"广西","b90":"-","b93":"6.61","b97":"7.14","b0":"6.23","92h":"6.61","95h":"7.14","98h":"7.92","0h":"6.23"},{"city":"山西","b90":"-","b93":"6.50","b97":"7.02","b0":"6.23","92h":"6.50","95h":"7.02","98h":"7.72","0h":"6.23"},{"city":"贵州","b90":"-","b93":"6.67","b97":"7.05","b0":"6.28","92h":"6.67","95h":"7.05","98h":"7.95","0h":"6.28"},{"city":"陕西","b90":"-","b93":"6.44","b97":"6.80","b0":"6.08","92h":"6.44","95h":"6.80","98h":"7.60","0h":"6.08"},{"city":"海南","b90":"-","b93":"7.66","b97":"8.13","b0":"6.26","92h":"7.66","95h":"8.13","98h":"9.18","0h":"6.26"},{"city":"四川","b90":"-","b93":"6.58","b97":"7.09","b0":"6.27","92h":"6.58","95h":"7.09","98h":"7.72","0h":"6.27"},{"city":"河北","b90":"-","b93":"6.53","b97":"6.90","b0":"6.17","92h":"6.53","95h":"6.90","98h":"7.73","0h":"6.17"},{"city":"西藏","b90":"-","b93":"7.43","b97":"7.86","b0":"6.73","92h":"7.43","95h":"7.86","98h":"0","0h":"6.73"},{"city":"河南","b90":"-","b93":"6.55","b97":"6.99","b0":"6.16","92h":"6.55","95h":"6.99","98h":"7.64","0h":"6.16"},{"city":"新疆","b90":"-","b93":"6.42","b97":"6.92","b0":"6.06","92h":"6.42","95h":"6.92","98h":"7.73","0h":"6.06"},{"city":"黑龙江","b90":"-","b93":"6.48","b97":"6.87","b0":"5.95","92h":"6.48","95h":"6.87","98h":"7.84","0h":"5.95"},{"city":"吉林","b90":"-","b93":"6.51","b97":"7.02","b0":"6.10","92h":"6.51","95h":"7.02","98h":"7.65","0h":"6.10"},{"city":"云南","b90":"-","b93":"6.69","b97":"7.18","b0":"6.25","92h":"6.69","95h":"7.18","98h":"7.86","0h":"6.25"},{"city":"湖北","b90":"-","b93":"6.55","b97":"7.01","b0":"6.16","92h":"6.55","95h":"7.01","98h":"7.58","0h":"6.16"},{"city":"浙江","b90":"-","b93":"6.52","b97":"6.94","b0":"6.16","92h":"6.52","95h":"6.94","98h":"7.60","0h":"6.16"},{"city":"湖南","b90":"-","b93":"6.50","b97":"6.90","b0":"6.23","92h":"6.50","95h":"6.90","98h":"7.71","0h":"6.23"}],"error_code":0}';
        echo $info;
    }
}
