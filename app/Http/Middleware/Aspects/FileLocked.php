<?php

namespace App\Http\Middleware\Aspects;

use App\Contracts\FileInterface;
use Closure;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class FileLocked
{
    protected FileInterface $repo;
    public function __construct(FileInterface $fileinterface){
        $this->repo = $fileinterface;
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
//        print "FileLocked Aspect ..\t";
        $file = $this->repo->show($request['id'],0);
        if ($file['locked']){
            Alert::WARNING('This File Is Locked')->flash();
               return redirect()->back();
        }
//        print "FileLocked Aspect Passed.\n";
        return $next($request);
    }
}
