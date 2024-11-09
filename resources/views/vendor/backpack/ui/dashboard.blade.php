@extends(backpack_view('blank'))


@php
    // if (backpack_theme_config('show_getting_started')) {
    //     $widgets['before_content'][] = [
    //         'type' => 'view',
    //         'view' => backpack_view('inc.getting_started'),
    //     ];
    // } else {
    //     $widgets['before_content'][] = [
    //         'type' => 'jumbotron',
    //         // 'heading' => trans('backpack::base.welcome'),
    //         'heading_class' =>
    //             'display-3 ' . (backpack_theme_config('layout') === 'horizontal_overlap' ? ' text-white' : ''),
    //         'content' => trans('backpack::base.use_sidebar'),
    //         'content_class' => backpack_theme_config('layout') === 'horizontal_overlap' ? 'text-white' : '',
    //         // 'button_link' => backpack_url('logout'),
    //         // 'button_text' => trans('backpack::base.logout'),
    //     ];
    // }
@endphp

@section('content')
    <div class="row d-inline-flex">
        @foreach ($stats as $stat)
            <x-stats-card :title="$stat['title']" :count="$stat['count']" :total="$stat['total']" :percentage="$stat['percentage']" :icon="$stat['icon']"
                :color="$stat['color']" />
        @endforeach
    </div>
@endsection
