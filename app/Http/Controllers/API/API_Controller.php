<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\CrudOperations;
use Illuminate\Http\JsonResponse;

class API_Controller extends Controller
{
    //API INTEX OPERATION

    public function index(Request $request)  : JsonResponse  //type hinting
    {
        try{

        $search=$request['search'];
        $search=$request['search'] ?? "";
        
         if($search!=""){
        $data=CrudOperations::where('first_name','LIKE',"%$search%")->with('getCountry')->paginate(5);
        }
         else
         {
            $data=CrudOperations::with('getCountry')->paginate(5);
         }
        
         return response()->json(['status'=>200,'message'=>'data is retrived successfuly','data'=>$data]);
        }
        catch(\Exception $ex){
            return response()->json(['status'=>500,'message'=>$ex->getMessage(),'data'=>null]);

        }
        
    }

   //API CREATE OPERATION 

    public function create() :JsonResponse
    {
       try{
        $country=Country::all();
        $message=['message'=>"data is succesfuly retrived"];
        return response()->json(['status'=>200,'message'=>$message,'data'=>$country]);
       }
    catch(\Exception $ex)
    {
        return response()->json(['status'=>200,'message'=>$ex->getMessage(),'data'=>$country]);
    }
 
    }

    //API STORE OPERATION

    public function store(Request $request)
    {
           
        try{
            $requestData=$request->all();
            if(!empty($requestData['profile']))
            {
                $imageName='lv_'.rand().'.'.$request->profile->extension();
                $request->profile->move(public_path('profiles/'),$imageName);
                $requestData['profile']=$imageName;
            }
            
            $data=CrudOperations::create($requestData);
           return response()->json(['status'=>200,'message'=>'data retrive successfuly done','data'=>$data]);
        }
        catch(\Exception $ex)
        {
            return response()->json(['status'=>200,'message'=>$ex->getMessage(),'data'=>$data]);
        }

       
    }

    //API EDIT OPERATION

    public function edit(CrudOperations $api_crud)
    {

           try{
            $country=Country::all();

            return response()->json(['status'=>200,'message'=>'data succesfuly retrived!!','data'=>['user_data'=>$api_crud,'country'=>$country]]);
           }
           catch(\Exception $ex){
            return response()->json(['status'=>200,'message'=>$ex->getMessage(),'data'=>['user_data'=>$api_crud,'country'=>$country]]);

           }
    }
    
    //API UPDATED OPERATION

    public function update(Request $request, CrudOperations $api_crud) : JsonResponse
    {
        
        try{
        $api_crud->first_name=$request->first_name ?? $api_crud->first_name;
        $api_crud->last_name=$request->last_name ?? $api_crud->last_name;
        $api_crud->email=$request->email ?? $api_crud->email;
        $api_crud->contact=$request->contact ?? $api_crud->contact;
        $api_crud->gender=$request->gender ?? $api_crud->gender;
        $api_crud->hobbies=$request->hobbies ?? $api_crud->hobbies_arr;
        $api_crud->address=$request->address ?? $api_crud->address;
        $api_crud->country=$request->country ?? $api_crud->country;
        $api_crud->save();

        return response()->json(['status'=>200,'message'=>'data updeted successfuly!!','data'=>$api_crud]);
        }
        catch(\Exception $ex)
        {
            return response()->json(['status'=>200,'message'=>$ex,'data'=>$api_crud]);
        }
       
    }


    //API DELETE OPERATION
    public function destroy(CrudOperations $api_crud)
    {
       try{
        $api_crud->delete();
        return response()->json(['status'=>200,'message'=>'your data is successfully deleted!!','data'=>null]);
       }
       catch(\Exception $ex)
       {
        return response()->json(['status'=>200,'message'=>$ex->getMessage(),'data'=>null]);
       }
    }

    //API IMAGE UPDATING OPERATION

    public function imageUpdate(Request $request,)
    {
        try{

            $requestData=$request->all(); 
            // print_r($requestData);
            // die;
        if(!empty($requestData['profile']))
            {
                $imageName='lv_'.rand().'.'.$request->profile->extension();
                $request->profile->move(public_path('profiles/'),$imageName);
                $requestData['profile']=$imageName;
            }

            $data=CrudOperations::where('id',['id'=>$request['id']])->update(['profile'=>$requestData['profile']]);

            return response()->json(['status'=>200,'message'=>'your data is successfully deleted!!','data'=>$data]);
        }
        catch(\Exception $ex)
        {
            return response()->json(['status'=>200,'message'=>$ex->getMessage(),'data'=>$data]);
        }
    }

   
}