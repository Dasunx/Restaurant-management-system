<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;
use App\Exports\InventoryExport;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Inventory::all();
        //var_dump($items);
        return view('restaurant.inventory')->with('inventory' ,$items);
    }

    public function lowstock(){
      $items = Inventory::all();
      //var_dump($items);
      return view('restaurant.lowstock')->with('inventory' ,$items);
    }

    public function expired(){
      $items = Inventory::all();
      //var_dump($items);
      return view('restaurant.expired')->with('inventory' ,$items);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('restaurant.addItem');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $validate = $this->validate($request, [
          'qty' => 'integer|min:0',
          'mDate' => 'date|before:aDate',
          'eDate' => 'date|after:mDate',
          'oDate' => 'date|after_or_equal:mDate',
          'aDate' => 'date|after:oDate',


        ]);

        //Create item
        $inventory = new Inventory;
        $inventory -> Product_Name = $request -> input('pName');
        $inventory -> Brand_Name = $request -> input('bName');
        $inventory -> Quantity = $request -> input('qty');
        $inventory -> Category = $request -> input('cat');
        $inventory -> Ordered_Date = $request -> input('oDate');
        $inventory -> Arrived_Date = $request -> input('aDate');
        $inventory -> Expire_Date = $request -> input('eDate');
        $inventory -> Manufactured_Date = $request -> input('mDate');

        $inventory->save();
        return redirect('inventory')->with('success', 'Item added');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $inventory = Inventory::find($id);
        return view('restaurant.show')->with('item', $inventory);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $inventory = Inventory::find($id);
      return view('restaurant.edit')->with('item', $inventory);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      $validate = $this->validate($request, [
        'qty' => 'integer|min:0',
        'mDate' => 'date|before:aDate',
        'eDate' => 'date|after:mDate',
        'oDate' => 'date|after_or_equal:mDate',
        'aDate' => 'date|after:oDate',

        ]);

      $inventory = Inventory::find($id);
      $inventory -> Product_Name = $request -> input('pName');
      $inventory -> Brand_Name = $request -> input('bName');
      $inventory -> Quantity = $request -> input('qty');
      $inventory -> Category = $request -> input('cat');
      $inventory -> Ordered_Date = $request -> input('oDate');
      $inventory -> Arrived_Date = $request -> input('aDate');
      $inventory -> Expire_Date = $request -> input('eDate');
      $inventory -> Manufactured_Date = $request -> input('mDate');

      $inventory->save();
      return redirect('inventory')->with('success', 'Item Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $inventory = Inventory::find($id);
        $inventory->delete();

        return redirect('inventory')->with('success', 'Item Deleted');
    }

    public function export() 
    {
        return Excel::download(new InventoryExport, 'Inventories.xlsx');
    }
}
