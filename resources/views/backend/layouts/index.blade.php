@extends('backend.app')
@section('title', 'Dashboard')
@section('header_title', 'Dashboard')
@section('content')
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-3 mt-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="fs-2hx fw-bold text-primary me-2 lh-1">{{ $totalUsers }}</h1>
                                <h4 class="fs-5 fw-semibold text-muted mt-2">Total Users</h4>
                            </div>
                            <div>
                                <i class="bi bi-people-fill display-4 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3 mt-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="fs-2hx fw-bold text-success me-2 lh-1">{{ $totalFaqs }}</h1>
                                <h4 class="fs-5 fw-semibold text-muted mt-2">Total FAQs</h4>
                            </div>
                            <div>
                                <i class="bi bi-question-circle-fill display-4 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-3 mt-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="fs-2hx fw-bold text-info me-2 lh-1">{{ $totalDynamicPages }}</h1>
                                <h4 class="fs-5 fw-semibold text-muted mt-2">Dynamic Pages</h4>
                            </div>
                            <div>
                                <i class="bi bi-file-earmark-richtext-fill display-4 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Visits Card -->
                <div class="col-12 col-md-4 col-lg-3 mt-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="fs-2hx fw-bold text-warning me-2 lh-1">{{ array_sum($data) }}</h1>
                                <h4 class="fs-5 fw-semibold text-muted mt-2">Total Visits </h4>
                            </div>
                            <div>
                                <i class="bi bi-bar-chart-fill display-4 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h2 class="text-lg font-bold mb-4">Last 7 Days Visits</h2>
                            <p>This is status: </p>
                            <canvas id="visitorsChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function () {
        const ctx = document.getElementById('visitorsChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Visitors',
                    data: @json($data),
                    fill: true,
                    borderColor: 'rgba(59, 130, 246, 1)',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        console.log('Document ready, checking Echo availability...');
        
        // Function to setup Echo listeners
        function setupEchoListeners() {
            console.log('Echo object:', typeof Echo !== 'undefined' ? Echo : 'undefined');
            console.log('window.Echo:', typeof window.Echo !== 'undefined' ? window.Echo : 'undefined');
            
            // Check if Echo is available
            if (typeof Echo !== 'undefined') {
                console.log('Echo is available, setting up listener...');
                
                try {
                    console.log('Setting up test channel...');
                    // Test public channel first
                    Echo.channel('test-channel').listen('test-event', (e) => {
                        console.log('Test event received:', e);
                    });
                    console.log('Test channel setup complete');
                    
                    console.log('Setting up private channel chat.1...');
                    // Listen to the correct channel name - use conversation ID 1 for testing
                    const privateChannel = Echo.private('chat.1');
                    console.log('Private channel object:', privateChannel);
                    
                    privateChannel.listen('custom.message.created', (e) => {
                        console.log('CustomMessageCreated event (short name):', e);
                    });
                    console.log('Custom message listener added');
                    
                    // Also listen to the full namespace for debugging
                    privateChannel.listen('\App\Events\CustomMessageCreated', (e) => {
                        console.log('CustomMessageCreated event (full namespace):', e);
                    });
                    console.log('Full namespace listener added');
                    
                    // Listen for any event on this channel
                    privateChannel.listen('.', (e) => {
                        console.log('Any event received on private channel:', e);
                    });
                    console.log('Wildcard listener added');
                    
                    // Also try listening on the public channel
                    Echo.channel('chat.1').listen('custom.message.created', (e) => {
                        console.log('CustomMessageCreated event (public channel):', e);
                    });
                    console.log('Public channel listener added');
                    
                    // Add error handling for private channel
                    privateChannel.error((error) => {
                        console.error('Private channel error:', error);
                    });
                    console.log('Error handler added');
                    
                    // Add subscription success handler
                    privateChannel.subscribed(() => {
                        console.log('Successfully subscribed to private channel chat.1');
                    });
                    console.log('Subscription success handler added');
                    
                    // Debug all events on the private channel
                    privateChannel.subscription.bind('pusher:subscription_succeeded', (data) => {
                        console.log('Private channel subscription succeeded:', data);
                    });
                    
                    privateChannel.subscription.bind('pusher:subscription_error', (data) => {
                        console.error('Private channel subscription error:', data);
                    });
                    
                    // Listen for any event on the subscription
                    privateChannel.subscription.bind_global((eventName, data) => {
                        console.log('Global event received on private channel:', eventName, data);
                    });
                    
                    console.log('All listeners setup complete!');
                } catch (error) {
                    console.error('Error setting up listeners:', error);
                }
            } else {
                console.error('Echo is not available. Make sure Echo is properly initialized.');
                console.log('Available global objects:', Object.keys(window).filter(key => key.includes('Echo') || key.includes('Pusher')));
            }
        }
        
        // Try to setup listeners immediately
        setupEchoListeners();
        
        // If Echo is not available, try again after a short delay
        if (typeof Echo === 'undefined') {
            setTimeout(setupEchoListeners, 1000);
            setTimeout(setupEchoListeners, 2000);
            setTimeout(setupEchoListeners, 3000);
        }
        
        // Also check authentication status
        console.log('Checking authentication status...');
        console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
        console.log('User authenticated:', {{ auth()->check() ? 'true' : 'false' }});
        console.log('User ID:', {{ auth()->id() ?? 'null' }});
        
        // Check Pusher configuration
        console.log('Pusher App Key:', '{{ config("broadcasting.connections.pusher.key") }}');
        console.log('Pusher Cluster:', '{{ config("broadcasting.connections.pusher.options.cluster") }}');
        console.log('Pusher App ID:', '{{ config("broadcasting.connections.pusher.app_id") }}');
    });
</script>
@endpush
