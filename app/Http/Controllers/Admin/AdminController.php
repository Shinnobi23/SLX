<?php

namespace app\Http\Controllers\Admin;

use App\Models\Sale;
use Auth;
use Charts;

class AdminController extends \Backpack\Base\app\Http\Controllers\AdminController
{
    public function __construct()
    {
        $user = Auth::user();

        if ($user) {
            if ($user->hasRole('Inspector') || $user->hasRole('Inventor')) {
                return redirect('/admin/factory');
            }

            if ($user->hasRole('Seller')) {
                return redirect('/admin/cart');
            }
        }

        parent::__construct();
    }

    public function dashboard()
    {
        $data['title'] = trans('backpack::base.dashboard');

        $data['sale'] = [
            'count' => [
                'all'   => Sale::getSaleCount('m', 'all'),
                'paid'  => Sale::getSaleCount('m', 'paid'),
                'debit' => Sale::getSaleCount('m', 'debit'),
            ],
            'totalIncome' => Sale::getTotalIncome('m'),
        ];

        $data['chart'] = Charts::database(Sale::all(), 'bar', 'highcharts')
            ->title('ຍອດຂາຍປະຈໍາເດືອນ')
            ->elementLabel('ຍອດຂາຍທັງໝົດ')
            ->dimensions(1000, 500)
            ->responsive(true)
            ->groupByDay();

        return view('backpack::dashboard', $data);
    }

  // public function redirect()
  // {
  //   // The '/admin' route is not to be used as a page, because it breaks the menu's active state.
  //   $user = Auth::user();

  //   if ($user->hasRole('Administrator') || $user->hasRole('Manager'))
  //   {
  //     return redirect('/admin/dashboard');
  //   }

  //   if ($user->hasRole('Inspector') || $user->hasRole('Inventor'))
  //   {
  //     return redirect('/admin/factory');
  //   }

  //   if ($user->hasRole('Seller'))
  //   {
  //     return redirect('/admin/cart');
  //   }
  // }
}
