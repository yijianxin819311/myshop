<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class ExamController extends Controller
{
    public function logins(Request $request)
    {
    	$res=$request->all();
    	return view('admin.logins');
    }
    public function logins_do(Request $request)
    {
    	$res=$request->all();
    	unset($res['_token']);
    	$result=DB::table('exam')->insert($res);
    	//dd($result);
    	if($result){
    		$request->session()->put('name',$res['name']);
            $request->session()->put('id',$result['id']);
    		return redirect('admin/adds');
    	}
    }
    public function index(Request $request)
    {
        $res=$request->all();
        $result=DB::table('question_problem')->get();
        //dd($result);
        return view('admin/index',['problem'=>$result]);
    }
    public function add_papers()
    {
        return view('admin.papers');
    }
    public function do_add_papers(Request $request)
    {

        $info = DB::connection('mysql')->table("question_problem")->get()->toArray();
        return view('admin.addPapers',['info'=>$info,'title'=>$request->all()['title']]);
    }
    public function insert_papers(Request $request)
    {
        $req = $request->all();
        $result = DB::connection("mysql")->table('question_test')->insert([
            'title'=>$req['title'],
            'question_list'=>implode(',',$req['problem']),
            'add_time'=>time()
        ]);
        if($result){
            echo "ok";
        }else{
            echo 'false';
        }
    }
    public function test_list(Request $request)
    {
        $info = DB::connection("mysql")->table('question_test')->get()->toArray();
        return view('admin.test_list',['info'=>$info]);
    }
    public function test_detail(Request $request)
    {
        $req = $request->all();
        dd($req);
    }
    public function sadd(Request $request)
    {
        
        return view('admin/sadd');
    }
    public function adds(Request $request)
    {
    	$res=$request->all();
    	return view('admin/adds');
    }
    public function do_sadd(Request $request)
    {
        $req=$request->all();
        // dd($req);
         DB::connection('mysql')->beginTransaction();
        if($req['type']==1){
            //单选
            $result1=DB::table('question_problem')->insertGetId([
                    'type_id'=>$req['type'],
                    'problem'=>$req['single'],
                    'add_time'=>time()
                ]);
            //dd($result1);
            $result2 = DB::table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['single_a'],
                'is_answer'=>($req['single_answer'] == 1)?1:0,
                'add_time'=>time()
            ]);
            $result3= DB::table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['single_b'],
                'is_answer'=>($req['single_answer'] == 2)?1:0,
                'add_time'=>time()
            ]);
            $result4 = DB::table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['single_c'],
                'is_answer'=>($req['single_answer'] == 3)?1:0,
                'add_time'=>time()
            ]);
            $result5 = DB::table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['single_d'],
                'is_answer'=>($req['single_answer'] == 4)?1:0,
                'add_time'=>time()
            ]);
            $result = $result1 && $result2 && $result3 && $result4 && $result5;
            }elseif($req['type'] == 2){
            //多选
            $result1 = DB::table('question_problem')->insertGetId([
                'type_id'=>$req['type'],
                'problem'=>$req['box'],
                'add_time'=>time()
            ]);
            $result2 = DB::table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['box_a'],
                'is_answer'=>in_array(1,$req['box_answer'])?1:0,
                'add_time'=>time()
            ]);
            $result3 = DB::table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['box_b'],
                'is_answer'=>in_array(2,$req['box_answer'])?1:0,
                'add_time'=>time()
            ]);
            $result4 = DB::table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['box_c'],
                'is_answer'=>in_array(3,$req['box_answer'])?1:0,
                'add_time'=>time()
            ]);
            $result5 = DB::table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['box_d'],
                'is_answer'=>in_array(4,$req['box_answer'])?1:0,
                'add_time'=>time()
            ]);
            $result = $result1 && $result2 && $result3 && $result4 && $result5;
        }
            if($result){
             DB::connection('student')->commit();
            echo "成功";
        }else{
             DB::connection('mysql')->rollback();
            echo "失败";
        }
      
    }
    public function do_add(Request $request)
    {
        $req = $request->all();
        echo "<pre>";print_r($req);
        DB::connection('mysql_cart')->beginTransaction();
        $result = true;
        if($req['type'] == 1){
            //单选
            $result1 = DB::connection("mysql_cart")->table('question_problem')->insertGetId([
                'type_id'=>$req['type'],
                'problem'=>$req['single'],
                'add_time'=>time()
            ]);
            $result2 = DB::connection("mysql_cart")->table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['single_a'],
                'is_answer'=>($req['single_answer'] == 1)?1:0,
                'add_time'=>time()
            ]);
            $result3= DB::connection("mysql_cart")->table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['single_b'],
                'is_answer'=>($req['single_answer'] == 2)?1:0,
                'add_time'=>time()
            ]);
            $result4 = DB::connection("mysql_cart")->table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['single_c'],
                'is_answer'=>($req['single_answer'] == 3)?1:0,
                'add_time'=>time()
            ]);
            $result5 = DB::connection("mysql_cart")->table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['single_d'],
                'is_answer'=>($req['single_answer'] == 4)?1:0,
                'add_time'=>time()
            ]);
            $result = $result1 && $result2 && $result3 && $result4 && $result5;
        }elseif($req['type'] == 2){
            //多选
            $result1 = DB::connection("mysql_cart")->table('question_problem')->insertGetId([
                'type_id'=>$req['type'],
                'problem'=>$req['box'],
                'add_time'=>time()
            ]);
            $result2 = DB::connection("mysql_cart")->table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['box_a'],
                'is_answer'=>in_array(1,$req['box_answer'])?1:0,
                'add_time'=>time()
            ]);
            $result3 = DB::connection("mysql_cart")->table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['box_b'],
                'is_answer'=>in_array(2,$req['box_answer'])?1:0,
                'add_time'=>time()
            ]);
            $result4 = DB::connection("mysql_cart")->table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['box_c'],
                'is_answer'=>in_array(3,$req['box_answer'])?1:0,
                'add_time'=>time()
            ]);
            $result5 = DB::connection("mysql_cart")->table('question_answer')->insert([
                'question_id'=>$result1,
                'desc'=>$req['box_d'],
                'is_answer'=>in_array(4,$req['box_answer'])?1:0,
                'add_time'=>time()
            ]);
            $result = $result1 && $result2 && $result3 && $result4 && $result5;
        }elseif ($req['type'] == 3){
            //判断
            $result = DB::connection("mysql_cart")->table('question_problem')->insertGetId([
                'type_id'=>$req['type'],
                'problem'=>$req['judge'],
                'judge_answer'=>$req['judge_answer'],
                'add_time'=>time()
            ]);
        }
        if($result){
            DB::connection('mysql_cart')->commit();
            echo "成功";
        }else{
            DB::connection('mysql_cart')->rollback();
            echo "失败";
        }
    }
}

