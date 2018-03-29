<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Board;
use Illuminate\Http\Request;

class BoardsController extends Controller
{
    

    public function confirmAdmin() {
        if(!Auth::user() || !Auth::user()->global_admin) {
            abort(403);
        }
        return true;
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->confirmAdmin();
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $boards = Board::where('unit', 'LIKE', "%$keyword%")
                ->orWhere('public_title', 'LIKE', "%$keyword%")
                ->orWhere('public', 'LIKE', "%$keyword%")
                ->orWhere('anyone_can_edit', 'LIKE', "%$keyword%")
                ->paginate($perPage);
        } else {
            $boards = Board::paginate($perPage);
        }

        return view('admin.boards.index', compact('boards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->confirmAdmin();
        return view('admin.boards.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->confirmAdmin();
        $requestData = $request->all();
        
        Board::create($requestData);

        return redirect('admin/boards')->with('flash_message', 'Board added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $this->confirmAdmin();
        $board = Board::findOrFail($id);

        return view('admin.boards.show', compact('board'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        
        if((Auth::user()->boards->find($id) && Auth::user()->boards->find($id)->pivot->is_admin) || $this->confirmAdmin()) {
            $board = Board::findOrFail($id);

            return view('admin.boards.edit', compact('board'));
        }
        
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        if((Auth::user()->boards->find($id) && Auth::user()->boards->find($id)->pivot->is_admin) || $this->confirmAdmin()) {
            $requestData = $request->all();
            
            $board = Board::findOrFail($id);
            $board->update($requestData);

            return redirect('admin/boards/' . $id . '/edit')->with('flash_message', 'Board updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $this->confirmAdmin();
        Board::destroy($id);

        return redirect('admin/boards')->with('flash_message', 'Board deleted!');
    }
}
