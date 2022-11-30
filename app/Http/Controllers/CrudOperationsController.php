<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CrudOperations;
use Illuminate\Http\Request;

class CrudOperationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //search is here
        $search=$request['search'] ?? "";
        if($search!==""){
            $data=CrudOperations::where("first_name","LIKE","%$search%")
            ->orwhere("email","LIKE","%$search%")->paginate(8);
        }
        else{
            $data=CrudOperations::with('getCountry')->paginate(8);
        }
        //get all data in index page
        // $data=CrudOperations::with('getCountry')->paginate(7);
        // echo"<pre>";
        // print_r($users);
        // echo"</pre>";
        // exit;
        return view('index',compact('data','search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $country=Country::all();
        return view('registration',compact('country'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData=$request->except(['_token','regist']);
        
        $imageName='lv_'.rand().'.'.$request->profile->extension();
        //move the image this folder
        $request->profile->move(public_path('profiles/'),$imageName);
        $request->file('profile')->storeAs('uploads',$imageName);
        $requestData['profile']=$imageName;

        // echo"<pre>";
        // print_r($imageName);
        // echo"</pre>";
        // exit;  

        $data=CrudOperations::create($requestData);
        return redirect()->route('crud.index')->with('success','your data is successfully inserted!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CrudOperations  $crudOperations
     * @return \Illuminate\Http\Response
     */
    public function show(CrudOperations $crud)
    {
        $country=Country::all();
        
        // echo"</pre>";
        // print_r($crud);
        // exit;
        return view('show',compact('country','crud'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CrudOperations  $crudOperations
     * @return \Illuminate\Http\Response
     */
    public function edit(CrudOperations $crud)
    {
           $country=Country::all();
           return view('edit',compact('country','crud'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CrudOperations  $crudOperations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CrudOperations $crud)
    {
        // echo"<pre>";
        // print_r($crud);
        // exit;

        $crud->first_name=$request->first_name ?? $crud->first_name;
        $crud->last_name=$request->last_name ?? $crud->last_name;
        $crud->email=$request->email ?? $crud->email;
        $crud->contact=$request->contact ?? $crud->contact;
        $crud->gender=$request->gender ?? $crud->gender;
        $crud->hobbies=$request->hobbies ?? $crud->hobbies_arr;
        $crud->address=$request->address ?? $crud->address;
        $crud->country=$request->country ?? $crud->country;
        
        if(isset($request->profile)){
            $imageName='lv_'.rand().'.'.$request->profile->extension();
            //move the image this folder
            $request->profile->move(public_path('profiles/'),$imageName);
            $crud->profile = $imageName;

        } 
        $crud->save();
        return redirect()->route('crud.index')->with('success','your data is successfully updated!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CrudOperations  $crudOperations
     * @return \Illuminate\Http\Response
     */
    public function destroy(CrudOperations $crud)
    {
       
        $crud->delete();
        return redirect()->route('crud.index')->with('success','your data is successfully deleted!!');
    }
}
