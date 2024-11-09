<x-backpack::menu-item class="space-x-2" :title="trans('backpack::base.dashboard')" icon="la la-home" :link="backpack_url('dashboard')" />
<x-backpack::menu-item class="space-x-2" :title="trans('users.users')" icon="la la-user" :link="backpack_url('user')" />
<x-backpack::menu-item class="space-x-2" :title="trans('categories.categories')" icon="la la-list-alt" :link="backpack_url('category')" />
<x-backpack::menu-item class="space-x-2" :title="trans('products.products')" icon="la la-box" :link="backpack_url('product')" />
