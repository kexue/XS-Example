<?php
/**
 * Created by PhpStorm.
 * User: kexue
 * Date: 2018/5/11
 * Time: 下午4:41
 */
namespace App\Http\Controllers;

use App\Libs\Xunsearch\Search;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $search;

    public function __construct(Request $request)
    {
        $config = [
            'flush_index'   => (bool)$request->input('flush_index'),
            'set_fuzzy'     => (bool)$request->input('set_fuzzy'),
        ];
        $this->search = new Search($this->projectName, $config);
    }

}