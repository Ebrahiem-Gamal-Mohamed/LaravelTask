<?php

namespace App\Http\Controllers;

use App\Employee;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use FarhanWazir\GoogleMaps\Facades\GMapsFacade as GMaps;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$employees = Employee::paginate();
        $employees = Employee::all();
        return view('home',compact('employees'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('employee.add',['users'=>$users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imageName="";
        if($request->hasFile('user_image')){
            $imageName=$request->user_image->store('public');
        }

        //Make Validation ...
        $this->validate($request,[
            "first_name"=>'required|string|min:3|max:15',
            "last_name"=>'required|string|min:3|max:15',
            "user_image"=>['required','image','dimensions:min_width=100,min_height=200']
          ]);

        $employee = new Employee;
        $employee->first_name = request('first_name');
        $employee->last_name = request('last_name');
        $employee->user_image = $imageName;  
        $employee->job = request('emp_job');  
        $employee->user_id = request('user_id');  
        $employee->location = \DB::raw("GeomFromText('POINT(".request('lat')." ".request('lng').")')");  
        $employee->save(); 

        $employees = Employee::all();
        return redirect('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $emp = Employee::find($id);
        return $emp; //json_encode($emp);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id)->user_emp;
        return view('employee.edit',compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $imageName="";
        if($request->hasFile('user_image')){
            $imageName=$request->user_image->store('public');
        }
        $employee = Employee::find($id)->user_emp;
        //Make Validation ...
        $this->validate($request,[
            "first_name"=>'required|string|min:3|max:15',
            "last_name"=>'required|string|min:3|max:15',
            "user_image"=>['required','image','dimensions:min_width=100,min_height=200']
          ]);

        $employee->first_name = request('first_name');
        $employee->last_name = request('last_name');
        $employee->user_image = $imageName;  
        $employee->job = request('emp_job');  
        $employee->user_id = request('user_id');  
        $employee->location = \DB::raw("GeomFromText('POINT(".request('lat')." ".request('lng').")')");  
        $employee->save(); 
        
        $employees = Employee::all();
        return view('home',compact('employees'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Employee::find($id)->delete();
        return back();
    }

    public function search(){
        $employee  = Employee::all();
        //$page = Input::get('page', 1); 
        $user_detail = [];
        $keyword = request('search_item');

        if ($keyword!='') {
            $employee  = Employee::
                   where("first_name", "LIKE","%$keyword%")
                   ->orWhere("last_name", "LIKE", "%$keyword%")
                   ->get();
        }

       return $employee;

        //$itemPerPage=5;
        //$count  = Employee::count();
        //$offSet = ($page * $itemPerPage) - $itemPerPage;
        //$itemsForCurrentPage = array_slice($employee->toArray(), $offSet, $itemPerPage);
        //return new LengthAwarePaginator($itemsForCurrentPage, count($employee), $itemPerPage, $page,$keyword);
    }
}
