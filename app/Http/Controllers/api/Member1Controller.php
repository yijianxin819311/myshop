<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Model\Member;
use DB;
class Member1Controller extends Controller
{
    public function add(Request $request)
    {
        $name=$request->input('name');
        $tel=$request->input('tel');
       // var_dump($tel);
        if(empty($name) ||empty($tel)){
            return json_encode(['code'=>202,'msg'=>'字段不能为空']);
        }
        $res=Member::create([
            'name'=>$name,
            'tel'=>$tel
        ]);
        if($res){
            return json_encode(['code'=>200,'msg'=>'添加成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'添加失败']);
        }
    }
    public function shows()
    {

        $data=Member::get();
        //dd($data);
        //return $data;
        return json_encode(['code'=>200,'data'=>$data]);
    }
    public function delete(Request $request)
    {
        $member_id=$request->input('member_id');
        //dd($member_id);
        $res=Member::where(['member_id'=>$member_id])->delete();
        //dd($res);
        if($res){
            return json_encode(['code'=>200,'msg'=>'删除成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'删除失败']);
        }
    }
    public function find(Request $request)
    {
        $member_id=$request->input('member_id');
        if(empty($member_id)){
            return json_encode(['code'=>203,'msg'=>'id不能为空']);
        }
        $data=Member::where(['member_id'=>$member_id])->first();

        return json_encode(['code'=>200,'data'=>$data]);
    }
    public function save(Request $request)
    {
        $member_id=$request->input('member_id');
        $name=$request->input('name');
        $tel=$request->input('tel');
        // var_dump($tel);
        $res=Member::where(['member_id'=>$member_id])->update([
            'name'=>$name,
            'tel'=>$tel
        ]);
        if($res){
            return json_encode(['code'=>200,'msg'=>'修改成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'修改失败']);
        }
    }
    public function loginPages()
    {
        //echo 11;die;
        $page = isset($_POST['page'])?$_POST['page'] : 1;//当前页
        $sou = isset($_POST['sou'])?$_POST['sou'] : '';
        //echo $page;die;
        $size =2;//每页3条
        $res = DB::select("select * from member where name like '%$sou%'");
        //var_dump($res);die;
        $count = count($res);//一共条数
        //echo $count;die;
        $bigpage = ceil($count/$size);//最大页数
        $foll = ($page-1)*$size;//偏移量
        $prev = $page - 1 < 1 ? 1 : $page -1;//上一页
        $next = $page + 1 > $bigpage ? $bigpage : $page + 1;//下一页
        $data = DB::select("select * from member where name like '%$sou%' limit $foll,$size");
        //dd($data);
        $data = json_decode(json_encode($data),1);
        //echo json_encode($data);die;
        $arr = "";
        foreach($data as $key => $v){
            $arr .= "
                        <tr>
            <td>".$v['member_id']."</td>
            <td>".$v['name']."</td>
            <td>".$v['tel']."</td>
            <td></td>
        </tr>
            ";

        }
        $but = "
            <button onclick='fun(1)'>首页</button>
            <button onclick='fun($prev)'>上一页</button>
            <button onclick='fun($next)'>下一页</button>
            <button onclick='fun($bigpage)'>尾页</button>
        ";
        $ser['arr'] = $arr;
        //dd($arr);
        $ser['but'] = $but;
        //dd($but);
        echo json_encode($ser);
    }
}
