<?php

namespace App\Http\Middleware\Aspects;

use App\Contracts\FileInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AllFilesBlocked
{
    protected FileInterface $repo;
    public function __construct(FileInterface $fileInterface){
        $this->repo = $fileInterface;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
//        print "BulkLock Aspect ..\t";
        $request->validate(['files'=>'required|array','files.*'=>'required|exists:files,id']);
        foreach ($request['files'] as $file_id){
            $file = $this->repo->show($file_id,0);
            if ($file['locked']){
                Alert::WARNING('There is files locked')->flash();
               return redirect()->back();
            }
        }
            //return $next($request);

        
//        print "BulkLock Aspect Passed.\n";
        return $next($request);
    }
}

