@switch($txnStatus)
    @case('pending')
        <div class="site-badge pending">{{ __('Pending') }}</div>
        @break
    @case('running')
        <div class="site-badge success">{{ __('Running') }}</div>
        @break
    @case('due')
        <div class="site-badge danger">{{ __('Due') }}</div>
        @break
    @case('paid')
        <div class="site-badge danger">{{ __('Paid') }}</div>
        @break
    @case('rejected')
        <div class="site-badge danger">{{ __('Rejected') }}</div>
        @break
    @case('completed') <!-- Add this case -->
        <div class="site-badge success">{{ __('Completed') }}</div>
        @break
    @default
        <div class="site-badge default">{{ __('Unknown') }}</div>        
@endswitch