<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobPart;
use App\Models\JobService;
use Illuminate\Http\Request;
use App\Http\Resources\JobResource;
use Illuminate\Support\Facades\Crypt;
use GuzzleHttp\Client;

class JobController extends Controller
{
    public function search(Request $request){
        $keyword = $request->keyword;
        $data = Job::with('brand','unit','status','client','images','services.service','parts.product')
        ->where('code','LIKE', "%{$keyword}%")
        ->limit(1)->get();
        $data = JobResource::collection($data);
        return $data;
    }

    public function index(Request $request){
        if($request->search){
            if($request->status == 'Pickups'){
                $data = JobResource::collection(
                    Job::query()
                    ->with('brand','unit','status','client','images','services.service','parts.product')
                    ->when($request->keyword, function ($query, $keyword) {
                        $query->where('code','LIKE', "%{$keyword}%");
                    })
                    ->when($request->status, function ($query, $status) {
                        $query->whereHas('status',function ($query) use ($status) {
                            $query->where('name','Completed')->where('is_pickup',0);
                        });
                    })
                    ->orderBy('created_at','DESC')
                    ->get()
                );
                return $data;
            }else{
                $data = JobResource::collection(
                    Job::query()
                    ->with('brand','unit','status','client','images','services.service','parts.product')
                    ->when($request->keyword, function ($query, $keyword) {
                        $query->where('code','LIKE', "%{$keyword}%");
                    })
                    ->when($request->date, function ($query, $date) {
                        $d = (explode("to", $date));
                        $monday = str_replace(' ','',$d[0]);
                        $sunday = str_replace(' ','',$d[1]);
                        // dd($monday,$sunday);
                        $monday =  date("Y-m-d", strtotime($monday));
                        $sunday = date("Y-m-d", strtotime($sunday));  
                        $query->whereBetween('created_at', [$monday, $sunday]);
                    })
                    ->when($request->status, function ($query, $status) {
                        $query->whereHas('status',function ($query) use ($status) {
                            $query->where('name',$status);
                        });
                    })
                    ->orderBy('created_at','DESC')
                    ->get()
                );
                return $data;
            }
        }else{
            return inertia('Jobs/Index');
        }
    }

    public function create(){
        return inertia('Jobs/Create');
    }

    public function store(Request $request){

        $validated = $request->validate([
            'model' => 'required',
            'serial_no' => 'nullable',
            'total' => 'required',
            'client_id' => 'required',
            'brand_id' => 'required',
            'unit_id' => 'required',
            'remarks' => 'required|max:200',
            'images' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png|max:2000',
            'services' => 'required',
        ]);
        
        $data = \DB::transaction(function () use ($request){
            $count = Job::count();
            $code = 'JL'.date('Y').date('m')."-".str_pad(($count+1), 4, '0', STR_PAD_LEFT);  
            $data = Job::create(array_merge($request->all(),['code' => $code, 'status_id' => 1]));

            foreach($request['services'] as $service) {
                $data->services()->create(array_merge($request->all(),[
                    'service_id' => $service['id'],
                    'amount' => $service['amount'],
                    'is_before' => 1,
                    'is_approved' => 1,
                    'is_completed' => 0,
                    'remarks' => 'n/a'
                ]));
            }

            if($request->parts){
                foreach($request['parts'] as $product) {
                    $data->parts()->create(array_merge($request->all(),[
                        'product_id' => $product['id'],
                        'amount' => $product['amount'],
                        'is_before' => 1,
                        'is_approved' => 1,
                        'is_installed' => 0,
                        'remarks' => 'n/a',
                        'quantity' => 1
                    ]));
                }
            }

            if($request['images'])
            {
                $files =$request['images'];   
                foreach ($files as $key=>$file) {
                    if($key == 0){
                        $file_name = $code.'.'.$file->getClientOriginalExtension();
                    }else{
                        $file_name = $code.'-'.$key.'.'.$file->getClientOriginalExtension();
                    }
                    $file_path = $file->storeAs('uploads/'.$code.'/', $file_name, 'public');
    
                    $fileinfo = pathinfo($file_name, PATHINFO_FILENAME);

                    $data->images()->create(array_merge($request->all(),[
                        'image' =>  $file_name
                    ]));
                }
            }
            if($data){
                $mobile = $data->client->mobile;
                $message = 'Hi good day! This is an automated text from weefix lenin, your job order number is '.$data->code.' . Thank you, For  questions, please call our number, (062) 975-3373 or (0927) 717-5421';
                $debug = false;
                $client = new Client();
                $result = $client->request('GET', 'http://gateway.onewaysms.ph:10001/api.aspx', [
                    'query' => [
                        'apiusername' => 'API7BEXYMMKBT',
                        'apipassword' => 'API7BEXYMMKBT7BEXY',
                        'senderid' => 'WEEFIX',
                        'mobileno' => $mobile,
                        'message' => $message,
                        'languagetype' => 1
                    ]
                ]);

                $response = json_decode($result->getBody()->getContents());
            }
        });

        return back()->with([
            'message' => 'Successfully Added',
            'data' => 'newjob',
            'type' => 'bxs-check-circle'
        ]); 
    }

