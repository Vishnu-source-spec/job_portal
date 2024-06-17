<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\JobType;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Auth;

class JobsController extends Controller
{
    //  this method will show jobs
    public function index(Request $request) {
        $categories = Category::where('status',1)->get();
        $jobTypes = JobType::where('status',1)->get();
        $jobs = Job::where('status',1);

        //search using keyword
        if(!empty($request->keyword)){

            $jobs = $jobs->where(function($query) use($request) {
                $query->orWhere('title','like','%'. $request->keyword  .'%');
                $query->orWhere('keywords','like','%'. $request->keyword  .'%');
            });

        }

        //search using location
        if(!empty($request->location)){

           
              $jobs= $jobs->Where('location', $request->location);
            


        }
        //search using category
        if(!empty($request->category)){

           
              $jobs= $jobs->Where('category_id', $request->category);
            

        }
        $jobTypeArray = [];
        // search using job type
        if(!empty($request->jobType)){
            //1,2,2
           $jobTypeArray = explode(',',$request->jobType);

           
              $jobs= $jobs->whereIn('job_type_id', $jobTypeArray);
            

        }
        //search using experience

        if(!empty($request->experience)){

           
            $jobs= $jobs->Where('experience', $request->experience);
          

      }


      $jobs = $jobs->with(['jobType','category']);

      if($request->sort == '0') {
          $jobs = $jobs->orderBy('created_at','ASC');
      } else {
          $jobs = $jobs->orderBy('created_at','DESC');
      }
      

      $jobs = $jobs->paginate(9);

        return view('front.jobs',[
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'jobs' => $jobs,
            'jobTypeArray' => $jobTypeArray
        ]);
    }


   // this method will shoe jobs page
    public function detail($id){
        $job = Job::where([
            'id'=>$id , 
            'status' => 1
        ])->with(['jobType', 'category'])->first();
      //  dd($job);

      if($job == null){
        abort(404);
      }
        
        return view('front.jobDetail',[
            'job' => $job
        ]);

    }

    public function applyJob(Request $request){
// return $request->all();
        $id = $request->id;
        // $job = Job::where('id',$id)->first();
        $job = Job::find($id);
       //if job not found in db
        if($job == null){
            session()->flash('error' , 'Job does not exist');

            return response()->json([
                
                'status' => false,
                'message' => 'Job does not exist'
            ]);


        }
        //you can not apply on your own job
        $employer_id = $job->user_id;

        if($employer_id == Auth::user()->id){
            session()->flash('error' , 'You can not apply on your job');

            return response()->json([
                
                'status' => false,
                'message' => 'You can not apply on your job'
            ]);
        }
         
        $application = new JobApplication();
        $application->job_id =$id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application ->save();


        session()->flash('success' , 'Applied');

            return response()->json([
                
                'status' => true,
                'message' => 'You have successfully applied'
            ]);
    }
}
