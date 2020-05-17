<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class KelasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        \DB::table('pe3_kelas')->insert([
            'idkelas'=>"A",
            'nkelas'=>'REGULER (S1)',
        ]);               

        \DB::table('pe3_kelas')->insert([
            'idkelas'=>"B",
            'nkelas'=>'KARYAWAN (S1)',
        ]);               

        \DB::table('pe3_kelas')->insert([
            'idkelas'=>"C",
            'nkelas'=>'EKSTENSI (S1)',
        ]);               
    }
}