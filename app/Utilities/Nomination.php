<?php
/**
 * Created by PhpStorm.
 * User: gpiproject
 * Date: 9/30/17
 * Time: 6:56 PM
 */

namespace App\Utilities;

use Illuminate\Http\Request;
use App\Nominee;


class Nomination
{
    public function updateNominee($nominee)
    {
        /* Find the nomination record */
        $nomination = Nominee::where('nominee_email', $email)
            ->first();

        /* If nomination record found */
        if($nomination){
            /* Pending nomination*/
            if($nomination->status == 'pending'){

                $nomination->status = 'registered';

                /* Return 1 if nominee successfully registered*/
                try{
                    $nomination->save();
                }catch(\Exception $e){
                    $nomination->save(); // Force save if something went wrong initially. This is because Nominee has been registered.
                }
            }

            /* Registered nomination */
            elseif($nomination->status == 'registered'){
                /* Return 2 if nomination have been registered already */
                return 2;
            }
        }

        /* If nomination record not found, return 0*/
        return 0;
    }
}