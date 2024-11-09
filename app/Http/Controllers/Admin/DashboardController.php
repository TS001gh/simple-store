<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Backpack\CRUD\app\Http\Controllers\AdminController as ControllersAdminController;

class DashboardController extends ControllersAdminController
{
    //
    // public function index()
    // {


    //     $chart = new DashboardChart;
    //     $chart->labels(['One', 'Two', 'Three', 'Four']);
    //     $chart->dataset('My dataset', 'line', [1, 2, 3, 4]);
    //     $chart->dataset('My dataset 2', 'line', [4, 3, 2, 1]);


    //     // Chart 1: Distribution of Products by Category
    //     $categoryDistributionChart = new DashboardChart;
    //     $categoryDistributionChart->labels(Category::pluck('name')->toArray())
    //         ->dataset('Products by Category', 'pie', Product::all()
    //             ->groupBy('category_id')
    //             ->map->count()
    //             ->values()
    //             ->toArray())
    //         ->options([
    //             'height' => 300,
    //             'title' => [
    //                 'display' => true,
    //                 'text' => 'Distribution of Products by Category'
    //             ]
    //         ]);

    //     // Chart 2: Products Added Monthly
    //     $monthlyProductsChart = new Chart;
    //     $monthlyProductCounts = Product::whereYear('created_at', Carbon::now()->year)
    //         ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
    //         ->groupBy('month')
    //         ->orderBy('month')
    //         ->pluck('count')
    //         ->toArray();

    //     $monthlyProductsChart->labels([
    //         'January',
    //         'February',
    //         'March',
    //         'April',
    //         'May',
    //         'June',
    //         'July',
    //         'August',
    //         'September',
    //         'October',
    //         'November',
    //         'December'
    //     ])
    //         ->dataset('Monthly Product Count', 'line', $monthlyProductCounts)
    //         ->options([
    //             'height' => 300,
    //             'width' => 600,
    //             'title' => [
    //                 'display' => true,
    //                 'text' => 'Products Added Monthly'
    //             ]
    //         ]);

    //     // Chart 3: Top 5 Most Populated Categories
    //     $topCategories = Category::withCount('products')
    //         ->orderBy('products_count', 'desc')
    //         ->take(5)
    //         ->get();


    //     $topCategoriesChart = new Chart;
    //     $topCategoriesChart->labels($topCategories->pluck('name')->toArray())
    //         ->dataset('Product Count', 'bar', $topCategories->pluck('products_count')->toArray())
    //         ->options([
    //             'height' => 300,
    //             'width' => 600,
    //             'title' => [
    //                 'display' => true,
    //                 'text' => 'Top 5 Categories by Product Count'
    //             ]
    //         ]);

    //     return view('admin.dashboard', compact('categoryDistributionChart', 'monthlyProductsChart', 'topCategoriesChart', 'chart'));
    // }
    public function index()
    {
        // Calculate statistics
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        $inactiveProducts = Product::where('status', 'inactive')->count();
        $totalCategories = Category::count();
        $productsWithoutCategory = Product::whereNull('category_id')->count();

        // Prepare stats array
        $stats = [
            [
                'title' => 'إجمالي المنتجات',
                'count' => $totalProducts,
                'total' => $totalProducts,
                'percentage' => '100%',
                'icon' => 'la-box',
                'color' => 'primary'
            ],
            [
                'title' => 'المنتجات النشطة',
                'count' => $activeProducts,
                'total' => $totalProducts,
                'percentage' => $this->calculatePercentage($activeProducts, $totalProducts),
                'icon' => 'la-check-circle',
                'color' => 'success'
            ],
            [
                'title' => 'المنتجات غير النشطة',
                'count' => $inactiveProducts,
                'total' => $totalProducts,
                'percentage' => $this->calculatePercentage($inactiveProducts, $totalProducts),
                'icon' => 'la-pause-circle',
                'color' => 'danger'
            ],
            [
                'title' => 'إجمالي التصنيفات',
                'count' => $totalCategories,
                'total' => $totalCategories,
                'percentage' => null,
                'icon' => 'la-tags',
                'color' => 'info'
            ],
            [
                'title' => 'منتجات بدون تصنيف',
                'count' => $productsWithoutCategory,
                'total' => $totalProducts,
                'percentage' => $this->calculatePercentage($productsWithoutCategory, $totalProducts),
                'icon' => 'la-exclamation-circle',
                'color' => 'warning'
            ],
            [
                'title' => 'متوسط السعر',
                'count' => number_format(Product::avg('price'), 2),
                'total' => null,
                'percentage' => null,
                'icon' => 'la-dollar-sign',
                'color' => 'secondary'
            ],
        ];

        //   // Assign $stats to data for the view
        $this->data['stats'] = $stats;

        // Add the widget using the $stats variable
        $widgets['after_content'][] = [
            'type' => 'view',
            'view' => 'components.stats-card',
            'data' => ['stats' => $stats], // Pass $stats here instead of $this->data['stats']
        ];

        // Default Backpack data
        $this->data['title'] = trans('backpack::base.dashboard');
        $this->data['breadcrumbs'] = [
            trans('backpack::crud.admin') => backpack_url('dashboard'),
            trans('backpack::base.dashboard') => false,
        ];

        return view(backpack_view('dashboard'), $this->data);
    }

    private function calculatePercentage($value, $total)
    {
        if ($total == 0) return '0%';
        return round(($value / $total) * 100) . '%';
    }
}
