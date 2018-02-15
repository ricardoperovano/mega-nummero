<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NumberController extends Controller
{
    private $sortable_numbers = [];
    private $numbers_array = [];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function __construct(){

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function sortNumbers()
    {
        $this->sortable_numbers = $this->getNumbersToSort();

        for ($i = 0; $i < 6; $i++) {
            $number = str_pad($this->sortable_numbers[rand(0,sizeof($this->sortable_numbers)-1)],2,"0",STR_PAD_LEFT);

            while (in_array($number,$this->numbers_array))
            {
                    $number = str_pad($this->sortable_numbers[rand(0,sizeof($this->sortable_numbers)-1)],2,"0",STR_PAD_LEFT);
            }

            array_push($this->numbers_array, $number);
        }
        asort($this->numbers_array);
    }

    private function groupArray($array)
    {
        $group_array = [];
        for ($i = 0; $i < sizeof($array); $i++)
        {
            if(!array_key_exists((int)$array[$i],$group_array)){
                $group_array[$array[$i]] = 1;
            }else{
                $group_array[$array[$i]] += 1;
            }
        }

        return $group_array;
    }

    private function isValidNumber()
    {
        $cols = [];
        $rows = [];

        foreach ($this->numbers_array as $number) {
            $row = $this->getRow($number);
            $col = $this->getCol($number, $row);
            array_push($cols, $col);
            array_push($rows, $row);
        }

        if($this->rowsExceded($rows) || $this->colsExceded($cols))
        {
            return false;
        }

        return true;
    }

    private function rowsExceded($rows)
    {
        $rows_count = $this->groupArray($rows);

        foreach ($rows_count as $key => $value)
        {
            if($value > 2)
            {
                return true;
            }
        }

        return false;
    }

    private function colsExceded($cols)
    {
        $cols_count = $this->groupArray($cols);

        foreach ($cols_count as $key => $value)
        {
            if($value > 2)
            {
                return true;
            }
        }

        return false;
    }

    public function getNumbers()
    {
        $this->sortNumbers();

        while(!$this->isValidNumber())
        {
            $this->sortable_numbers = [];
            $this->numbers_array = [];
            $this->sortNumbers();
        }

        return view('number',['numbers' => $this->numbers_array]);
    }

    private function getCol($number,$row)
    {
        return 10 - (($row * 10) - $number);
    }

    private function getRow($number)
    {
        return round(ceil(($number / 10)),0,PHP_ROUND_HALF_UP);
    }

    private function getNumbersToSort()
    {
        $actual_number = 0;
        $exclued_rows = $this->getRowsToExclude();
        $numbers = [];
        for($row = 1; $row <= 6; $row ++)
        {
            for($col = 1; $col <= 10; $col++)
            {
                $actual_number++;
                if(!in_array($row,$exclued_rows))
                {
                    array_push($numbers,$actual_number);
                }
            }
        }

        return $numbers;
    }
    private function getRowsToExclude()
    {
        $row_1 = rand(1,6);

        do{
            $row_2 = rand(1,6);
        }
        while($row_2 == $row_1);

        return [$row_1,$row_2];
    }
}
