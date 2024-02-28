<?php

namespace App\Http\Middleware\Aspects;

use App\Http\Requests\CheckOutRequest;
use App\Contracts\FileInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class LockedByMe
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
//        print "LockedByMe Aspect ..\t";
        $file = $this->repo->show($request['file_id'],0);
        if (! $file['locked'] || $file['locked_by'] != Auth::id())
        {
           Alert::WARNING('This File Is Not Locked By You')->flash();
               return redirect()->back();
        }
        return $next($request);
    }
}
