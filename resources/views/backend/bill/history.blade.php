@extends('backend.layouts.app')
@section('title')
{{ __($statusForFrontend.' Bill') }}
@endsection
@section('content')
<div class="main-content">
    <div class="page-title">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="title-content">
                        <h2 class="title">{{ __($statusForFrontend.' Bill') }}</h2>
                        <div class="card-header-info" style="display: inline-block; background: rgba(42, 157, 143, 0.25); border-radius: 15px; padding: 6px 16px; border: none; color: #2a9d8f; font-size: 14px; font-weight: 700; float: right; margin:15px;">Total Profits: 0 XOF</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">

            <div class="col-xl-12">
                <div class="site-table table-responsive">
                    @include('backend.bill.include.__filter', ['status' => false, 'type' => false ])
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Method') }}</th>
                                <th>{{ __('Service') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Charge') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Created') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bill as $item)
                            <tr>
                                <td>
                                    {{ $item->mthod }}
                                </td>
                                <td>
                                    {{ $item->service->name }}
                                </td>
                                <td>
                                    {{ $item->service->type }}
                                </td>
                                <td>
                                    {{ $item->amount }}
                                </td>
                                <td>
                                    {{ $item->charge }}
                                </td>
                                <td>
                                    @include('backend.bill.include.__user', ['id' => $item->user_id, 'name' =>
                                    $item->user->username])
                                </td>
                                <td>
                                    {{ safe($item->created_at) }}
                                </td>
                                <td>
                                    @include('backend.bill.include.__loan_status', ['txnStatus' => $item->status->value])
                                </td>
                            
                            </tr>
                            @empty
                            <td colspan="9" class="text-center">{{ __('No Data Found!') }}</td>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $bill->links('backend.include.__pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
