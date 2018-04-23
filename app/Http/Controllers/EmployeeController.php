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
        
        $mylocation = $employees->first()->location;
        //return $mylocation;
        $mylocation = explode('(',$mylocation);
        $mylocation = explode(')',$mylocation[1]);
        $mylocation = explode(' ',$mylocation[0]);

        return view('home', ['employees'=>$employees, 'location'=>$mylocation]);
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
        //$emp = Employee::find($id)->user_emp;
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
        $employee = Employee::find($id);
        //$mylocation = \DB::table('employees')->select((\DB::raw('AsText(location)')))->where('id',$id)->get();
        $mylocation = $employee->location;
        $mylocation = explode('(',$mylocation);
        $mylocation = explode(')',$mylocation[1]);
        $mylocation = explode(' ',$mylocation[0]);

        return view('employee.edit',['employee'=>$employee, 'location'=>$mylocation]);
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
        $employee = Employee::find($id);

        //Make Validation ...
        $this->validate($request,[
            "first_name"=>'required|string|min:3|max:15',
            "last_name"=>'required|string|min:3|max:15',
            "emp_job"=>'required|string|min:10',
          ]);

        $employee->first_name = request('first_name');
        $employee->last_name = request('last_name');
        $employee->job = request('emp_job');  
        $employee->user_id = $id;  
        $employee->location = \DB::raw("GeomFromText('POINT(".request('lat')." ".request('lng').")')");  
        $employee->save(); 
        
        return redirect('home');
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
