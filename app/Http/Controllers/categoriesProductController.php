<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class categoriesProductController extends Controller
{

    public function index($categorySlug, Request $request)
    {
        $filterArray = $this->filter($request);

        return DB::select('select p.* from products p '.$filterArray['join_type'].' join order_items i on i.product_id=p.id inner join product_categories pc on pc.product_id=p.id inner join categories c on c.id=pc.category_id where c.slug=? '.$filterArray['sql_condition'], [$categorySlug]);

    }

    public function filter($request)
    {
        $filter = $request->filter;
        switch ($filter){
            case 'without_order':
                $join_type = 'left';
                $sql_condition = 'and i.id is null';
                break;
            case 'with_order':
                $join_type = 'inner';
                $sql_condition = '';
                break;
            case 'all':
                $join_type = 'left';
                $sql_condition = '';
                break;
        }
        return ['join_type' => $join_type, 'sql_condition' => $sql_condition];
    }

}
