<?php

use Illuminate\Database\Seeder;

class OrganizationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('organizations')->delete();

        \DB::table('organizations')->insert(array(
            0 =>
            array(
                "id" => 1,
                "code" => "MS001",
                "email" => "admin_masjid@gmail.com",
                "nama" => "Masjid Al-Alami",
                "telno" => "01139893143",
                "address" => "UTeM, Ayer Keroh",
                "postcode" => "34400",
                "state" => "Melaka",
                "created_at" => "2020-06-07 10:48:33",
                "updated_at" => "2020-06-07 10:52:01",
                "type_org" => "4",
                "fixed_charges" => "3.00"
            ),
            1 =>
            array(
                "id" => 2,
                "code" => "MS002",
                "email" => "admin_najihah@gmail.com",
                "nama" => "Masjid An-Najihah",
                "telno" => "0194959837",
                "address" => "Sungai Gedong, Bagan Serai",
                "postcode" => "34400",
                "state" => "Perak",
                "created_at" => "2020-06-07 10:48:33",
                "updated_at" => "2020-06-07 10:52:01",
                "type_org" => "4",
                "fixed_charges" => "3.00"

            ),
            2 =>
            array(
                "id" => 3,
                "code" => "SK001",
                "email" => "admin_sekolah@gmail.com",
                "nama" => "SRA Al-Ridhwaniah",
                "telno" => "01139893143",
                "address" => "UTeM, Ayer Keroh",
                "postcode" => "34400",
                "state" => "Melaka",
                "created_at" => "2020-06-07 10:48:33",
                "updated_at" => "2020-06-07 10:52:01",
                "type_org" => "2",
                "fixed_charges" => "2.00"

            ),
            3 =>
            array(
                "id" => 4,
                "code" => "SM00004",
                "email" => "school@gmail.com",
                "nama" => "SM Teknik Melaka",
                "telno" => "01139893143",
                "address" => "UTeM, Ayer Keroh",
                "postcode" => "34400",
                "state" => "Melaka",
                "created_at" => "2020-06-07 10:48:33",
                "updated_at" => "2020-06-07 10:52:01",
                "type_org" => "2",
                "fixed_charges" => "2.00"

            ),
        ));
    }
}
