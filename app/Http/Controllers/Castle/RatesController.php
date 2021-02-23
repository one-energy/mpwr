<?php

namespace App\Http\Controllers\Castle;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Rates;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class RatesController extends Controller
{
    use RefreshDatabase;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('castle.rate.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::query()->get();
        $roles       = User::ROLES;
        $roles       = array_slice($roles, -5);

        return view('castle.rate.create', compact('departments', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $validated = request()->validate([
            'name'          => 'required|string|min:3|max:255',
            'time'          => 'required|numeric',
            'rate'          => 'required',
            'department_id' => 'required',
            'role'          => 'required',
        ]);

        $rate                = new Rates();
        $rate->name          = $validated['name'];
        $rate->time          = $validated['time'];
        $rate->rate          = $validated['rate'];
        $rate->department_id = $validated['department_id'];
        $rate->role          = $validated['role'];

        if($rate->alreadyExists()){
            alert()
                ->withTitle(__('This rate already exists'))
                ->withColor('red')
                ->send();
            return back();
        } else {
            $rate->save();
            alert()
                ->withTitle(__('Rate created!'))
                ->send();

            return redirect(route('castle.rates.index'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Rates $rate)
    {
        $departments = Department::get();
        $roles       = User::ROLES;
        $roles       = array_slice($roles, -5);

        return view('castle.rate.edit', compact('rate', 'departments', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $validated = request()->validate([
            'name'          => 'required|string|min:3|max:255',
            'time'          => 'required|numeric',
            'rate'          => 'required',
            'department_id' => 'required',
            'role'          => 'required',
        ]);

        $rate                = Rates::whereId($id)->first();
        $rate->name          = $validated['name'];
        $rate->time          = $validated['time'];
        $rate->rate          = $validated['rate'];
        $rate->department_id = $validated['department_id'];
        $rate->role          = $validated['role'];

        if($rate->alreadyExists()){
            alert()
                ->withTitle(__('This rate already exists'))
                ->withColor('red')
                ->send();
            return back();
        } else {
            $rate->save();
            alert()
                ->withTitle(__('Rate updated!'))
                ->send();

            return redirect(route('castle.rates.index'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rates $rate)
    {
        $rate->delete();

        alert()
            ->withTitle(__('Rate deleted!'))
            ->send();

        return redirect(route('castle.rates.index'));
    }

    public function getRatesPerRole($role)
    {
        $rate = Rates::whereRole($role);
        if ($rate->exists())
            return $rate->first();
        return response('', 204);
    }
}
