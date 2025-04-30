@extends('backend.layouts.app')
@section('title')
    {{ __('Wallets') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Available Currency') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">

                <div class="col-xl-12">
                    <div class="site-table table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                
                                @include('backend.filter.th',['label' => 'Country','field' => 'country_name'])
                                @include('backend.filter.th',['label' => 'Motto','field' => 'name'])
                                @include('backend.filter.th',['label' => 'Currency Symbol','field' => 'symbol'])
                                @include('backend.filter.th',['label' => 'Code ISO','field' => 'code'])
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($currencies as $currency)
                                <tr>
                                    <td>
                                        {{ $currency->country_name }}
                                    </td>
                                    <td>
                                        {{ $currency->name }}
                                    </td>
                                    <td>
                                        {{ $currency->symbol }}
                                    </td>
                                    <td>
                                        {{ $currency->code }}
                                    </td>
                                </tr>
                            @empty
                            <td colspan="7" class="text-center">{{ __('No Currency Found!') }}</td>
                            @endforelse
                            </tbody>
                        </table>

                        {{ $currencies->links('backend.include.__pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

