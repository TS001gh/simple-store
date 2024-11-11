<?php

namespace App\Http\Controllers\Admin;

use App\Events\AdminCreatedUser;
use App\Http\Requests\UserRequest;
use App\Notifications\AdminCreatedUserNotification;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation {
        store as traitStore;
    }

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings(trans('users.user'), trans('users.users'));
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name')->label(trans('users.name'));
        CRUD::column('email')->label(trans('users.email'));
        CRUD::column('role')
            ->label(trans('users.role'))
            ->type('enum')
            ->options([
                'admin' => trans('users.admin_user'),
                'user' => trans('users.simple_user')
            ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $user = backpack_user();
        CRUD::setValidation(UserRequest::class);

        // Add translated fields
        CRUD::field('name')
            ->label(trans('users.name'))
            ->type('text');

        CRUD::field('email')
            ->label(trans('users.email'))
            ->type('email');

        CRUD::field('password')
            ->label(trans('users.password'))
            ->type('password');

        CRUD::field('role')
            ->label(trans('users.role'))
            ->type('select_from_array')
            ->options([
                'admin' => $user->role === 'admin' ? trans('users.admin_user') : '',
                'user' => trans('users.simple_user')
            ])
            ->default('user');
    }

    /**
     * Handle storing of the user data (create operation).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        // Call the parent store method to create the user
        $response = $this->traitStore();

        // After successfully creating the user, send the email verification notification
        if ($this->crud->entry) {
            $user = $this->crud->entry;

            // Send the email verification notification
            $user->sendEmailVerificationNotification();
        }

        // Return the response from the original store method
        return $response;
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
