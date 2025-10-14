<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            🔔 Notifikasi Saya
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @php
                        // Filter hanya OrderStatusUpdated
                        $orderNotifications = $notifications->filter(function($notification){
                            return $notification->type === 'App\Notifications\OrderStatusUpdated';
                        });
                    @endphp

                    @if($orderNotifications->count())
                        <ul class="divide-y divide-gray-200">
                            @foreach($orderNotifications as $notification)
                                <li class="py-4">
                                    <p class="font-semibold text-purple-600">
                                        🛒 Pesanan #{{ $notification->data['order_id'] ?? '' }}
                                    </p>
                                    <p>Status terbaru: 
                                        <span class="font-bold text-orange-600">
                                            {{ ucfirst($notification->data['status'] ?? '') }}
                                        </span>
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">Belum ada notifikasi pesanan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>