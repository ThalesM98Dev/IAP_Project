<?php

namespace App\Http\Middleware;

use App\Contracts\FileInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class FileOwner
{
    protected FileInterface $repo;

    public function __construct(FileInterface $fileInterface)
    {
        $this->repo = $fileInterface;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
//        print "FileOwner Aspect ..\t";
        $file = $this->repo->show($request['id'], 0);
        if ($file['owner_id'] != Auth::id())
        {
            Alert::WARNING('This File Is Not Yours')->flash();
               return redirect()->back();
        } 
//        print "FileOwner Aspect Passed..\n";
        return $next($request);
    }
}