    public function update(Request $request){

        $validated = $request->validate([
            'client_id' => 'sometimes|required',
            'id' => 'required',
        ]);


        if($request->type == 'sms'){
            $data = \DB::transaction(function () use ($request){ 
                $mobile = $request->mobile;
                if($request->subtype == 'services'){
                    $message = 'Hi! Good day! This is an automated text from Weefix Lenin, we would like to inform you that your device is in need of a part replacement. For confirmation and questions, please call our number, (062) 975-3373 or (0927) 717-5421';
                }else{
                    $message = 'Hi! Good day! This is an automated text from Weefix Lenin, we would like to inform you that the part/s that we ordered has arrived. We are now in the process of replacing it. For confirmation and questions, please call our number, (062) 975-3373 or (0927) 717-5421';
                }
                // $message = 'Hi! Good day! This is a automated text form Weefix Lenin, we would like to inform you that your device is in need of a part replacement. For confirmation and questions, please call our number, (062) 975-3373 or (0927) 717-5421';
                $debug = false;
                $client = new Client();
                $result = $client->request('GET', 'http://gateway.onewaysms.ph:10001/api.aspx', [
                    'query' => [
                        'apiusername' => 'API7BEXYMMKBT',
                        'apipassword' => 'API7BEXYMMKBT7BEXY',
                        'senderid' => 'WEEFIX',
                        'mobileno' => $mobile,
                        'message' => $message,
                        'languagetype' => 1
                    ]
                ]);

                $response = json_decode($result->getBody()->getContents());
                
                return back()->with([
                    'message' => 'Message Sent',
                    'data' => '',
                    'type' => 'bxs-check-circle'
                ]); 
            });
        }else if($request->type == 'service'){
            $data = \DB::transaction(function () use ($request){
                $data = JobService::findOrFail($request->id);
                $data->update($request->all());
                $data = Job::with('brand','unit','status','client','images','services.service','parts.product')->where('id',$request->job_id)->first();
                return new JobResource($data);
            });
            return back()->with([
                'message' => 'Service Completed',
                'data' => $data,
                'type' => 'bxs-check-circle'
            ]); 
        }else if($request->type == 'part'){
            $data = \DB::transaction(function () use ($request){
                $data = JobPart::findOrFail($request->id);
                $data->update($request->all());
                $data = Job::with('brand','unit','status','client','images','services.service','parts.product')->where('id',$request->job_id)->first();
                return new JobResource($data);
            });
            return back()->with([
                'message' => 'Part Installed',
                'data' => $data,
                'type' => 'bxs-check-circle'
            ]); 
        }else if($request->type == 'newservice'){
            $data = \DB::transaction(function () use ($request){
                $data = Job::findOrFail($request->id);
                $tot = 0;
                foreach($request['services'] as $service) {
                    $data->services()->create(array_merge($request->all(),[
                        'service_id' => $service['id'],
                        'amount' => $service['amount'],
                        'is_before' => 1,
                        'is_approved' => 1,
                        'is_completed' => 0,
                        'remarks' => 'n/a'
                    ]));
                    $tot = $tot + $service['amount'];
                }
                ($request->has_warranty) ? '' : $data->total = $data->total + $tot; 
                $data->save();
                $data = Job::with('brand','unit','status','client','images','services.service','parts.product')->where('id',$request->id)->first();
                return new JobResource($data);
            });
            return back()->with([
                'message' => 'Service Added',
                'data' => $data,
                'type' => 'bxs-check-circle'
            ]); 
        }else if($request->type == 'removeservice'){
            $data = \DB::transaction(function () use ($request){
                $data = Job::findOrFail($request->id);
                $service = JobService::findOrFail($request->item_id);
                ($request->has_warranty) ? '' : $data->total = $data->total - $service->amount;
                $data->save();
                $service->delete();
                $data = Job::with('brand','unit','status','client','images','services.service','parts.product')->where('id',$request->id)->first();
                return new JobResource($data);
            });
            return back()->with([
                'message' => 'Service Removed',
                'data' => $data,
                'type' => 'bxs-check-circle'
            ]); 
        }else if($request->type == 'newpart'){
            $data = \DB::transaction(function () use ($request){
                $data = Job::findOrFail($request->id);
                if($request->parts){
                    $tot = 0;
                    foreach($request['parts'] as $product) {
                        $data->parts()->create(array_merge($request->all(),[
                            'product_id' => $product['id'],
                            'amount' => $product['amount'],
                            'is_before' => 1,
                            'is_approved' => 1,
                            'is_installed' => 0,
                            'remarks' => 'n/a',
                            'quantity' => 1
                        ]));
                        $tot = $tot + $product['amount'];
                    }
                }
                ($request->has_warranty) ? '' : $data->total = $data->total + $tot; 
                $data->save();
                $data = Job::with('brand','unit','status','client','images','services.service','parts.product')->where('id',$request->id)->first();
                return new JobResource($data);
            });
            return back()->with([
                'message' => 'Part Added',
                'data' => $data,
                'type' => 'bxs-check-circle'
            ]); 
        }else if($request->type == 'removepart'){
            $data = \DB::transaction(function () use ($request){
                $data = Job::findOrFail($request->id);
                $part = JobPart::findOrFail($request->item_id);
                ($request->has_warranty) ? '' : $data->total = $data->total - $part->amount;
                $data->save();
                $part->delete();
                $data = Job::with('brand','unit','status','client','images','services.service','parts.product')->where('id',$request->id)->first();
                return new JobResource($data);
            });
            return back()->with([
                'message' => 'Part Removed',
                'data' => $data,
                'type' => 'bxs-check-circle'
            ]); 
        }else if($request->type == 'completed'){
            $data = \DB::transaction(function () use ($request){
                $data = Job::findOrFail($request->id);
                $data->update($request->all());

                if($request->status_id == 4){
                    $mobile = $data->client->mobile;
                    $message = 'Hi! Good day! This is an automated text from Weefix Lenin, we would like to inform you that your device is now ready for pick up! For questions, please call our number, (062) 975-3373 or (0927) 717-5421';
                    $debug = false;
                    $client = new Client();
                    $result = $client->request('GET', 'http://gateway.onewaysms.ph:10001/api.aspx', [
                        'query' => [
                            'apiusername' => 'API7BEXYMMKBT',
                            'apipassword' => 'API7BEXYMMKBT7BEXY',
                            'senderid' => 'WEEFIX',
                            'mobileno' => $mobile,
                            'message' => $message,
                            'languagetype' => 1
                        ]
                    ]);
    
                    $response = json_decode($result->getBody()->getContents());
                }

                $data = Job::with('brand','unit','status','client','images','services.service','parts.product')->where('id',$request->id)->first();
                return new JobResource($data);
            });

            return back()->with([
                'message' => 'Job Completed!',
                'data' => $data,
                'type' => 'bxs-check-circle'
            ]); 
        }else{
            $data = \DB::transaction(function () use ($request){
                $data = Job::findOrFail($request->id);
                $data->update($request->all());
                $data = Job::with('brand','unit','status','client','images','services.service','parts.product')->where('id',$request->id)->first();
                return new JobResource($data);
            });

            if($request->type == 'paid'){
                $message = 'Job Paid';
            }else if($request->type == 'picked'){
                $message = 'Job Picked';
            }else if($request->type == 'condemed'){
                $message = 'Job Condemed';
            }else{
                $message = 'Job Updated';
            }
            return back()->with([
                'message' => $message,
                'data' => $data,
                'type' => 'bxs-check-circle'
            ]); 

        }

        // return back()->with([
        //     'message' => 'Successfully Updated',
        //     'data' => $data,
        //     'type' => 'bxs-check-circle'
        // ]); 

    }

    public function show($id){
        $id = Crypt::decryptString($id);
        $data = Job::with('brand','unit','status','client','images','services.service','parts.product')->where('id',$id)->first();
        $data = new JobResource($data);

        return inertia('Jobs/Show',['job' => $data]);
    }
}
