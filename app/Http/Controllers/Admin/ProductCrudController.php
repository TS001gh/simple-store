<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings(trans('products.product'), trans('products.products'));

        $this->crud->addField([
            'name' => 'user_id',
            'type' => 'hidden',
            'value' => backpack_user()->id,
        ]);
    }

    protected function setupListOperation()
    {
        CRUD::column('name')
            ->label(trans('products.name'));

        CRUD::column('description')
            ->label(trans('products.description'));

        CRUD::column('price')
            ->label(trans('products.price'))
            ->type('number')
            ->decimals(2)
            ->prefix('$');

        CRUD::column('category_id')
            ->type('select')
            ->label(trans('products.category'))
            ->entity('category')
            ->attribute('name');

        CRUD::column('image')
            ->label(trans('products.image'))
            ->type('image')
            ->height('50px')
            ->width('50px')
            ->prefix('storage/');

        CRUD::column('status')
            ->label(trans('products.status'))
            ->type('enum');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);
        Product::creating(function ($entry) {
            $entry->user_id = backpack_user()->id;
        });

        CRUD::field('name')
            ->label(trans('products.name'))
            ->type('text')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('description')
            ->label(trans('products.description'))
            ->type('textarea')
            ->wrapper(['class' => 'form-group col-md-12']);

        CRUD::field('price')
            ->label(trans('products.price'))
            ->type('number')
            ->attributes(['step' => '0.01'])
            ->prefix('$')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('category_id')
            ->type('select')
            ->label(trans('products.category'))
            ->entity('category')
            ->attribute('name')
            ->model('App\Models\Category')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('image')
            ->label(trans('products.image'))
            ->type('upload')
            ->upload(true)
            ->disk('public')
            ->wrapper(['class' => 'form-group col-md-6']);

        CRUD::field('status')
            ->label(trans('products.status'))
            ->type('select_from_array')
            ->options([
                'active' => trans('products.active'),
                'inactive' => trans('products.inactive')
            ])
            ->default('active')
            ->wrapper(['class' => 'form-group col-md-6']);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
