<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Model\Member;
use DB;
class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *展示页面
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name=$request->input('name');
        $where=[];
        $where1=[];
        if(!empty($name)){
            $where[]=['name','like',"%$name%"];
        }
        if(!empty($name)){
            $where1[]=['tel','like',"%$name%"];
        }

        $data=Member::where($where)->orwhere($where1)->paginate(2)->toArray();
       // var_dump($data);die;
        if(!empty($name)){
            foreach ($data['data'] as  $key=> $value){
                $data['data'][$key]['name']=str_replace($name,"<b style='color:red'>".$name."</b>",$value['name']);

            }
        }

        return json_encode(['code'=>200,'data'=>$data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *添加
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

    /**
     * Display the specified resource.
     *修改查询页面
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($member_id)
    {
        //$member_id=$request->input('member_id');
        if(empty($member_id)){
            return json_encode(['code'=>203,'msg'=>'id不能为空']);
        }
        $data=Member::where(['member_id'=>$member_id])->first();

        return json_encode(['code'=>200,'data'=>$data]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *修改执行
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$member_id)
    {
        //$member_id=$request->input('member_id');
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

    /**
     * Remove the specified resource from storage.
     *删除
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($member_id)
    {
        //$member_id=$request->input('member_id');
        //dd($member_id);
        $res=Member::where(['member_id'=>$member_id])->delete();
        //dd($res);
        if($res){
            return json_encode(['code'=>200,'msg'=>'删除成功']);
        }else{
            return json_encode(['code'=>201,'msg'=>'删除失败']);
        }
    }

    public function loginPages()
    {
        echo "1123";
    }
}
