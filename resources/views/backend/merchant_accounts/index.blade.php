@extends('backend.layouts.app')
@section('title')
    {{ __('Merchant Accounts') }}
@endsection
@section('content')
    <div class="main-content">
        <div class="page-title">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <div class="title-content">
                            <h2 class="title">{{ __('Merchant Accounts') }}</h2>
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
                                                <form action="{{ request()->url() }}" method="get">
                            <div class="table-filter">
                                <div class="filter">
                                    <div class="search">
                                        <input type="text" id="search" name="query" value="{{ request('query') }}"
                                            placeholder="Search" />
                                    </div>
                                    <select name="email_status" id="email_status" class="form-select form-select-sm">
                                        <option value="" selected>{{ __('Filter By Email Status') }}</option>
                                        <option value="verified" {{ request('email_status') == 'verified' ? 'selected' : '' }}>{{ __('Email Verified') }}</option>
                                        <option value="unverified" {{ request('email_status') == 'unverified' ? 'selected' : '' }}>{{ __('Email Unverified') }}</option>
                                    </select>
                                    <select name="kyc_status" id="kyc_status" class="form-select form-select-sm">
                                        <option value="" selected>{{ __('Filter By KYC') }}</option>
                                        <option value="1" {{ request('kyc_status') == '1' ? 'selected' : '' }}>{{ __('Verified') }}</option>
                                        <option value="0" {{ request('kyc_status') == '0' ? 'selected' : '' }}>{{ __('Unverified') }}</option>
                                    </select>

                                    <select name="status" id="status" class="form-select form-select-sm">
                                        <option value="" selected>{{ __('Filter By Status') }}</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>{{ __('Disabled') }}</option>
                                        <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>{{ __('Closed') }}</option>
                                    </select>
                                    <button type="submit" class="apply-btn"><i data-lucide="search"></i>{{ __('Search') }}</button>
                                </div>
                            </div>
                        </form>
                        <table class="table">
                            <thead>
                                <tr>
                                    @include('backend.filter.th', ['label' => 'Id', 'field' => 'id'])
                                    @include('backend.filter.th', ['label' => 'Account Number', 'field' => 'account_number'])
                                    @include('backend.filter.th', ['label' => 'Merchant UserName', 'field' => 'username'])
                                    @include('backend.filter.th', ['label' => 'Balance', 'field' => 'balance'])
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($merchantAccounts as $account)
                                    <tr>
                                        <!-- Account Id in user table -->
                                        <td>{{ $account->id }}</td>
                                        
                                        <!-- Account Number -->
                                        <td>{{ $account->account_number }}</td>
                        
                                        <!-- User Name -->
                                        <td>
                                            <a href="" class="link">
                                                {{ Str::limit($account->username, 15) }}
                                            </a>
                                        </td>
                        
                                        <!-- Balance -->
                                        <td>{{ $account->balance }}</td>
                        
                                        <!-- Actions -->
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <!-- View Icon -->
                                                <a href="" class="round-icon-btn blue-btn" data-bs-toggle="tooltip" data-bs-original-title="{{ __('View') }}">
                                                    <i data-lucide="eye"></i>
                                                </a>
                        
                                                <!-- Edit Icon -->
                                                <a href="" class="round-icon-btn green-btn" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}">
                                                    <i data-lucide="edit-3"></i>
                                                </a>
                        
                                                <!-- Delete Icon -->
                                                <form action="" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="round-icon-btn red-btn" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Delete') }}">
                                                        <i data-lucide="trash-2"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">{{ __('No Merchants Found!') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <!--link pagination here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

