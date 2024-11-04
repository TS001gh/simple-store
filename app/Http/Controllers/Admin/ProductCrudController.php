<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Product\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Gate;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');

        $this->crud->addField([
            'name' => 'user_id',
            'type' => 'hidden',
            'value' => backpack_user()->id,
        ]);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('description');
        CRUD::column('price')
            ->type('number')
            ->decimals(2)
            ->prefix('$');

        CRUD::column('category_id')
            ->type('select')
            ->label('Category')
            ->entity('category')
            ->attribute('name');

        CRUD::column('image')
            ->type('image')
            ->height('50px')
            ->width('50px')
            ->prefix('storage/');

        CRUD::column('status')
            ->type('enum');
        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);
        // Name field
        CRUD::field('name')
            ->type('text')
            ->wrapper(['class' => 'form-group col-md-6']);

        // Description field
        CRUD::field('description')
            ->type('textarea')
            ->wrapper(['class' => 'form-group col-md-12']);

        // Price field
        CRUD::field('price')
            ->type('number')
            ->attributes(['step' => '0.01'])
            ->prefix('$')
            ->wrapper(['class' => 'form-group col-md-6']);

        // Category field (using select)
        CRUD::field('category_id')
            ->type('select')
            ->label('Category')
            ->entity('category')
            ->attribute('name')
            ->model('App\Models\Category')
            ->wrapper(['class' => 'form-group col-md-6']);

        // Image upload field
        CRUD::field('image')
            ->type('upload')
            ->upload(true)
            ->disk('public')
            ->wrapper(['class' => 'form-group col-md-6']);

        // Status field
        CRUD::field('status')
            ->type('select_from_array')
            ->options([
                'active' => 'Active',
                'inactive' => 'Inactive'
            ])
            ->default('active')
            ->wrapper(['class' => 'form-group col-md-6']);
        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
