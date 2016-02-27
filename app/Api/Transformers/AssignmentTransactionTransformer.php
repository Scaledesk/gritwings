<?php namespace App\Api\Transformers;

use App\Assignment_Transaction;
use App\AssignmentStatus;
use League\Fractal\TransformerAbstract;

class AssignmentTransactionTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array.
     *
     * @param $item
     * @return array
     */
     protected $defaultIncludes = [
        
        ];

    public function transform(Assignment_Transaction $item)
    {
        return

            ['assignment_id' => $item->assignment_id,
                'payment_type'  => $item->payment_type,
                'status' => $item->status,
                'date' => $item->date,
                'amount' => $item->amount,
                'transaction_id' => $item->transaction_id];
            

    }


}

